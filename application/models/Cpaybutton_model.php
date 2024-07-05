<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cpaybutton_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('clogin_model');
        $this->load->model('cutils_model');
        $this->usersession = $this->session->userdata('user_data');
    }

    public function getButton($token = '')
    {
        $this->db->select('pb.*, c.slug as course_slug, c.id as course_id, cl.slug as client_slug, cl.id as client_id');
        $this->db->from('paybutton as pb');
        $this->db->join('courses as c', 'pb.course_id = c.id');
        $this->db->join('clients as cl', 'c.client_id = cl.id');
        $this->db->where('token', $token);
        $query = $this->db->get();
        $button = $query->row_array();
        //echo $this->db->last_query().' '.json_encode($button);

        return $button;
    }

    public function createOc($data)
    {
        $dbc = $this->load->database('cursovia', TRUE);

        $sessionuser = $this->usersession;

        $client = $this->cutils_model->checkClient($data['client_id'], 'datos');
        $dbc->from('users');
        $dbc->where('id', $sessionuser["id"]);
        $dbc->where('status', 1);
        $user = $dbc->get();
        $user = $user->row_array();

        $ocData = [
            'client_id' => $data['client_id'],
            'user_id' => $sessionuser["id"],
            'course_id' => $data['course_id'],
            'quantity' => $data['quantity'],
            'invoice_client_id' => $data['invoice_client_id'],
            'type_pay_id' => '',
            'oc' => $data["oc"],
            'payment_status' => 0,
            'mc_gross' => '',
            'mc_currency' => '',
            'receiver_email' => $client['email'],
            'payer_email' => $user['emilio'],
            'invoiced' => '',
            'created' => date('Y-m-d H:i:s'),
        ];

        $dbc->insert('cursovia_oc', $ocData);
    }



    public function updateOc($data)
    {
        // Cargar la base de datos 'cursovia'
        $dbc = $this->load->database('cursovia', TRUE);

        $ocData = [
            'payment_status' => 1,
            'mc_gross' => $data['mc_gross'],
            'mc_currency' => $data['mc_currency'],
            'invoiced' => $data['invoiced'],
        ];

        $dbc->where('oc', $data["oc"]);
        $dbc->update('cursovia_oc', $ocData);
    }

    public function getOcDetails($oc)
    {
        $dbc = $this->load->database('cursovia', TRUE);
        $dbc->from('oc');
        $dbc->where('oc', $oc);
        $query = $dbc->get();
    
        if ($query->num_rows() > 0) {
            $ocDetails = $query->row_array();
            $this->db->select('pb.token');
            $this->db->from('paybutton as pb');
            $this->db->where('course_id', $ocDetails['course_id']);
            $query = $this->db->get();
            $button = $query->row_array();
    
            $ocDetails['button_token'] = $button['token'];
    
            return $ocDetails;
        }
    }

    public function createInvoice($data){
        $dbc = $this->load->database('cursovia', TRUE);

        $invoiceData = [
            'razon_social' => $data['name'],
            'giro' => $data['giro'],
            'rut' => $data['rut'],
            'email' => $data['email'],
            'direccion' => $data['address'],
            'ciudad' => $data['city'],
            'comuna' => $data['comuna'],
        ];

        $dbc->insert('cursovia_invoice', $invoiceData);
        return $dbc->insert_id();
    }

    public function updateInvoice($data)
    {
        // Cargar la base de datos 'cursovia'
        $dbc = $this->load->database('cursovia', TRUE);

        $invoiceData = [
            'folio' => $data["folio"],
        ];

        $dbc->where('id', $data["id"]);
        $dbc->update('cursovia_invoice', $invoiceData);
    }

    public function getInvoiceDetails($id)
    {
        $dbc = $this->load->database('cursovia', TRUE);
        $dbc->from('invoice');
        $dbc->where('id', $id);
        $query = $dbc->get();
        return $query->row_array();
    
    }

}
