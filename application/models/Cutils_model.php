<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cutils_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	// function getAllStatus() {
	// 	$this->db->from('status');
	// 	$this->db->where('hidden',0);
	// 	$status = $this->db->get();

	// 	return $status->result();
	// }

	// function getAllCapline() {
	// 	$this->db->from('capline');
	// 	$this->db->where('status',1);
	// 	$capline = $this->db->get();

	// 	return $capline->result();
	// }

	// function getAllLogins() {
	// 	$this->db->from('logins');
	// 	$this->db->where('owner',0);
	// 	$this->db->where('status',1);
	// 	$this->db->order_by('name','asc');
	// 	$login = $this->db->get();

	// 	return $login->result();
	// }

	// function validExistsSlug($table, $slug){
	// 	$slug = soloCaracteresPermitidos( $slug );

	// 	$this->db->from($table);
	// 	$this->db->where('slug', $slug);
	// 	$query = $this->db->get();
		
	// 	if( $query->num_rows() ){
	// 		$slug = $slug."-".base_convert(date('YmdHis'), 4, 36);
	// 	}

	// 	return $slug;
	// }

	function getAllCountries() {
		$dbc = $this->load->database('cursovia', TRUE);
		$dbc->from('countries');
		$dbc->where('status', 1);
		$dbc->order_by('name', 'asc');
		$countries = $dbc->get();

		return $countries->result();
	}

	function checkClient($id=0,$action='vigencia'){

		// $this->db->select('c.id,c.image_int, c.name, c.slug, c.hour, c.code, c.description, c.resume, c.showContent, c.target, c.skill, cl.name as client_name, cl.logo as client_logo, cl.id as client_id');
	 	$this->db->select('cl.id, cl.name, cl.email');
	 	$this->db->from('clients as cl');
	 	$this->db->join('lastClientOC as oc', 'oc.client_id = cl.id');
	 	if($action=='vigencia'){
	 	$this->db->where('oc.expire >= CURDATE()');
	 	$this->db->where('cl.status', 1);
	 	$this->db->where('oc.status', 1);
	 	}
	 	
	 	$this->db->where('cl.id',$id);

	 	$datos = $this->db->get();

	 	switch ($action){
	 		case 'vigencia':
				return $this->db->affected_rows() ? true : false;	
	 		break;
	 		case 'datos':
				return $datos->result();
	 		break;
	 	}
	}


	function checkKimun($course_id='', $client_id=''){
	 	$this->db->from('courses as c');
	 	$this->db->where('c.id', $course_id);
	 	$this->db->where('c.client_id', $client_id);
	 	$this->db->where('cursovia', 1);
	 	$this->db->where('c.status', 1);
	 	$datos = $this->db->get();
	 	// echo $this->db->last_query(); 
	 	return $this->db->affected_rows() ? true : false;	
	}

	function setCourseInRsults($idCourse='', $txt_search='', $ve=1){

		$fecha = date('Y-m-d H:i:s');



		$data = array(
			'course_id' => (int)$idCourse,
			'date_result' => $fecha,
			'text_search' => $txt_search,
			'has_detail' => $ve
			// 'origin' => $_SERVER['HTTP_REFERER']
		);

		// print_r($data); die();
		
		$this->db->insert('cursovia_stats', $data);
	}


	

	// function getAllProfiles() {
	// 	$this->db->from('users_type');
	// 	$this->db->where('status', 1);
	// 	$this->db->where('id != ', 1); // no mostramos el superadmin
	// 	$this->db->order_by('name', 'asc');
	// 	$profiles = $this->db->get();

	// 	return $profiles->result();
	// }

	// function getPlanValid( $clientid=0 ) {
	// 	$clientid = (int) $clientid;
	// 	$fecha = date('Y-m-d');

	// 	$this->db->from( 'lastClientOC' );
	// 	$this->db->where('client_id', $clientid);
	// 	$this->db->where('payment_status', 'Completed');
	// 	// $this->db->where('expire >=', $fecha);
	// 	$this->db->where('status', 1);
	// 	$planvalid = $this->db->get();

	// 	return $planvalid->row();
	// }

	// function getAllEvaluationsType() {
	// 	$this->db->from('evaluations_type');
	// 	$this->db->where('status', 1);
	// 	$this->db->order_by('orden', 'asc');
	// 	$evaluationstype = $this->db->get();

	// 	return $evaluationstype->result();
	// }

	// function getTypeQuestion() {
	// 	$this->db->from('questions_type');
	// 	$this->db->where('status', 1);
	// 	$questiontypes = $this->db->get();

	// 	return $questiontypes->result();
	// }

	// function getSurveyTypeQuestion() {
	// 	$this->db->from('questions_type');
	// 	$this->db->where('status', 1);
	// 	$this->db->where('show_survey', 1);
	// 	$questiontypes = $this->db->get();

	// 	return $questiontypes->result();
	// }

	// function getLastOC() {
	// 	$userdata = $this->session->userdata('user_data');
	// 	$clientid = ( $userdata ? $userdata["client_id"] : 0 );

	// 	$this->db->from('lastClientOC');
	// 	$this->db->where('client_id', $clientid);
	// 	$oc = $this->db->get();

	// 	return $oc->row();
	// }

	// function getPLan( $planid=0 ) {
	// 	$features_a=array();
	// 	$planid = (int) $planid;

	// 	$this->db->from('plans');
	// 	$this->db->where('id', $planid);
	// 	$this->db->where('status', 1);
	// 	$plan = $this->db->get();

	// 	if( $plan->num_rows() ) {
	// 		$rsplan = $plan->row_array();
	// 		$features_a = $rsplan;

	// 		$this->db->select('f.*, s.name as service_name');
	// 		$this->db->from('features as f');
	// 		$this->db->join('services as s', 'f.service_id = s.id');
	// 		$this->db->where('f.plan_id', $rsplan["id"]);
	// 		$this->db->where('f.status', 1);
	// 		$features = $this->db->get();

	// 		if( $features->num_rows() ) {
	// 			foreach ($features->result() as $key => $feature) {
	// 				$features_a[$feature->service_name] = $feature->param;
	// 			}
	// 		}
	// 	}

	// 	return $features_a;
	// }

	// function getFeaturesPlan( $planid=0 ) {
	// 	$output=array();
	// 	$planid = (int) $planid;

	// 	$this->db->from('features');
	// 	$this->db->where('plan_id', $planid);
	// 	$this->db->where('status', 1);
	// 	$features = $this->db->get();

	// 	if( $features->num_rows() ) {
	// 		foreach ($features->result() as $key => $feature) {
	// 			$output[$feature->service_id] = $feature->param;
	// 		}
	// 	}

	// 	return $output;
	// }

	// function getMenu() {
	// 	$menu=array();
	// 	$userdata = $this->session->userdata('user_data');
	// 	$userid = (int) $userdata["user_id"];

	// 	$this->db->from('menu_admin');
	// 	$this->db->where('menu_admin_id', null);
	// 	$this->db->where('status', 1);
	// 	$menuparent = $this->db->get();

	// 	if( $menuparent->num_rows() ) {
	// 		foreach ($menuparent->result() as $key => $menup) {
	// 			$menu[$menup->id]["name"] = $menup->name;
	// 			$menu[$menup->id]["icon"] = $menup->icon;
	// 			$menu[$menup->id]["uri"] = $menup->uri;

	// 			// obtenemos las hijas
	// 			$this->db->from('menu_admin');
	// 			$this->db->where('menu_admin_id', $menup->id);
	// 			$this->db->where('status', 1);
	// 			$menuchild = $this->db->get();

	// 			if( $menuchild->num_rows() ) {
	// 				foreach ($menuchild->result() as $key1 => $menuc) {
	// 					$menu[$menup->id]["childs"][$key1]["id"] = $menuc->id;
	// 					$menu[$menup->id]["childs"][$key1]["name"] = $menuc->name;
	// 					$menu[$menup->id]["childs"][$key1]["uri"] = $menuc->uri;
	// 					$menu[$menup->id]["childs"][$key1]["link"] = base_url($menup->uri."/".$menuc->id."-".$menuc->uri);

	// 					// menu sub childs
	// 					$this->db->from('menu_admin');
	// 					$this->db->where('menu_admin_id', $menuc->id);
	// 					$this->db->where('status', 1);
	// 					$menusubchild = $this->db->get();

	// 					if( $menusubchild->num_rows() ) {
	// 						foreach ($menusubchild->result() as $key2 => $menusc) {
	// 							$menu[$menup->id]["childs"][$key1]["childs"][$key2]["id"] = $menusc->id;
	// 							$menu[$menup->id]["childs"][$key1]["childs"][$key2]["name"] = $menusc->name;
	// 							$menu[$menup->id]["childs"][$key1]["childs"][$key2]["uri"] = $menusc->uri;
	// 							$menu[$menup->id]["childs"][$key1]["childs"][$key2]["link"] = base_url($menup->uri."/".$menuc->id."-".$menuc->uri."/".$menusc->uri);
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}

	// 	// echo "<pre>";
	// 	// print_r($menu);
	// 	// die;
	// 	return $menu;
	// }

	// function getOperationsUser() {
	// 	$permission=array();
	// 	$userdata = $this->session->userdata('user_data');
	// 	$userid = (int) $userdata["user_id"];

	// 	$this->db->select('ma2.id as menu_id_parent, 
	// 						ma.id as menu_id, 
	// 						ma.uri as uri_menu, 
	// 						mao.id as operation_id,
	// 						mao.name as operation_name');
	// 	$this->db->from('users_permissions as up');
	// 	$this->db->join('menu_admin_operations as mao', 'up.menu_admin_operations_id = mao.id', 'left');
	// 	$this->db->join('menu_admin as ma', 'mao.menu_admin_id = ma.id', 'left');
	// 	$this->db->join('menu_admin as ma2', 'ma.menu_admin_id = ma2.id', 'left');
	// 	$this->db->where('up.user_id', $userid);
	// 	$this->db->order_by('ma2.id', 'asc');
	// 	$this->db->order_by('ma.id', 'asc');
	// 	$operations = $this->db->get();

	// 	if( $operations->num_rows() ) {
	// 		$a=0;
	// 		foreach ($operations->result() as $key => $operation) {
	// 			$permission[$operation->menu_id][$operation->operation_id] = $operation->operation_name;
				
	// 			$a++;
	// 		}
	// 	}
	// 	// print_r($permission);
	// 	// die();
	// 	return $permission;
	// }

	// function getAllTypeCourses() {
	// 	$this->db->from('courses_type');
	// 	$this->db->where('status', 1);
	// 	$types = $this->db->get();

	// 	return $types->result();
	// }

	// function getCategoriesModules( $typeid=0 ) {
	// 	$userdata = $this->session->userdata('user_data');
	// 	$clientid = ( $userdata ? $userdata["client_id"] : 0 );
	// 	$typeid = (int) $typeid;

	// 	$this->db->from('label');
	// 	$this->db->where('label_type_id', $typeid);
	// 	$this->db->where('client_id', $clientid);
	// 	$this->db->order_by('name', 'asc');
	// 	$categories = $this->db->get();

	// 	return $categories->result();
	// }

	// function getAllLabelsType() {
	// 	$this->db->from('label_type');
	// 	$this->db->where('status', 1);
	// 	$types = $this->db->get();

	// 	return $types->result();
	// }
}