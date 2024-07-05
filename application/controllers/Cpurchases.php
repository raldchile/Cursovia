<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cpurchases extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Carga el modelo de usuarios
        $this->usersession = $this->session->userdata('user_data');
        $this->load->model('cpaybutton_model');
        $this->load->model('ccourses_model');
        $this->load->model('caccount_model');
        $this->load->model('cpurchases_model');

        $session_id = $this->session->userdata('session_id');
        if (!$session_id) {
            $session_id = uniqid() . date('YmdHis');
            $this->session->set_userdata('session_id', $session_id);
            $this->ccourses_model->setSessionCourses();
        }
    }

    public function index()
    {
        if (!check_islogin()) redirect('/ingresar');
        $data["sessionuser"] = $this->usersession;
        $data['purchases'] = $this->cpurchases_model->getPurchases();
        $data["user"] = $this->caccount_model->getUsername();
        $data["cant_favorites"] = $this->cfavorites_model->countFavorites();
        $data["header"] = $this->load->view('templates/header_detail', $data, true);
        $data["footer"] = $this->load->view('templates/footer', $data, true);
        $this->load->view('purchases/purchases', $data);
    }

    public function purchase_detail($oc = '')
    {
        if (!check_islogin()) redirect('/ingresar');
        $data["sessionuser"] = $this->usersession;
        $data['details'] = $this->cpurchases_model->getAdded($oc);
        $data['oc'] = $this->cpaybutton_model->getOcDetails($oc);
        $data['button'] = $this->cpaybutton_model->getButton($data['oc']['button_token']);
        $data['invoice'] = $this->cpaybutton_model->getInvoiceDetails($data['oc']['invoice_client_id']);
        $data["user"] = $this->caccount_model->getUsername();
        $data["cant_favorites"] = $this->cfavorites_model->countFavorites();
        $data["header"] = $this->load->view('templates/header_detail', $data, true);
        $data["footer"] = $this->load->view('templates/footer', $data, true);
        $this->load->view('purchases/purchases-detail', $data);
    }

    public function add_participant($oc_id = '')
    {   
        $data['added'] = $this->cpurchases_model->addParticipant($oc_id);
        echo json_encode($data);
    }

}
