<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ccourses extends CI_Controller
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
		$this->load->model('cmessages_model');
		$session_id = $this->session->userdata('session_id');
		if (!$session_id) {
			$session_id = uniqid() . date('YmdHis');
			$this->session->set_userdata('session_id', $session_id);
			$this->ccourses_model->setSessionCourses();
		}
	}

	public function index()
	{
		$limit = 5;
		$data["sessionuser"] = $this->usersession;
		$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
		$data["header"] = $this->load->view('templates/header', $data, true);
		$data["footer"] = $this->load->view('templates/footer', $data, true);
		$data["paid_banner_courses"] = $this->ccourses_model->getAllPaidBanner_Courses();
		$data["courses"] = $this->ccourses_model->getAllCourses('', 1, $limit, 0, '');
		$data["total_courses"] = $this->ccourses_model->countAllCourses();
		$data['unreadMsgs'] = $this->cmessages_model->GetAllUnreadMessages();


		$gets = $this->input->get();
		if ($gets) $this->session->set_flashdata('txt', $gets["buscar"]);
		else $this->session->set_flashdata('txt', '');



		if (count($data["courses"]) == 1) {

			$slug = $data["courses"][0]["slug"];
			redirect('/mostrar/' . $slug, 'refresh');
		} else $this->load->view('index/index_cursovia', $data);
	}



	function profile($client_slug = '')
	{
		$limit = 50;
		$data["courses"] = $this->ccourses_model->getClientCourses($client_slug, $limit, 0);
		$data["client"] = $this->ccourses_model->getClient($client_slug);
		$data["sessionuser"] = $this->usersession;
		if ($this->usersession) {
			$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
		}
		$data["header"] = $this->load->view('templates/header_detail', $data, true);
		$data["footer"] = $this->load->view('templates/footer', $data, true);
		$data["paid_banner_courses"] = $this->ccourses_model->getAllPaidBanner_Courses();
		$data["total_courses"] = $this->ccourses_model->countClientCourses($client_slug);

		$gets = $this->input->get();
		if ($gets) $this->session->set_flashdata('txt', $gets["buscar"]);
		else $this->session->set_flashdata('txt', '');


		$this->load->view('index/client_profile', $data);
	}

	public function loadMoreCourses()
	{
		$limit = 50;
		$client_slug = $this->input->post('client_slug');
		$offset = $this->input->post('offset');
		$search_txt = $this->input->post('search_txt');

		if ($client_slug) {
			$data["courses"] = $this->ccourses_model->getClientCourses($client_slug, $limit, $offset, $search_txt);
			$data["client"] = $this->ccourses_model->getClient($client_slug);
		} else {
			$data["courses"] = $this->ccourses_model->getAllCourses('', 1, $limit, $offset, $search_txt);
			$data["client"] = '';
		}

		echo json_encode($data);
	}

	function getCourse($slug = '')
	{

		$data["sessionuser"] = $this->usersession;
		$data["courses"] = $this->ccourses_model->getAllCourses($slug, 2, 1, 0);
		$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
		$data["header"] = $this->load->view('templates/header_detail', $data, true);
		$data["footer"] = $this->load->view('templates/footer', $data, true);
		$data["isLogged"] = check_islogin();
		$c = count($data["courses"]);

		if ($c != 0) {
			$this->load->view('index/detalle', $data);
		} else {
			$data["error"] = "Curso No existe...";
			$this->load->view('index/error', $data);
		}
	}

	function getURLExternal($token = '')
	{
		$data["sessionuser"] = $this->usersession;
		$URLExternal = $this->ccourses_model->getURLExternal($token);
		$data["isLogged"] = check_islogin();
		if ($URLExternal) { 
			// echo $URLExternal; die();
			## DEBE CONSIDERAR UN REGISTRO DE CADA CLIC EN URL EXTERNA

			redirect($URLExternal);
		}
	}

	function getAllFavorites()
	{
		if (check_islogin()) {

			$data["sessionuser"] = $this->usersession;
			$data["courses"] = $this->ccourses_model->getAllFavCourses();
			$data["cant_favorites"] = $this->cfavorites_model->countFavorites();
			$data["header"] = $this->load->view('templates/header_detail', $data, true);
			$data["footer"] = $this->load->view('templates/footer', $data, true);
			$data["isLogged"] = check_islogin();

			$this->load->view('fav/index', $data);
		} else {
			redirect('/ingresar/cursos-favoritos');
		}
	}

	function watchVideo($client = '', $uri = '')
	{

		return URL_KAMPUS . '/public_htm/public/cliente/uploads/' . $client . '/' . $uri;
	}
}
