<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cpurchases_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('clogin_model');
		$this->load->model('cutils_model');
        $this->usersession = $this->session->userdata('user_data');
    }

    public function getPurchases()
    {   
        $dbc = $this->load->database('cursovia', TRUE);
        $sessionuser = $this->usersession;
        $dbc->from('oc');
        $dbc->where('user_id', $sessionuser["id"]);
        $dbc->order_by('updated', 'desc');
        $oc = $dbc->get();
        return $oc->result();
    }

    public function getAdded($oc = '')
    {      
        
        $oc_id = $this->cpaybutton_model->getOcDetails($oc);
        $oc_id = $oc_id['id'];
        $dbc = $this->load->database('cursovia', TRUE);
        $dbc->from('oc_detail');
        $dbc->where('oc_id', $oc_id);
        $oc = $dbc->get();
        return $oc->result();
    }

    public function addParticipant($oc_id = '')
    {      
        
        $dbc = $this->load->database('cursovia', TRUE);
        $dbc->from('oc');
        $dbc->where('id', $oc_id);
        $result = $dbc->get()->row_array();

        $quantity = $result['quantity'];
        $oc = $result['oc'];
        
        $added = count($this->getAdded($oc));

        if($quantity > $added){
            $req = $this->input->post();
            $data = [
                'oc_id' => $oc_id,
                'name' => $req['name'],
                'last_name' => $req['last_name'],
                'rut' => $req['rut'],
                'email' => $req['email'],
            ];
            $dbc->insert('oc_detail', $data);
            return true;
        } else return false;


    }






}
