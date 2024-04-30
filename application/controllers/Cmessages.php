<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cmessages extends CI_Controller {

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
		$this->load->model('cmessages_model');
		$this->usersession = $this->session->userdata('user_data');
		$this->load->model('cfavorites_model');
		$this->load->model('caccount_model');



	}

	public function index()
	{

		// $data["courses"] = $this->ccourses_model->getAllCourses();
		// $data["favorites"] = $this->cfavorites_model->getFavorites();
		// $this->load->view('index/index_cursovia', $data);
	}

	public function sendMessage()
	{

		// VALIDAMOS SESION DEL USUARIO SI NO REDIRECCIONAMOS AL LOGIN
		if( check_islogin() ) {

		$send = $this->cmessages_model->SendMessage();
		if($send){
			echo "<span class=\"content-msg\">¡Contacto realizado!</span>";
		}			
		}else{
		 redirect(base_url());
	}
	}

	public function getTheMessage()
	{
		// VALIDAMOS SESION DEL USUARIO SI NO REDIRECCIONAMOS AL LOGIN
		$check = $this->cmessages_model->getTheMessage();
		if($check){
			echo "¡Contacto realizado!"; die();
		}
	}

	public function checkMessage($token)
	{
		$check = $this->cmessages_model->CheckMessage($token);

		if($check){

			$data["datos"] = $check;
			$data["sessionuser"] = $this->usersession;
			$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
			$data["user"] = $this->caccount_model->getUsername();
			$data["header"] = $this->load->view('templates/header_detail', $data, true);
			$data["footer"] = $this->load->view('templates/footer', $data, true);

			switch ($check) {
				case 'NC':
					$data["error"] = "Cliente No Vigente...";
					$this->load->view('index/error', $data);
					break;

				case 'TU':
					$data["error"] = "Token usado o No existe...";
					$this->load->view('index/error', $data);
					break;

				case 'NT':
					$data["error"] = "Token No existe...";
					$this->load->view('index/error', $data);
					break;

				case 'NP':
					$data["error"] = "El curso no está publicado en Cursovia...";
					$this->load->view('index/error', $data);
					break;
				
				default:
					$this->load->view('index/lectura', $data);
					break;
			}

			// echo $check; die();
		}else{
			$this->load->view('index/error');
		}			
	}


	public function readMessage($token)
	{
		$check = $this->cmessages_model->GetAllMessages($token);

		if($check){

			$data["mensajes"] = $check;
			$data["sessionuser"] = $this->usersession;
			$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
			$data["user"] = $this->caccount_model->getUsername();
			$data["header"] = $this->load->view('templates/header_detail', $data, true);
			$data["footer"] = $this->load->view('templates/footer', $data, true);

			switch ($check) {
				case 'NC':
					$data["error"] = "Cliente No Vigente...";
					$this->load->view('index/error', $data);
					break;

				case 'TU':
					$data["error"] = "Token usado o No existe...";
					$this->load->view('index/error', $data);
					break;

				case 'NT':
					$data["error"] = "Token No existe...";
					$this->load->view('index/error', $data);
					break;
				
				default:
					$this->load->view('msgs/detail', $data);
					break;
			}


			// echo $check; die();
		}else{
			$this->load->view('index/error');
		}			
	}


		public function checkMessageUser()
	{
			$data["sessionuser"] = $this->usersession;
			$data["mensajes"] = $this->cmessages_model->GetAllMessages();
			$data["qty"] = $this->cmessages_model->getQtyMsg();
			$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
			$data["user"] = $this->caccount_model->getUsername();
			$data["header"] = $this->load->view('templates/header_detail', $data, true);
			$data["footer"] = $this->load->view('templates/footer', $data, true);
			$this->load->view('msgs/inbox', $data);

	}
}
