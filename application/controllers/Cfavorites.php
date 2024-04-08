<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cfavorites extends CI_Controller {

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
		$this->load->model('cfavorites_model');
	}

	public function index()
	{

		// $data["courses"] = $this->ccourses_model->getAllCourses();
		// $data["favorites"] = $this->cfavorites_model->getFavorites();
		// $this->load->view('index/index_cursovia', $data);
	}

	public function fav($idcurso='')
	{

		// VALIDAMOS SESION DEL USUARIO SI NO REDIRECCIONAMOS AL LOGIN
		if( !check_islogin() ) {
			echo "";
			die;
		}else{
		$fav 	=	$this->cfavorites_model->setFavorites($idcurso);
		if($fav){
			echo $fav; die();
		}
	}
	}
}
