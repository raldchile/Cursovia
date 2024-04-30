<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clogin_model extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->model('clogin_model');
	}

	function setAccount() {
		$dbc = $this->load->database('cursovia', TRUE);
		$posts = $this->input->post();

		if($posts){

			$created 	= date('Y-m-d H:i:s');
			$token 		= sha1($created);
			$url		= base_url('validate/'.$token);

			$full_name	= soloCaracteresPermitidos( $posts["fullname"] );
			$company 	= soloCaracteresPermitidos( $posts["company"] );
			$email 		= soloCaracteresPermitidos( $posts["email"] );
			$subject 	= "¡Registro listo... Activa tu cuenta!";
			$pais 		= $posts["country"];
			// $message = "activa tu cuenta";
			$accion 	= "Registro"; 
			$details 	= '';

			$message = "<!doctype html>";
			$message .= "<html>";
			$message .= "<head>";
			$message .= "<meta charset=\"UTF-8\">";
			$message .= "<title>Email de bienvenida</title>";
			$message .= "</head>";

			$message .= "<body>";
			$message .= "<div style=\"background-color:#FFF;display: block; margin: 0 auto; border: 1px solid #e4e4e4; max-width: 650px; height: auto; min-height: 330px; font-family: 'Arial'; font-size: 15px; color: #272626; padding: 15px; line-height: 22px;\">";
			$message .= "<img src=\"https://www.cursovia.com/public/imgs/logoCusoviaEmail-fi.png\" style=\"width: 165px\" />";
			$message .= "<p style=\"font-size: 25px\">¡Bienvenid@!</p>";
			$message .= "<p>Estás a un paso de ser parte de <strong>Cursovia</strong>! como ya hemos registrado tus datos, sólo debes activar tu cuenta. Para eso hace clic en el siguiente botón o, copia y pega el enlace a continuación:</p>";
			$message .= "<p><a href=\"".$url."\">".$url."</a></p>";
			$message .= "<p>Atentamente,</p>";
			$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
			$message .= "</div>";
			$message .= "</body>";
			$message .= "</html>";



			$data = array(
						'full_name' => $full_name,
						'company' => $company,
						'emilio' => $email,
						'canalla' => sha1($posts["password"]),
						'token' => $token,
						'country' => $pais,
						'status' => 2,
						'created' => $created,
					);
		
	 	$dbc->insert('users', $data);

	 	if($dbc->affected_rows()){

	 		$envio = sendMail( $email, $accion, $message, $company, $details, $subject);

	 		if($envio){
	 			return true; die();
	 		}else{
	 			return false; die();
	 		}

	 	}else{
	 		return false; die();
	 	}
	 }else{
	 	return false;
	 }

	}

	function validaUser(){
		$dbc = $this->load->database('cursovia', TRUE);
		$posts = $this->input->post();
		if($posts){
			$email = $posts["email"];

			$dbc->from('users');
			$dbc->where('emilio', $email);
			$dbc->where('status <> 3');
	 		$val = $dbc->get();

			return $val->num_rows() ? true : false;

		}else{
			return false;
		}

	}

	function checkToken($token){

		if($token){

		$data = array('status' => 1);
		$dbc = $this->load->database('cursovia', TRUE);
			$dbc->where('token',$token);
			$dbc->where('status',2);
			$dbc->update('users',$data);
			return $dbc->affected_rows() ? true : false;
		}else{
			return false;
		}
	}

	function checkLogin($EmailOnly=false){
		$dbc = $this->load->database('cursovia', TRUE);
		$posts = $this->input->post();
		if($posts){
			$email 	= $posts["emailUser"];
			$pass 	= sha1($posts["passUser"]);

			$dbc->from('users');
			$dbc->where('emilio', $email);
			if(!$EmailOnly){
				$dbc->where('canalla', $pass);
			}
			$dbc->where('status', 1);
	 		$val = $dbc->get();

			//return $val->num_rows() ? true : false;

			if( $val->num_rows() ) {

				$userrs = $val->row();

				if(!$EmailOnly){


					$data = array(
						'id' => $userrs->id,
						'full_name' => $userrs->full_name,
						'company' => $userrs->company,
						'emilio' => $userrs->emilio,
						'country' => $userrs->country,
						'status' => $userrs->status,
						'validated' => true
					);

					
					$this->session->set_userdata('user_data', $data);
					return true;
			}

			return $userrs;

		}else{
			return false;
		}

		}else{
			return false;
		}

	}

	function SendNewPassword($iduser=''){


		// print_r($iduser); die();

		/* $id = (int)$iduser->id;
		$emilio = $iduser->emilio;
		$name = $iduser->full_name; */

		if (is_object($iduser)) {
			$id = isset($iduser->id) ? (int)$iduser->id : null;
			$emilio = isset($iduser->emilio) ? $iduser->emilio : null;
			$name = isset($iduser->full_name) ? $iduser->full_name : null;
		} else {
			// Manejo de la situación donde $iduser no es un objeto
			$id = null;
			$emilio = null;
			$name = null;
		}

		$dbc = $this->load->database('cursovia', TRUE);
		$date 	= date('Y-m-d H:i:s');


			$token 		= substr(sha1($date),0,8);

			$full_name	= soloCaracteresPermitidos( $posts["fullname"] );
			$email 		= soloCaracteresPermitidos( $posts["email"] );
			$subject 	= "Enviamos una nueva clave";
			$accion 	= "Registro"; 

			$message = "<!doctype html>";
			$message .= "<html>";
			$message .= "<head>";
			$message .= "<meta charset=\"UTF-8\">";
			$message .= "<title>Recuperaste tu clave</title>";
			$message .= "</head>";

			$message .= "<body>";
			$message .= "<div style=\"background-color:#FFF;display: block; margin: 0 auto; border: 1px solid #e4e4e4; max-width: 650px; height: auto; min-height: 330px; font-family: 'Arial'; font-size: 15px; color: #272626; padding: 15px; line-height: 22px;\">";
			$message .= "<img src=\"https://www.cursovia.com/public/imgs/logoCusoviaEmail-fi.png\" style=\"width: 165px\" />";
			$message .= "<p style=\"font-size: 25px\">¡Hola ".$name."!</p>";
			$message .= "<p>Hemos recibido una solicitud de nueva clave para tu cuenta en <strong><a href=\"https://cursovia.local/Cursovia\">www.cursovia.com</a></strong>. Tu nueva clave es: <strong>".$token."</strong>. te recomendamos cambiarla desde el panel de usuario > Mi cuenta.</p>";

			$message .= "<p><strong>¡Ya puedes volver a conectarte y ser parte de este gran marketPlace de Cursos! Ingresa a:</p>";
			$message .= "<p><a href=\"https://cursovia.local/Cursovia/ingresar\">www.cursovia.com</a></p>";
			$message .= "<p>Atentamente,</p>";
			$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
			$message .= "</div>";
			$message .= "</body>";
			$message .= "</html>";



			$data = array(
						'canalla' => sha1($token),
					);
		
	 	$dbc->where('id', $id);
	 	$dbc->where('status', 1);
	 	$dbc->update('users', $data);


	 	if($dbc->affected_rows()){

	 		$envio = sendMail( $emilio, $accion, $message, $company, $details, $subject);

	 		// echo $emilio. " entra ".$envio; die();


	 		if($envio){
	 			return true; 
	 		}else{
	 			return false;
	 		}

	 	}else{
	 		return false; 
	 	}
	 }

}