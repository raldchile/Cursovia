<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmessages_model extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->usersession = $this->session->userdata('user_data');
		$this->load->model('cutils_model');
		$this->load->model('ccourses_model');

	}

	function SendMessage() {
		if(check_islogin() ) {

			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;

			$posts = $this->input->post();

			//tomar variables
				$message 	=	$posts["message"];
				$passUser 	=	sha1($posts["passUser"]);
				$toUser		=	$posts["toUser"];
				$course		=	$posts["course"];
				$subject	=	$posts["subject"];
				$message_id	=	$posts["idm"];
				$fromUser	=	$sessionuser["id"];

				if(!$message_id){
				 	$message_id = NULL;
				 	}



			// valida password

				 	$dbc->from('users');
				 	$dbc->where('canalla',$passUser);
				 	$dbc->where('id',$fromUser);
				 	$dbc->where('status',1);
				 	$usr = $dbc->get();

				 	// echo $usr->num_rows();
				 	
				 		if(!$usr->num_rows()){

				 			return false; die();

				 		}else{ // Password OK

				 			//Datos de quien contacta

				 			foreach ($usr->result() as $key => $user) {
											$emilio = $user->emilio;
											$nombre = $user->full_name;
											$company = $user->company;
										}

							$token = GenToken();

				 			$data = array(

				 				'from_id' 		=> $fromUser,
				 				'to_id'	 		=> $toUser,
				 				'message_id' 	=> $message_id,
				 				'content' 		=> $message,
				 				'token' 		=> $token,
				 				'created' 		=> date('Y-m-d H:i:s'),
				 				'ischeck' 		=> 1,
				 				'status' 		=> 1,
				 				'course_id'		=> $course
				 			);
							//guardar en tabla de mensajes
				 			$dbc->insert('messages',$data);
	 								if($dbc->affected_rows()){ // si guarda ok//
	 									

	 									// manda mail a empresa que se contacta

	 									$this->db->select('name, email');
	 									$this->db->from('clients');
	 									$this->db->where('id', $toUser);

	 									$datosCliente = $this->db->get();

	 										if($this->db->affected_rows()){ // si guarda ok//

	 											foreach ($datosCliente->result() as $key => $usrCliente) {
													$cliente_email = $usrCliente->email;
													$cliente_name = $usrCliente->name;
													$url =  base_url("lectura/".$token);
												}

											$accion 	= "Contacto"; 
											$details 	= '';
											$message = "<!doctype html>";
											$message .= "<html>";
											$message .= "<head>";
											$message .= "<meta charset=\"UTF-8\">";
											$message .= "<title>Contacto realizado</title>";
											$message .= "</head>";

											$message .= "<body>";
											$message .= "<div style=\"background-color:#FFF;display: block; margin: 0 auto; border: 1px solid #e4e4e4; max-width: 650px; height: auto; min-height: 330px; font-family: 'Arial'; font-size: 15px; color: #272626; padding: 15px; line-height: 22px;\">";
											$message .= "<img src=\"https://www.cursovia.com/public/imgs/logoCusoviaEmail-fi.png\" style=\"width: 165px\" />";
											$message .= "<p style=\"font-size: 25px\">Estimado cliente:</p>";
											$message .= "<p>Has recibido un nuevo contacto en www.cursovia.com, para verlo y contactar, haz clic en el siguiente enlace, o copialo y pégalo en tu browser:</p>";
											$message .= "<p><a href=\"".$url."\">".$url."</a></p>";
											$message .= "<p>Atentamente,</p>";
											$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
											$message .= "</div>";
											$message .= "</body>";
											$message .= "</html>";

	 									

	 									$envio = sendMail( $cliente_email, $accion, $message, 'Cursovia', $details, $subject);

								 		if($envio){

								 			$accion 	= "Contacto"; 
											$details 	= '';
											$message = "<!doctype html>";
											$message .= "<html>";
											$message .= "<head>";
											$message .= "<meta charset=\"UTF-8\">";
											$message .= "<title>Contacto realizado</title>";
											$message .= "</head>";

											$message .= "<body>";
											$message .= "<div style=\"background-color:#FFF;display: block; margin: 0 auto; border: 1px solid #e4e4e4; max-width: 650px; height: auto; min-height: 330px; font-family: 'Arial'; font-size: 15px; color: #272626; padding: 15px; line-height: 22px;\">";
											$message .= "<img src=\"https://www.cursovia.com/public/imgs/logoCusoviaEmail-fi.png\" style=\"width: 165px\" />";
											$message .= "<p style=\"font-size: 25px\">Estimado usuario:</p>";
											$message .= "<p>Te enviamos un respaldo del contacto que has realizado en www.cursovia.com. ¡Apenas tengas una respuesta te avisaremos!</p>";
											$message .= "<p>Atentamente,</p>";
											$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
											$message .= "</div>";
											$message .= "</body>";
											$message .= "</html>";

	 										$envio = sendMail( $emilio, $accion, $message, 'Cursovia', $details, $subject);

								 			return true; die();
								 		}else{
								 			return false; die();
								 		}

								 	}

				 		}

		}
		}else{
	 	return false;
	 }
	 }


	 function CheckMessage($token=''){

	 	if($token){

			$output = array();
			$i = 0;


	 		//veo si token esta vigente en BBDD Cursovia
	 		$dbc = $this->load->database('cursovia', TRUE);

	 		$dbc->select('full_name, to_id, content, m.id, m.message_id, m.course_id');
	 		$dbc->from('messages as m');
	 		$dbc->join('users as u', 'm.from_id = u.id');
	 		$dbc->where('m.token', $token);
	 		$dbc->where('m.status', 1);
	 		$dbc->where('m.isget', 1);
	 		$dbc->where('u.status', 1);
	 		$datos = $dbc->get();




	 		if($dbc->affected_rows()){ // Token valido // entonces reviso si Cliente esta vigente

	 				foreach ($datos->result() as $key => $cliente) {
								$cliente_id = $cliente->to_id;
								$course_id = $cliente->course_id;
							}

						$vigente =  $this->cutils_model->checkClient($cliente_id,$action='vigencia');

	 				if($vigente){
	 					//Cliente existe y esta vigente doy chance de responder

	 					$kimun = $this->cutils_model->checkKimun($course_id, $cliente_id);

	 					// print_r($kimun); die();
	 					if($kimun){

	 					foreach ($datos->result() as $key => $dat) {


	 						if($dat->message_id){
	 						$message_id = $dat->message_id;
	 						}else{
	 						$message_id = $dat->id;
	 						}

							$output[$i]['full_name'] = $dat->full_name;
							$output[$i]['token'] = $token;
							$output[$i]['content'] =  $dat->content;
							$output[$i]['message_id'] =  $message_id;

							$datosCliente =  $this->cutils_model->checkClient($cliente_id,$action='datos');
							// print_r($datosCliente); die();
	 						foreach ($datosCliente as $key => $datCl) {
								$output[$i]['client_name'] = $datCl->name;
	 						}

	 						$i++;


						}

						$data = array(
							'ischeck' => 2
						);

						$dbc->where('token', $token);
						$dbc->update('messages', $data);

	 					return $output; die(); 
	 				}else{
	 					return "NP"; die(); // Curso no publicado en cursovia
	 				}


	 				}else{
	 					return "NC"; die(); // Cliente No existe o no está vigente
	 				}

	 		}else{// Token ya usado//
	 			return "TU"; die(); die(); //Token ya usado
	 		}

	 	}else{
	 		return "NT"; die(); //No hay token
	 	}
	 }


	 function getTheMessage() {

		$posts = $this->input->post();
		$token 	= $posts['token'];
		// echo $posts["token"];



		if($token) {

			// print_r($posts); die();

			// echo $token; 


			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;

			$dbc->select('m.to_id, m.from_id, m.id, u.emilio, m.course_id');
			$dbc->from('messages as m');
			$dbc->from('users as u','u.id = m.to_id');
			$dbc->where('m.token', $token);
			$dbc->where('m.status', 1);
			$dbc->where('u.status', 1);
			$msg = $dbc->get();
			if($msg->num_rows()){
				foreach ($msg->result() as $key => $msgdatos) {
											$toid 		= $msgdatos->to_id;
											$fromid 	= $msgdatos->from_id;
											if($posts["idm"]){
											$id 		= $posts["idm"];
										}else{
											$id 		= $msgdatos->id;
										}
											$emilio 	= $msgdatos->emilio;
											$course 	= $this->ccourses_model->getCourseName($msgdatos->course_id);
										}

			//tomar variables
				$message 	=	$posts["message"];
				$toUser		=	$toid;
				$subject	=	$posts["subject"];
				$Gentoken 	=   GenToken();
				$fromUser	=	$fromid;

				// echo GenToken(); die();

	 			$data = array(
				 				'from_id' 		=> $fromUser,
				 				'to_id'	 		=> $toUser,
				 				'message_id' 	=> $id,
				 				'token' 		=> $Gentoken,
				 				'content' 		=> $message,
				 				'created' 		=> date('Y-m-d H:i:s'),
				 				'ischeck' 		=> 1,
				 				'status' 		=> 1,
				 				'course_id'		=> $msgdatos->course_id
				 			);


	 			// print_r($data); die();

							//guardar en tabla de mensajes
				 			$dbc->insert('messages',$data);
	 								if($dbc->affected_rows()){ // si guarda ok//

	 									$data = array(
	 										'isget' => 2
	 									);

	 									// manda mail a empresa que se contacta

	 									$dbc->where('id', $msgdatos->id);
	 									$dbc->where('status', 1);
	 									$dbc->update('messages', $data);

	 										if($dbc->affected_rows()){ // si guarda ok//

											$datosCliente =  $this->cutils_model->checkClient($toUser,$action='datos');


	 										foreach ($datosCliente as $key => $usrCliente) {
													$cliente_email = $usrCliente->email;
													$cliente_name = $usrCliente->name;
													$url = base_url("lectura/".$Gentoken);
											}

											$accion 	= "Contacto"; 
											$details 	= '';
											$message = "<!doctype html>";
											$message .= "<html>";
											$message .= "<head>";
											$message .= "<meta charset=\"UTF-8\">";
											$message .= "<title>Respuesta recibida</title>";
											$message .= "</head>";

											$message .= "<body>";
											$message .= "<div style=\"background-color:#FFF;display: block; margin: 0 auto; border: 1px solid #e4e4e4; max-width: 650px; height: auto; min-height: 330px; font-family: 'Arial'; font-size: 15px; color: #272626; padding: 15px; line-height: 22px;\">";
											$message .= "<img src=\"https://www.cursovia.com/public/imgs/logoCusoviaEmail-fi.png\" style=\"width: 165px\" />";
											$message .= "<p style=\"font-size: 25px\">Estimado cliente:</p>";
											$message .= "<p>Has recibido respuesta a tu consulta de curso: <strong>".$course."</strong>. Para verlo, haz clic en el siguiente enlace, o copialo y pégalo en tu browser:</p>";
											$message .= "<p><a href=\"".$url."\">".$url."</a></p>";
											$message .= "<p>Atentamente,</p>";
											$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
											$message .= "</div>";
											$message .= "</body>";
											$message .= "</html>";

	 										$envio = sendMail( $emilio, $accion, $message, 'Cursovia', $details, $subject);

								 		if($envio){

								 			$accion 	= "Contacto"; 
											$details 	= '';
											$message = "<!doctype html>";
											$message .= "<html>";
											$message .= "<head>";
											$message .= "<meta charset=\"UTF-8\">";
											$message .= "<title>Contacto realizado</title>";
											$message .= "</head>";

											$message .= "<body>";
											$message .= "<div style=\"background-color:#FFF;display: block; margin: 0 auto; border: 1px solid #e4e4e4; max-width: 650px; height: auto; min-height: 330px; font-family: 'Arial'; font-size: 15px; color: #272626; padding: 15px; line-height: 22px;\">";
											$message .= "<img src=\"https://www.cursovia.com/public/imgs/logoCusoviaEmail-fi.png\" style=\"width: 165px\" />";
											$message .= "<p style=\"font-size: 25px\">Estimado usuario:</p>";
											$message .= "<p>Te enviamos un respaldo de la respuesta has enviado por el curso: ".$course." a través de www.cursovia.com. ¡Apenas tengas una respuesta te avisaremos!</p>";

											$message .= "<p>Atentamente,</p>";
											$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
											$message .= "</div>";
											$message .= "</body>";
											$message .= "</html>";

	 										$envio = sendMail($cliente_email, $accion, $message, 'Cursovia', $details, $subject);

								 			return true; die();
								 		}else{
								 			return false; die();
								 		}

								 	}
								 	}

				 		}

		}else{
	 	return false;
	 }
	 }


	 function GetAllMessages($token='') {
		if(check_islogin() ) {
			$output = array();
			$i = 0;

			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;
			$fromUser	=	$sessionuser["id"];

			$posts = $this->input->post();

			$dbc->from('messages');
			$dbc->where('from_id', $fromUser);
			if($token){
			$dbc->where('token', $token);
			}
			$dbc->where('message_id', NULL);
			// $dbc->where('status', 1);
			$dbc->order_by('created','desc');
			$datos = $dbc->get();

	 		if($dbc->affected_rows()){ // Token valido // entonces reviso si Cliente esta vigente

	 				foreach ($datos->result() as $key => $cliente) {
								$cliente_id = 	$cliente->to_id;
								$idmsg 		= 	$cliente->id;
								$course_id 	= 	$cliente->course_id;
								$fechamsg	=	FechaToCastle($cliente->created);
						
						$vigente =  $this->cutils_model->checkClient($cliente_id,$action='datos');
						foreach ($vigente as $key => $usrCliente) {
													$cliente_email = $usrCliente->email;
													$cliente_name = $usrCliente->name;
													$cliente_idK = $usrCliente->id;
											}

									$output[$i]['id'] = $cliente->id;
									// $output[$i]['subject'] = $cliente->subject;
									$output[$i]['created'] = $fechamsg;
									$output[$i]['message_id'] = $cliente->id;
									$output[$i]['message'] = $cliente->content;
									$output[$i]['cliente_email'] = $cliente_email;
									$output[$i]['cliente_name'] = $cliente_name;
									$output[$i]['cliente_idK'] = $cliente_idK;
									$output[$i]['token'] = $cliente->token;
									$output[$i]['ischeck'] = $cliente->ischeck;
									$output[$i]['course_name'] = $this->ccourses_model->getCourseName($cliente->course_id);
									$output[$i]['course_id'] = $course_id;
									$output[$i]['qty_responses'] = count($this->getAlllResponses($idmsg));
									$output[$i]['responses'] = $this->getAlllResponses($idmsg);
									
							$i++;				

							}

							return $output;
	
		}
		}
	}

	function getAlllResponses($id){
		$dbc = $this->load->database('cursovia', TRUE);

		$dbc->from('messages');
		$dbc->where('message_id', $id);
		$dbc->where('status', 1);
		$dbc->order_by('created', 'ASC');
		$responses = $dbc->get();
		return $responses->result();
	}

	function getQtyMsg(){
		$dbc = $this->load->database('cursovia', TRUE);
		$sessionuser = $this->usersession;
		$fromUser	=	$sessionuser["id"];

		$dbc->from('messages');
		$dbc->where('from_id', $fromUser);
		$dbc->where('message_id', NULL);
		$dbc->where('status',1);
		$qty = $dbc->get();
		return $qty->num_rows();
	}




}