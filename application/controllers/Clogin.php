<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clogin extends CI_Controller
{

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

	function __construct()
	{
		parent::__construct();
		$this->usersession = $this->session->userdata('user_data');
		$this->load->model('ccourses_model');
		$this->load->model('cfavorites_model');
		$this->load->model('clogin_model');
		$this->load->model('cutils_model');
	}

	public function index($slug = '')
	{

		if (!check_islogin()) {

			$data["sessionuser"] = $this->usersession;
			$data["courses"] = array();
			$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
			$data["header"] = $this->load->view('templates/header_detail', $data, true);
			$data["footer"] = $this->load->view('templates/footer', $data, true);
			if ($slug == "cursos-favoritos") {
				$data["backto"] = base_url("/cursos-favoritos");
			} else {
				$data["backto"] = "";
			}
			$countries_a = array();
			$countries = $this->cutils_model->getAllCountries();
			$countries_a[""] = "Seleccione...";
			if ($countries) {
				foreach ($countries as $key => $value) {
					$countries_a[$value->id] = $value->name;
				}
			}

			$data["countries"] = $countries_a;


			$this->load->view('login/login', $data);
		} else {
			redirect('/');
		}
	}

	public function setAccount()
	{

		$_POST['g-recaptcha-response'] = true;
		if ($_POST['g-recaptcha-response'] == '') {
			echo "Captcha invalido";
		} else {
			$obj = new stdClass();
			$obj->secret = "6LeQjJsaAAAAAG1WTQxmokDSPMIc0HDEN31bD_uz";
			$obj->response = $_POST['g-recaptcha-response'];
			$obj->remoteip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify';

			$options = array(
				'http' => array(
					'header' => "Content-type: application/x-www-form-urlencoded\r\n",
					'method' => 'POST',
					'content' => http_build_query($obj)
				)
			);
			$context = stream_context_create($options);
			$result = file_get_contents($url, false, $context);

			$validar = json_decode($result);

			/* FIN DE CAPTCHA */

			$account = $this->clogin_model->setAccount();
			if ($account) {
				$this->session->set_flashdata('msg', '<span class="msg"><strong>Te has registrado correctamente</strong>... hemos enviado un email para que actives tu cuenta!</span>');
			} else {
				$this->session->set_flashdata('msg', '<span class="msg"><strong>¡No pudimos registrar tus datos...</strong> reinténtalo más tarde!</span>');
			}
			redirect('/ingresar');
		}
	}


	public function checkToken($token = '')
	{

		$check = $this->clogin_model->checkToken($token);
		// $check = true;
		if ($check) {
			$this->session->set_flashdata('msg', '<span class="msg"><strong>¡Ya validamos tu cuenta, ahora ya puedes ingresar!</span>');
		} else {
			$this->session->set_flashdata('msg', '<span class="msg"><strong>No hemos podido valida tu cuenta, revisa que la dirección de validación no esté expirada o ya usada.</span>');
		}
		redirect('/ingresar');
	}

	public function checkLogin()
	{
		$account = $this->clogin_model->checkLogin();
		if ($account) {
			$posts = $this->input->post();
			$this->session->set_flashdata('msg', '');
			if ($posts["backto"]) {
				redirect($posts["backto"]);
			} else {
				redirect(base_url());
			}
		} else {
			$this->session->set_flashdata('msg', '<span class="msg">Ingreso no válido. Email de usuario, clave errónea y/o usuario no activo aún.</span>');
			redirect(base_url('/ingresar'));
		}
	}

	function getPassword()
	{

		$posts = $this->input->post();

		if ($posts) {

			$getEmail = $this->clogin_model->checkLogin(true);
			if ($getEmail) {
				$SendEmail = $this->clogin_model->SendNewPassword($getEmail);
				if ($SendEmail) {
					$this->session->set_flashdata('msg', '<span class="msg">¡Hemos enviado una nueva clave a tu casilla de e-mail!</span>');
					redirect('/clogin/getPassword');
				} else {
					$this->session->set_flashdata('msg', '<span class="msg">¡Algo ocurrió! No pudimos enviar el e-mail.</span>');
					redirect('/clogin/getPassword');
				}
			} else {
				$this->session->set_flashdata('msg', '<span class="msg">¡Algo ocurrió! No encontramos el e-mail ingresado.</span>');
				redirect('/clogin/getPassword');
			}

			die();
		}
		$data = array();
		$data["header"] = $this->load->view('templates/header_detail', $data, true);
		$data["footer"] = $this->load->view('templates/footer', $data, true);
		$this->load->view('/login/password_recovery', $data);
	}

	public function validaUser()
	{
		$validate = $this->clogin_model->validaUser();
		echo $validate;
		die();
		// 
	}

	public function logout()
	{
		$this->session->unset_userdata('user_data');
		redirect('/');
	}
}
