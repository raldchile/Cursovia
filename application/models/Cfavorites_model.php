<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cfavorites_model extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->usersession = $this->session->userdata('user_data');


	}

	function getFavorites($course_id='') {
		if(check_islogin() ) {
			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;
			$id_user  = $sessionuser["id"];
			// $id_course	= $id_course;
		 	$dbc->select('course_id');
		 	$dbc->from('favorites');
		 	$dbc->where('user_id',$id_user);
		 	$dbc->where('course_id',$course_id);
		 	$favorites = $dbc->get();

#		 	echo '<pre>';
#   		print_r($dbc->last_query()); die();
#   		echo '</pre>';

		return $favorites->num_rows() ? true : false;
		}
	 }

	 function getAllFavorites($course_id='') {
			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;
			$id_user  = $sessionuser["id"];
			// $id_course	= $id_course;
		 	$dbc->select('course_id');
		 	$dbc->from('favorites');
		 	$dbc->where('course_id',$course_id);
		 	$favorites = $dbc->get();

		 	$cant_favorites = $favorites->num_rows() > 1000 ? '+999' : $favorites->num_rows();


			return $cant_favorites;
	 }


	function setFavorites($course_id='') {

		if(check_islogin() ) {

		$dbc = $this->load->database('cursovia', TRUE);
		
		$sessionuser = $this->usersession;
		$id_user  = $sessionuser["id"];

		$checkFav = $this->getFavorites($course_id);

		if($checkFav){
			$dbc->where('course_id',$course_id);
			$dbc->where('user_id',$id_user);
			$dbc->delete('favorites');
		}else{

			$data = array(
						'course_id' => $course_id,
						'user_id' => $id_user
					);

			$dbc->insert('favorites', $data);
				}

				$cnat_fav_total = $this->countFavorites();
				if($cnat_fav_total>99){
					$cnat_fav_total = "+99";
				}

				$cnat_fav = $this->getAllFavorites($course_id);

				if($cnat_fav>999){
					$cnat_fav = "+999";
				}

				$favAll = $cnat_fav_total."|".$cnat_fav;
				// return $cnat_fav;

			return ( $dbc->affected_rows() ?  $favAll : false );
	}
	}

	function countFavorites($idcurso='') {
		if(check_islogin() ) {

		$idcurso = $idcurso;
		$dbc = $this->load->database('cursovia', TRUE);
		$c = 0;

		$sessionuser = $this->usersession;
		$id_user  = $sessionuser["id"];

			$dbc->select('id,course_id');
			$dbc->from('favorites');
			$dbc->where('user_id',$id_user);
			if($idcurso){
				$dbc->where('course_id',$idcurso);
			}
			$favorites = $dbc->get();


			#print_r($dbc->last_query()); die();

			if($favorites->num_rows()){
					$r = $favorites->result();

		         	foreach ($r as $key => $value) {
		         		$this->db->select('c.id');
					 	$this->db->from('courses as c');
					 	$this->db->join('lastClientOC as oc', 'oc.client_id = c.client_id');
					 	$this->db->where('oc.expire >= CURDATE()');
					 	$this->db->where('c.id',$value->course_id);
					 	$this->db->where('c.status',1);
					 	$this->db->where('c.publish_kimun',1);

					 	#print_r($this->db->last_query()); 

						$exist = $this->db->get();
						if($exist->num_rows()){
							$c = $c + 1;
						}
		         	}	

		        
	}
	return $c;
}
}

}