<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ccourses_model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->usersession = $this->session->userdata('user_data');
		$this->load->model('cfavorites_model');
		$this->load->model('cutils_model');
		$this->load->model('clogin_model');
	}

	/* function getAllCourses($slug='',$ve=1) {

		$posts = $this->input->post();

		$output = array();
		$i=0;
		$txt = "";

		if(!$slug){
			if($posts){
			$txt = soloCaracteresPermitidos( $posts["conceptosearch"] );
			}
		}

	 	$this->db->select('c.id as course_id, c.image_int, c.name, c.slug, c.hour, c.code, c.description, c.resume, k.*, cl.name as client_name, cl.slug as client_slug, cl.logo as client_logo, cl.id as client_id');
	 	$this->db->from('courses as c');
	 	$this->db->join('clients as cl', 'c.client_id = cl.id');
	 	$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
	 	$this->db->join('cursovia k', 'k.course_id = c.id');
	 	$this->db->where('oc.expire >= CURDATE()');
	 	if($slug){
	 		$this->db->where('c.slug',$slug);
	 	}

	 	if($txt){

	 		$this->db->where('( c.name LIKE "%'.$txt.'%" OR
								c.description LIKE "%'.$txt.'%"
								OR cl.name LIKE "%'.$txt.'%" )');

	 		// $this->db->like('c.name',$txt);
	 		// $this->db->or_like('c.description', $txt); 
	 	}else{
	 		$this->db->limit(50);
	 	}

	 	$this->db->where('c.status',1);
	 	$this->db->where('cl.status',1);
	 	$this->db->where('c.publish_kimun',1);
	 	$this->db->where('k.cursovia_ispaid !=',1);
	 	$this->db->order_by('rand()');
	 	$courses = $this->db->get();

	 	//echo $this->db->last_query(); die();

	 	// print_r($courses->result()); die();

	 	if( $courses->num_rows() ) {
			foreach ($courses->result() as $key => $course) {

				$showContent = $course->cursovia_structure;
				$showSence = $course->cursovia_sence_code;
				$cursovia_elearning = $course->cursovia_elearning;
				$cursovia_inRoom = $course->cursovia_inRoom;
				$muestraModalidad = $cursovia_elearning + $cursovia_inRoom;
				$price = $course->cursovia_price ? $course->cursovia_price : 'Cotizar';

#				echo $showContent; die();


				if($muestraModalidad == 2){
					$modalidad = "E-learning | Presencial";
				}elseif($muestraModalidad == 1){
					if($cursovia_elearning) $modalidad 	= 'E-learning';
					if($cursovia_inRoom) $modalidad 	= 'Presencial';
				}else{
					$modalidad = 'Consultar';
				}

				// if($course->cursovia_price){}

				$output[$i]['id'] = $course->course_id;
				$output[$i]['image_int'] = $course->image_int;
				$output[$i]['name'] = $course->name;
				$output[$i]['resume'] = trim($course->resume);
				$output[$i]['target'] = $course->cursovia_target;
				$output[$i]['skill'] = $course->cursovia_learn_objective;
				$output[$i]['slug'] = $course->slug;
				$output[$i]['hour'] = $course->hour;
				$output[$i]['code'] = $course->code;
				$output[$i]['price'] = $price;
				$output[$i]['description'] = trim($course->cursovia_description);
				$output[$i]['client_name'] = $course->client_name;
				$output[$i]['client_slug'] = $course->client_slug;
				$output[$i]['client_logo'] = $course->client_logo;
				$output[$i]['client_id'] = $course->client_id;
				$output[$i]['promotional_video'] = $course->promotional_video;
				$output[$i]['favorite'] = $this->cfavorites_model->getFavorites($course->course_id);
				$output[$i]['total_favorites'] = $this->cfavorites_model->getAllFavorites($course->course_id);
				$output[$i]['showSence'] = $showSence;
				$output[$i]['modalidad'] = $modalidad;

				if($slug and $showContent==1){
					$output[$i]['content'] = $this->getContent($course->course_id);
				}

				$this->cutils_model->setCourseInRsults($course->course_id, $txt, $ve);

			$i++;

			}			
		}

	 	// echo $this->db->last_query(); die();

		return $output;

	 } */


	 function setSessionCourses() {

	 	$this->db->select('c.id as course_id');
	 	$this->db->from('courses as c');
	 	$this->db->join('clients as cl', 'c.client_id = cl.id');
	 	$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
	 	$this->db->join('cursovia k', 'k.course_id = c.id');
	 	$this->db->where('oc.expire >= CURDATE()');
	 	$this->db->where('c.status',1);
	 	$this->db->where('cl.status',1);
	 	$this->db->where('c.publish_kimun',1);
	 	$this->db->where('k.cursovia_ispaid !=',1);
	 	$this->db->order_by('rand()');
	 	$courses = $this->db->get();

		$course_ids_text = '';
		
	 	if( $courses->num_rows() ) {
			foreach ($courses->result() as $key => $course) {

				$course_ids_text .= ($key > 0 ? ',' : '').$course->course_id;

			}			
		}

		$sessionuser = $this->usersession;
		$user_id = ($sessionuser) ? $sessionuser["id"] : null;
		$session_id = $this->session->userdata('session_id');
		$dbc = $this->load->database('cursovia', TRUE);

		$fecha = date('Y-m-d H:i:s');
		$data = array(
			'session_id'=> $session_id,
			'courses_id' => $course_ids_text,
			'user_id' => $user_id,
			'result_date' => $fecha,
		);

		

		//print_r($data); die();
		
		$dbc->insert('cursovia_results', $data);

		//echo dbc->last_query(); 
	 }

	 function getAllCourses($slug='',$ve=1, $limit=0, $offset=0) {

		
		$session_id = $this->session->userdata('session_id');

		$dbc = $this->load->database('cursovia', TRUE);
		$dbc->select('id, courses_id');
		$dbc->from('cursovia_results');
		$dbc->where('session_id',$session_id);
		$query = $dbc->get();

		if (!$query->num_rows()){
			$this->setSessionCourses();
		}

		$result = $query->row();
		$result_courses_id = explode(",", $result->courses_id);
		$result_courses_id = array_map('intval', $result_courses_id);


		$gets = $this->input->get();
		$output = array();
		$i=0;
		$txt = "";

		if(!$slug){
			if($gets){
			$txt = soloCaracteresPermitidos( $gets["buscar"] );
			}
		}

	 	$this->db->select('c.id as course_id, c.image_int, c.name, c.slug, c.hour, c.code, c.description, c.resume, k.*, cl.name as client_name, cl.slug as client_slug, cl.logo as client_logo, cl.id as client_id');
	 	$this->db->from('courses as c');
	 	$this->db->join('clients as cl', 'c.client_id = cl.id');
	 	$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
	 	$this->db->join('cursovia k', 'k.course_id = c.id');
	 	$this->db->where('oc.expire >= CURDATE()');
	 	if($slug){
	 		$this->db->where('c.slug',$slug);
	 	}

	 	if($txt){

	 		$this->db->where('( c.name LIKE "%'.$txt.'%" OR
								c.description LIKE "%'.$txt.'%"
								OR cl.name LIKE "%'.$txt.'%" )');

	 		// $this->db->like('c.name',$txt);
	 		// $this->db->or_like('c.description', $txt); 
	 	}
	 	$this->db->where('c.status',1);
	 	$this->db->where('cl.status',1);
	 	$this->db->where('c.publish_kimun',1);
	 	$this->db->where('k.cursovia_ispaid !=',1);
		$this->db->order_by("FIELD(c.id, " . implode(",", $result_courses_id) . ")");
		$this->db->limit($limit, $offset);
	 	$courses = $this->db->get();

	 	//echo $this->db->last_query(); die();

	 	// print_r($courses->result()); die();

	 	if( $courses->num_rows() ) {
			foreach ($courses->result() as $key => $course) {

				$showContent = $course->cursovia_structure;
				$showSence = $course->cursovia_sence_code;
				$cursovia_elearning = $course->cursovia_elearning;
				$cursovia_inRoom = $course->cursovia_inRoom;
				$muestraModalidad = $cursovia_elearning + $cursovia_inRoom;
				$price = $course->cursovia_price ? $course->cursovia_price : 'Cotizar';

#				echo $showContent; die();


				if($muestraModalidad == 2){
					$modalidad = "E-learning | Presencial";
				}elseif($muestraModalidad == 1){
					if($cursovia_elearning) $modalidad 	= 'E-learning';
					if($cursovia_inRoom) $modalidad 	= 'Presencial';
				}else{
					$modalidad = 'Consultar';
				}

				// if($course->cursovia_price){}

				$output[$i]['id'] = $course->course_id;
				$output[$i]['image_int'] = $course->image_int;
				$output[$i]['name'] = $course->name;
				$output[$i]['resume'] = trim($course->resume);
				$output[$i]['target'] = $course->cursovia_target;
				$output[$i]['skill'] = $course->cursovia_learn_objective;
				$output[$i]['slug'] = $course->slug;
				$output[$i]['hour'] = $course->hour;
				$output[$i]['code'] = $course->code;
				$output[$i]['price'] = $price;
				$output[$i]['description'] = trim($course->cursovia_description);
				$output[$i]['client_name'] = $course->client_name;
				$output[$i]['client_slug'] = $course->client_slug;
				$output[$i]['client_logo'] = $course->client_logo;
				$output[$i]['client_id'] = $course->client_id;
				$output[$i]['promotional_video'] = $course->promotional_video;
				$output[$i]['favorite'] = $this->cfavorites_model->getFavorites($course->course_id);
				$output[$i]['total_favorites'] = $this->cfavorites_model->getAllFavorites($course->course_id);
				$output[$i]['showSence'] = $showSence;
				$output[$i]['modalidad'] = $modalidad;

				if($slug and $showContent==1){
					$output[$i]['content'] = $this->getContent($course->course_id);
				}

				$this->cutils_model->setCourseInRsults($course->course_id, $txt, $ve);

			$i++;

			}			
		}

	 	// echo $this->db->last_query(); die();

		return $output;

	 }

	 public function countAllCourses() {

		$this->db->select('c.id');
		$this->db->from('courses as c');
		$this->db->join('clients as cl', 'c.client_id = cl.id');
		$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
		$this->db->join('cursovia k', 'k.course_id = c.id');
		$this->db->where('oc.expire >= CURDATE()');
		$gets = $this->input->get();
		$txt = '';
		if($gets){
			$txt = soloCaracteresPermitidos( $gets["buscar"] );		
		}
		if($txt){
			$this->db->where('( c.name LIKE "%'.$txt.'%" OR
								   c.description LIKE "%'.$txt.'%"
								   OR cl.name LIKE "%'.$txt.'%" )');
		}
		$this->db->where('c.status', 1);
		$this->db->where('cl.status', 1);
		$this->db->where('c.publish_kimun', 1);
		$this->db->where('k.cursovia_ispaid !=', 1);
		$query = $this->db->get();
		$count = $query->num_rows();

		return $count;
    }

	 function getClient($slug = ''){
		$this->db->select('id, name, slug, alias, description, phone, email, color_first, color_second,	background,	logo, profile_img, profile_cover, profile_description, favicon,	mobileicon,	email_support, address');
	 	$this->db->from('clients');
		$this->db->where('slug', $slug);
		$client = $this->db->get()->row_array();
		return $client;
	 }
	
	 function getClientCourses($slug='', $limit=0, $offset=0) {

		$session_id = $this->session->userdata('session_id');

		$dbc = $this->load->database('cursovia', TRUE);
		$dbc->select('id, courses_id');
		$dbc->from('cursovia_results');
		$dbc->where('session_id',$session_id);
		$query = $dbc->get();

		if (!$query->num_rows()){
			$this->setSessionCourses();
		}

		$result = $query->row();
		$result_courses_id = explode(",", $result->courses_id);
		$result_courses_id = array_map('intval', $result_courses_id);

		$gets = $this->input->get();
		$txt = '';
		
		$output = array();
		$i=0;
	    if($gets){
			$txt = soloCaracteresPermitidos( $gets["buscar"] );
			}
		

	 	$this->db->select('c.id as course_id, c.image_int, c.name, c.slug, c.hour, c.code, c.description, c.resume, k.*, cl.name as client_name, cl.slug as client_slug, cl.logo as client_logo, cl.id as client_id');
	 	$this->db->from('courses as c');
	 	$this->db->join('clients as cl', 'c.client_id = cl.id');
	 	$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
	 	$this->db->join('cursovia k', 'k.course_id = c.id');
	 	$this->db->where('oc.expire >= CURDATE()');
		
	 	if($txt){

	 		$this->db->where('( c.name LIKE "%'.$txt.'%" OR
								c.description LIKE "%'.$txt.'%"
								OR cl.name LIKE "%'.$txt.'%" )');
	 	}

	 	$this->db->where('c.status',1);
	 	$this->db->where('cl.status',1);
		$this->db->where('cl.slug',$slug);
	 	$this->db->where('c.publish_kimun',1);
	 	$this->db->where('k.cursovia_ispaid !=',1);
		$this->db->order_by("FIELD(c.id, " . implode(",", $result_courses_id) . ")");
		$this->db->limit($limit, $offset);
	 	$courses = $this->db->get();

	 	if( $courses->num_rows() ) {
			foreach ($courses->result() as $key => $course) {

				$showContent = $course->cursovia_structure;
				$showSence = $course->cursovia_sence_code;
				$cursovia_elearning = $course->cursovia_elearning;
				$cursovia_inRoom = $course->cursovia_inRoom;
				$muestraModalidad = $cursovia_elearning + $cursovia_inRoom;
				$price = $course->cursovia_price ? $course->cursovia_price : 'Cotizar';

#				echo $showContent; die();


				if($muestraModalidad == 2){
					$modalidad = "E-learning | Presencial";
				}elseif($muestraModalidad == 1){
					if($cursovia_elearning) $modalidad 	= 'E-learning';
					if($cursovia_inRoom) $modalidad 	= 'Presencial';
				}else{
					$modalidad = 'Consultar';
				}

				// if($course->cursovia_price){}

				$output[$i]['id'] = $course->course_id;
				$output[$i]['image_int'] = $course->image_int;
				$output[$i]['name'] = $course->name;
				$output[$i]['resume'] = trim($course->resume);
				$output[$i]['target'] = $course->cursovia_target;
				$output[$i]['skill'] = $course->cursovia_learn_objective;
				$output[$i]['slug'] = $course->slug;
				$output[$i]['hour'] = $course->hour;
				$output[$i]['code'] = $course->code;
				$output[$i]['price'] = $price;
				$output[$i]['description'] = trim($course->cursovia_description);
				$output[$i]['client_name'] = $course->client_name;
				$output[$i]['client_slug'] = $course->client_slug;
				$output[$i]['client_logo'] = $course->client_logo;
				$output[$i]['client_id'] = $course->client_id;
				$output[$i]['promotional_video'] = $course->promotional_video;
				$output[$i]['favorite'] = $this->cfavorites_model->getFavorites($course->course_id);
				$output[$i]['total_favorites'] = $this->cfavorites_model->getAllFavorites($course->course_id);
				$output[$i]['showSence'] = $showSence;
				$output[$i]['modalidad'] = $modalidad;

				if($slug and $showContent==1){
					$output[$i]['content'] = $this->getContent($course->course_id);
				}

			$i++;

			}			
		}

	 	// echo $this->db->last_query(); die();

		return $output;

	 }

	 function countClientCourses($slug='') {
		$gets = $this->input->get();
		$txt = '';
		if($gets){
			$txt = soloCaracteresPermitidos( $gets["buscar"] );
		}

	 	$this->db->select('c.id');
	 	$this->db->from('courses as c');
	 	$this->db->join('clients as cl', 'c.client_id = cl.id');
	 	$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
	 	$this->db->join('cursovia k', 'k.course_id = c.id');
	 	$this->db->where('oc.expire >= CURDATE()');
		
		 if($txt){

			$this->db->where('( c.name LIKE "%'.$txt.'%" OR
							   c.description LIKE "%'.$txt.'%"
							   OR cl.name LIKE "%'.$txt.'%" )');
		}

	 	$this->db->where('c.status',1);
	 	$this->db->where('cl.status',1);
		$this->db->where('cl.slug',$slug);
	 	$this->db->where('c.publish_kimun',1);
	 	$this->db->where('k.cursovia_ispaid !=',1);
	 	$courses = $this->db->get();
		$count = $courses->num_rows();
		return $count;

	 }

	 function getAllPaidBanner_Courses() {

		$i=0;
		$output = array();

	 	$this->db->select('c.id as course_id, c.name, c.slug, c.hour, c.code, c.description, c.resume, k.*, cl.name as client_name, cl.logo as client_logo, cl.id as client_id, bl.*');
		$this->db->from('courses as c');
		$this->db->join('clients as cl', 'c.client_id = cl.id');
		$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
		$this->db->join('cursovia k', 'k.course_id = c.id');
		$this->db->join('buttonlabels as bl', 'bl.id = k.cursovia_button', 'left');
		$this->db->where('oc.expire >= CURDATE()');
		$this->db->where('c.status', 1);
		$this->db->where('cl.status', 1);
		$this->db->where('c.publish_kimun', 1);
		$this->db->where('k.cursovia_ispaid', 1);
		$this->db->where('(cursovia_forever IS NULL AND (cursovia_from <= NOW() AND cursovia_tothe >= NOW()) OR cursovia_forever = 1)');
		$this->db->where('k.cursovia_isbanner', 1);
		$this->db->where('k.cursovia_status', 1);
		$this->db->order_by('k.cursovia_order', 'asc'); // 'asc' debe estar entre comillas simples o en mayúsculas
		$paid_banner_courses = $this->db->get();

		//echo $this->db->last_query(); die();

		return $paid_banner_courses->result();

	 	//echo $this->db->last_query(); die();
	 	// SELECT * FROM `kampus_cursovia` WHERE `cursovia_from` <= NOW() AND cursovia_tothe >= NOW();

	 	// select * FROM kampus_cursovia WHERE (`cursovia_forever` IS NULL AND (`cursovia_from` <= NOW() AND cursovia_tothe >= NOW())) OR `cursovia_forever` = 1;
	 	// $this->db->where('(is_active = true AND (campo1 = "xx" AND campo2 = "yy")) OR is_active = false');


	 }


	 function getURLExternal($token='') {

	 	$this->db->select('cursovia_url, cursovia_utm_source, cursovia_utm_medium, cursovia_utm_campaign, cursovia_utm_term, cursovia_utm_content');
	 	$this->db->from('cursovia');
	 	$this->db->where('token', $token);
	 	$this->db->where('cursovia_status', 1);
	 	$active_courses = $this->db->get();
		echo $this->db->last_query();

	 	if( $active_courses->num_rows() ) {
			foreach ($active_courses->result() as $key => $url) {

				$cursovia_url			=	$url->cursovia_url;
				$cursovia_utm_source	=	$url->cursovia_utm_source;
				$cursovia_utm_medium	=	$url->cursovia_utm_medium;
				$cursovia_utm_campaign	=	$url->cursovia_utm_campaign;
				$cursovia_utm_term		=	$url->cursovia_utm_term;
				$cursovia_utm_content	=	$url->cursovia_utm_content;

			}

			$utm_string = '?utm_source='.$cursovia_utm_source;
			$utm_string .= '&utm_medium='.$cursovia_utm_medium;
			$utm_string .= '&utm_campaign='.$cursovia_utm_campaign;
			$utm_string .= '&utm_term='.$cursovia_utm_term;
			$utm_string .= '&utm_content='.$cursovia_utm_content;

		}

			return $url_return = trim($cursovia_url).$utm_string;
 	}



	 function getCourseName($id){

	 	$this->db->select('name');
	 	$this->db->from('courses');
	 	$this->db->where('id',$id);
	 	// $this->db->where('publish_kimun',1);
	 	$name = $this->db->get();

			if($name->num_rows()){
					$ncourse = $name->result();
		         	foreach ($ncourse as $key => $value) {
		         		$course_name = $value->name;
		         	}
		         	return  $course_name;
				}else{
					 echo false;
				}
	 }

	 function getAllFavCourses(){
	 	if(check_islogin() ) {

		$dbc = $this->load->database('cursovia', TRUE);
		$output = array();
		$i = 0;


		$sessionuser = $this->usersession;
		$id_user  = $sessionuser["id"];

			$dbc->select('id,course_id');
			$dbc->from('favorites');
			$dbc->where('user_id',$id_user);
			$favorites = $dbc->get();

			if($favorites->num_rows()){
					$r = $favorites->result();

		         	foreach ($r as $key => $value) {
		         		$this->db->select('k.*, c.id,c.image_int, c.name, c.slug, c.hour, c.code, cl.name as client_name, cl.logo as client_logo, cl.id as client_id');
					 	$this->db->from('courses as c');
	 					$this->db->join('cursovia k', 'k.course_id = c.id');
					 	$this->db->join('lastClientOC as oc', 'oc.client_id = c.client_id');
	 					$this->db->join('clients as cl', 'c.client_id = cl.id');
					 	$this->db->where('oc.expire >= CURDATE()');
					 	$this->db->where('c.id',$value->course_id);
					 	$this->db->where('c.status',1);
					 	$this->db->where('cl.status',1);
					 	$this->db->where('c.publish_kimun',1);

						$exist = $this->db->get();
						if($exist->num_rows()){

		         		
							foreach ($exist->result() as $key => $course) {

							$output[$i]['id'] = $course->id;
							$output[$i]['image_int'] = $course->image_int;
							$output[$i]['name'] = $course->name;
							// $output[$i]['resume'] = $course->resume;
							$output[$i]['slug'] = $course->slug;
							$output[$i]['hour'] = $course->hour;
							$output[$i]['code'] = $course->code;
							$output[$i]['description'] = trim($course->cursovia_description);
							$output[$i]['client_name'] = $course->client_name;
							$output[$i]['client_logo'] = $course->client_logo;
							$output[$i]['client_id'] = $course->client_id;
							$output[$i]['favorite'] = $this->cfavorites_model->getFavorites($course->id);
							$output[$i]['total_favorites'] = $this->cfavorites_model->getAllFavorites($course->id);
							$i++;
						}	
						}
		         	}		        
		}
		return $output;
		}
	 }

	 function getContent($idcourse){

			$output = array();


			// TOPICOS Y MODULOS
			$this->db->select('*');
			$this->db->from('topics');
			$this->db->where('course_id', $idcourse);
			$this->db->where('status', 1);
			$this->db->order_by('order', 'asc');
			$topics = $this->db->get();

			// echo $topics->num_rows(); die();

#			print_r($this->db->last_query()); 
			
			if( $topics->num_rows() ) {
				$i=0;
				foreach ($topics->result() as $key => $topic) {
					// $output['topics'][$i]['id'] = $topic->id;
					$output['topics'][$i]['title'] = $topic->name;

					$this->db->select('m.*, mt.icon');
					$this->db->from('modules as m');
					$this->db->join('modules_type as mt', 'm.modules_type_id = mt.id', 'inner');
					$this->db->where('m.topics_id', $topic->id);
					$this->db->where('modules_type_id != 5');
					$this->db->where('m.status', 1);
					$this->db->order_by('m.order', 'asc');
					$modules = $this->db->get();

#					print_r($this->db->last_query()); 

					if( $modules->num_rows() ){
						$a=0;
						foreach ($modules->result() as $key => $module) {

							// DISTINTO A TIPO EJERCICIO
							if( $module->modules_type_id != 5 ) {
								// $output['topics'][$i]['modules'][$a]['id'] = $module->id;
								$output['topics'][$i]['modules'][$a]['title'] = $module->name;
								// $output['topics'][$i]['modules'][$a]['module_category'] = $module->module_category;
							}

							$output['topics'][$i]['modules'][$a]['icon'] = $module->icon;
							// $output['topics'][$i]['modules'][$a]['type'] = $module->modules_type_id;

							
							$a++;
						}
					}
					$i++;
				}
			}

		return $output;
	 }
}