<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caccount extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	// 

	function __construct() {
		parent::__construct();

		if( !check_islogin() ) {
			redirect('/ingresar');
			die;
		}

		$this->usersession = $this->session->userdata('user_data');
		$this->load->model('caccount_model');
		$this->load->model('cutils_model');
		// $this->load->model('ccourses_model');
		$this->load->model('cfavorites_model');
		// $this->load->model('clogin_model');
	}

	public function index()
	{	
		$data["sessionuser"] = $this->usersession;
		$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
		$data["user"] = $this->caccount_model->getUsername();
		$data["header"] = $this->load->view('templates/header_detail', $data, true);
		$data["footer"] = $this->load->view('templates/footer', $data, true);

		$countries_a = array();
		$countries = $this->cutils_model->getAllCountries();
		$countries_a[""] = "Seleccione...";
		if( $countries ) {
			foreach ($countries as $key => $value) {
				$countries_a[$value->id] = $value->name;
			}
		}

		$data["countries"] = $countries_a;

		$this->load->view('account/account', $data);
	}


	public function updateUser(){
		$actualiza = $this->caccount_model->updateUsername();
		if($actualiza){
			$this->session->set_flashdata('msg','<span class="msg">Datos actualizados correctamente.</span>');
		}else{
			$this->session->set_flashdata('msg','<span class="msg">No pudimos actualizar tus datos. Posiblemente la clave qe ingresaste está errónea o que no modificaste ningún dato.</span>');
		}
		redirect('/cuenta');
	}


	public function changePassword(){
		$actualiza = $this->caccount_model->updatePassword();
		if($actualiza){
			$this->session->set_flashdata('msg-pw','<span class="msg">¡Se ha actualizado tu password!</span>');
		}else{
			$this->session->set_flashdata('msg-pw','<span class="msg">No pudimos actualizar tu password. Posiblemente la clave actual que ingresaste está errónea, que no modificaste ningún dato o que indicaste la misma clave que ocupas actualmente.</span>');
		}
		redirect('/cuenta');
	}


}
