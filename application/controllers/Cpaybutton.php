<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;

class Cpaybutton extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Carga el modelo de usuarios
        $this->usersession = $this->session->userdata('user_data');
        $this->load->model('cpaybutton_model');
        $this->load->model('ccourses_model');
        $this->load->model('caccount_model');
        $this->load->model('cutils_model');

        /* if (ENVIRONMENT === 'production') {
            \Transbank\Webpay\WebpayPlus::configureForProduction(config_item('webpay_plus_cc'), config_item('webpay_plus_api_key'));
        } else {
            \Transbank\Webpay\WebpayPlus::configureForTesting();
        } */

        \Transbank\Webpay\WebpayPlus::configureForTesting();


        $session_id = $this->session->userdata('session_id');
        if (!$session_id) {
            $session_id = uniqid() . date('YmdHis');
            $this->session->set_userdata('session_id', $session_id);
            $this->ccourses_model->setSessionCourses();
        }
    }

    public function index($token = '')
    {
        if (!check_islogin()) redirect('/ingresar');

        $buttom = $this->cpaybutton_model->getButton($token);
        $course_slug = $buttom['course_slug'];
        $client_slug = $buttom['client_slug'];
        $data["user"] = $this->caccount_model->getUsername();
        $data['button'] = $buttom;
        $data["courses"] = $this->ccourses_model->getAllCourses($course_slug, 2, 1, 0);
        $data["client"] = $this->ccourses_model->getClient($client_slug);
        $data["sessionuser"] = $this->usersession;
        $data["cant_favorites"] = $this->cfavorites_model->countFavorites();
        $data["header"] = $this->load->view('templates/header_detail', $data, true);
        $data["footer"] = $this->load->view('templates/footer', $data, true);
        $this->load->view('paybutton/paybutton', $data);
    }


    public function createTransaction()
    {
        $req = $this->input->post();
        $return_url = base_url("paycomitted");
        $resp = (new Transaction)->create($req["buy_order"], $req["session_id"], $req["amount"], $return_url);

        $buttom = $this->cpaybutton_model->getButton($req["button_token"]);
        $course_id = $buttom['course_id'];
        $client_id = $buttom['client_id'];

        $invoiceData = [
            'number' => 0,
            'name' => $req['name'],
            'giro' => $req['giro'],
            'rut' => $req["rut"],
            'email' => $req["email"],
            'address' => $req["address"],
            'city' => $req["city"],
            'comuna' => $req['comuna']
        ];
        $invoice_id = $this->cpaybutton_model->createInvoice($invoiceData);

        $ocData = [
            'client_id' => $client_id,
            'course_id' => $course_id,
            'quantity' => $req["quantity"],
            'oc' => $req["buy_order"],
            'invoice_client_id' => $invoice_id,
        ];
        $this->cpaybutton_model->createOc($ocData);


        echo json_encode($resp);
    }



    public function commitTransaction()
    {
        $data["sessionuser"] = $this->usersession;
        $data["cant_favorites"] = $this->cfavorites_model->countFavorites();
        $data["header"] = $this->load->view('templates/header_detail', $data, true);
        $data["footer"] = $this->load->view('templates/footer', $data, true);
        $tokenws = $this->input->get("token_ws") ? $this->input->get("token_ws") : $this->input->get("token_ws");

        if ($tokenws) {
            $resp = $this->transbanktransaction->commit($tokenws);
            if ($resp->isApproved()) {

                $oc = $this->cpaybutton_model->getOcDetails($resp->getBuyOrder());
                $course_id = $oc['course_id'];

                $data['course_name'] = $this->ccourses_model->getCourseName($course_id);

                $client_id = $this->ccourses_model->getClientofCourse($course_id);
                $client = $this->cutils_model->checkClient($client_id, 'datos');

                $data['client'] = $client;
                $data["resp"] =  $resp;
                $data['oc'] =  $oc;
                $amount = $resp->getAmount();
                $currency = (is_float($amount + 0)) ? 'USD' : 'CLP';
                $data['currency'] = $currency;

                $ocData = [
                    'oc' => $resp->getBuyOrder(),
                    'mc_gross' => $amount,
                    'mc_currency' => $currency,
                    'invoiced' => 1,
                ];

                $this->cpaybutton_model->updateOc($ocData);

                $webpayData = [
                    'Tbk_respuesta' => $resp->getResponseCode(),
                    'Tbk_orden_compra' => $resp->getBuyOrder(),
                    'Tbk_id_sesion' => $resp->getSessionId(),
                    'Tbk_codigo_autorizacion' => $resp->getAuthorizationCode(),
                    'Tbk_monto' => $resp->getAmount(),
                    'Tbk_numero_tarjeta' => $resp->getCardDetail()['card_number'],
                    'Tbk_numero_final_tarjeta' => $resp->getCardDetail()['card_number'],
                    'Tbk_fecha_expiracion' => $resp->getTransactionDate(),
                    'Tbk_fecha_contable' => $resp->getTransactionDate(),
                    'Tbk_fecha_transaccion' => $resp->getTransactionDate(),
                    'Tbk_id_transaccion' => $resp->getBuyOrder(),
                    'Tbk_tipo_pago' => $resp->getPaymentTypeCode(),
                    'Tbk_numero_cuotas' => $resp->getInstallmentsNumber(),
                    'Tbk_mac' => $tokenws,
                    'Tbk_monto_cuota' => $resp->getInstallmentsAmount() ? $resp->getInstallmentsAmount() : '',
                    'Tbk_VCI' => $resp->getVci(),
                    'Tbk_ip' => $_SERVER['REMOTE_ADDR']
                ];

                $dbc = $this->load->database('webpay', TRUE);
                $dbc->insert('webpay', $webpayData);

                $sessionuser = $this->usersession;

                $FmaPago = ($resp->getPaymentTypeCode() == 'VD' || $resp->getPaymentTypeCode() == 'VP')  ? 1 : 2;

                $amount = intval($amount);
                $quantity = intval($oc['quantity']);

                $PrcItem = round($amount / $quantity);
                $iva = round($PrcItem / 1.19);
                $neto = round($amount - $iva);

                $invoice = $this->cpaybutton_model->getInvoiceDetails($oc['invoice_client_id']);
                $data['invoice'] = $invoice;

                $invoiceData = [
                    'FmaPago' => $FmaPago,
                    'RUTRecep' => $invoice['rut'],
                    'RznSocRecep' => $invoice['razon_social'],
                    'GiroRecep' => $invoice['giro'],
                    'CorreoRecep' => $invoice['email'],
                    "DirRecep" => $invoice['direccion'],
                    "CmnaRecep" => $invoice['comuna'],
                    "CiudadRecep" => $invoice['ciudad'],
                    "MntNeto" => $neto,
                    "IVA" => $iva,
                    'MntTotal' => $amount,
                    "NroLinDet" => "1",
                    "NmbItem" => $data['course_name'],
                    "QtyItem" => $oc['quantity'],
                    "UnmdItem" => "un",
                    "PrcItem" => $PrcItem,
                    "MontoItem" => $amount,
                ];

                /* echo '<pre>';
                print_r($invoiceData);
                echo '</pre>'; */

                $response_invoice = $this->cutils_model->createInvoiceV2($invoiceData);
                $data['response_invoice'] = $response_invoice;

                /* echo '<pre>';
                print_r($response_invoice);
                echo '</pre>' */;

                $invoiceUpdateData = [
                    'id' => $invoice['id'],
                    'folio' => $response_invoice->data->folio,
                ];
                
                $this->cpaybutton_model->updateInvoice($invoiceUpdateData);

                $emailData = [
                    'folio' => $response_invoice->data->folio,
                    "course_name" => $data['course_name'],
                    'quantity' => $quantity,
                ];

                $this->cutils_model->sendInvoiceEmail($emailData);



                $this->load->view('paybutton/transaction_committed', $data);
            } else {
                $data["resp"] = $this->input->get() ? $this->input->get() : $this->input->post();
                $this->load->view('paybutton/transaction_aborted', $data);
            }
        } else if ($this->input->get("TBK_TOKEN") or $this->input->post("TBK_TOKEN")) {
            $data["resp"] = $this->input->get() ? $this->input->get() : $this->input->post();
            $this->load->view('paybutton/transaction_aborted', $data);
        } else {
            $data["resp"] = $this->input->get() ? $this->input->get() : $this->input->post();
            $this->load->view('paybutton/transaction_timeout', $data);
        }
    }

    // En tu controlador o donde manejes tus rutas
public function downloadInvoicePdf()
{
    $folio = $this->input->get('folio');

    $pdfContent = $this->cutils_model->getInvoicePdf($folio);

    if ($pdfContent) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="factura_' . $folio . '.pdf"');
        echo $pdfContent;
    } else {
        echo "No se pudo obtener el PDF.";
    }
}




}
