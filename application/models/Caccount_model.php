<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caccount_model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->model('clogin_model');
		$this->usersession = $this->session->userdata('user_data');

	}

	function getUserName(){
		$sessionuser = $this->usersession;
		$id_user  = $sessionuser["id"];
		$dbc = $this->load->database('cursovia', TRUE);
		$dbc->select('u.full_name, u.emilio, u.company, c.id');
		$dbc->from('users as u');
		$dbc->join('countries as c','c.id = u.country');
		$dbc->where('u.id',$id_user);
		$dbc->where('u.status', 1);
		$users = $dbc->get();

		return $users->row_array();

	}

	function updateUsername(){

		$sessionuser = $this->usersession;
		$id_user  = $sessionuser["id"];
		$dbc = $this->load->database('cursovia', TRUE);
		$posts = $this->input->post();

			$data = array(
						'full_name' => soloCaracteresPermitidos( $posts["fullname"] ),
						'company' => soloCaracteresPermitidos( $posts["company"] ),
						'country' => $posts["country"],
					);

		$dbc->where('id', $id_user);
		$dbc->where('status', 1);
		$dbc->where('canalla', sha1($posts["current-password"]));
		$dbc->update('users', $data);

		return ( $dbc->affected_rows() ?  true : false );

	}

	function updatePassword(){

		$sessionuser = $this->usersession;
		$id_user  = $sessionuser["id"];
		$dbc = $this->load->database('cursovia', TRUE);
		$posts = $this->input->post();

			$data = array(
						'canalla' => sha1($posts["npassword"]),
					);

		$dbc->where('id', $id_user);
		$dbc->where('status', 1);
		$dbc->where('canalla', sha1($posts["current-password"]));
		$dbc->update('users', $data);

		return ( $dbc->affected_rows() ?  true : false );


	}
}