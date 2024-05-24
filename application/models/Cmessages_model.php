<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cmessages_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();

		$this->usersession = $this->session->userdata('user_data');
		$this->load->model('cutils_model');
		$this->load->model('ccourses_model');
	}

	function SendMessage()
	{
		if (check_islogin()) {

			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;

			$posts = $this->input->post();
			$message 	=	$posts["message"];
			$passUser 	=	sha1($posts["passUser"]);
			$toUser		=	$posts["toUser"];
			$course		=	$posts["course"];
			$subject	=	$posts["subject"];
			$message_id	=	$posts["idm"];
			$fromUser	=	$sessionuser["id"];

			if (!$message_id) {
				$message_id = NULL;
			}

			// valida password

			$dbc->from('users');
			$dbc->where('canalla', $passUser);
			$dbc->where('id', $fromUser);
			$dbc->where('status', 1);
			$user = $dbc->get();

			// echo $user->num_rows();

			if (!$user->num_rows()) {
				return false;
				die();
			} else { // Password OK

				//Datos de quien contacta

				$user = $user->row_array();

				$emilio = $user['emilio'];
				$nombre = $user['full_name'];
				$company = $user['company'];

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
				$dbc->insert('messages', $data);

				if ($dbc->affected_rows()) { // si guarda ok//


					// manda mail a empresa que se contacta
					$this->db->select('name, email');
					$this->db->from('clients');
					$this->db->where('id', $toUser);

					$datosCliente = $this->db->get();
					if ($datosCliente->num_rows()) { // si guarda ok//
						/* foreach ($datosCliente->result() as $key => $usrCliente) {
							$cliente_email = $usrCliente->email;
							$cliente_name = $usrCliente->name;
							$url =  base_url("lectura/" . $token);
						} */

						$datosCliente = $datosCliente->row_array();

						$cliente_email = $datosCliente['email'];
						$cliente_name = $datosCliente['name'];
						$url =  base_url("lectura/" . $token);

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
						$message .= "<p><a href=\"" . $url . "\">" . $url . "</a></p>";
						$message .= "<p>Atentamente,</p>";
						$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
						$message .= "</div>";
						$message .= "</body>";
						$message .= "</html>";



						$envio = sendMail($cliente_email, $accion, $message, 'Cursovia', $details, $subject);

						if ($envio) {

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

							$envio = sendMail($emilio, $accion, $message, 'Cursovia', $details, $subject);

							return true;
							die();
						} else {
							return false;
							die();
						}
					}
				}
			}
		} else {
			return false;
		}
	}


	function CheckMessage($token = '')
	{

		if ($token) {

			$output = array();

			//veo si token esta vigente en BBDD Cursovia
			$dbc = $this->load->database('cursovia', TRUE);

			$dbc->select('to_id, from_id, content, m.id, m.message_id, m.course_id');
			$dbc->from('messages as m');
			$dbc->where('m.token', $token);
			$dbc->where('m.status', 1);
			$dbc->where('m.isget', 1);
			$datos = $dbc->get();


			if ($datos->num_rows()) { // Token valido // entonces reviso si Cliente esta vigente


				$datos = $datos->row_array();

				$course_id = $datos['course_id'];
				$clientId = $this->ccourses_model->getClientofCourse($course_id);

				$vigente =  $this->cutils_model->checkClient($clientId, $action = 'vigencia');

				if ($vigente) {
					//Cliente existe y esta vigente doy chance de responder

					$kimun = $this->cutils_model->checkKimun($course_id, $clientId);

					// print_r($kimun); die();
					if ($kimun) {

						$message_id = ($datos['message_id']) ? $datos['message_id'] : $datos['id'];

						$dbc->select('full_name, u.id');
						$dbc->from('messages as m');
						$dbc->join('users as u', 'm.from_id = u.id');
						$dbc->where('u.status', 1);
						$dbc->where('m.id', $message_id);
						$user = $dbc->get()->row_array();

						$output['course'] = $this->ccourses_model->getCourseName($course_id);

						$output['full_name'] = $user['full_name'];
						$output['token'] = $token;
						$output['content'] =  $datos['content'];
						$output['message_id'] =  $message_id;
						$datosCliente = $this->cutils_model->checkClient($clientId, $action = 'datos');
						$output['client_name'] = $datosCliente['name'];

						if ($datos['from_id'] == $user['id']) {
							$output['from'] = $user['full_name'];
							$output['to'] = $datosCliente['name'];
						} else {
							$output['from'] = $datosCliente['name'];
							$output['to'] = $user['full_name'];
						}

						$data = array(
							'ischeck' => 2
						);

						$dbc->where('token', $token);
						$dbc->update('messages', $data);

						return $output;
						die();
					} else {
						return "NP";
						die(); // Curso no publicado en cursovia
					}
				} else {
					return "NC";
					die(); // Cliente No existe o no está vigente
				}
			} else { // Token ya usado//
				return "TU";
				die();
				die(); //Token ya usado
			}
		} else {
			return "NT";
			die(); //No hay token
		}
	}


	function getTheMessage()
	{

		$posts = $this->input->post();
		$token 	= $posts['token'];
		// echo $posts["token"];


		if ($token) {

			// print_r($posts); die();

			// echo $token; 


			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;

			$dbc->select('m.to_id, m.from_id, m.id, m.course_id');
			$dbc->from('messages as m');
			$dbc->where('m.token', $token);
			$dbc->where('m.status', 1);
			$msg = $dbc->get();
			if ($msg->num_rows()) {

				$msg = $msg->row_array();

				$fromid = $msg['to_id'];
				$toid = $msg['from_id'];
				$id = ($posts["idm"]) ? $posts["idm"] : $msg['id'];

				$course 	= $this->ccourses_model->getCourseName($msg['course_id']);
				$idCourse = $msg['course_id'];

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
					'course_id'		=> $idCourse
				);


				// print_r($data); die();

				//guardar en tabla de mensajes
				$dbc->insert('messages', $data);
				if ($dbc->affected_rows()) { // si guarda ok//

					$data = array(
						'isget' => 2
					);

					// manda mail a empresa que se contacta

					$dbc->where('id', $msg['id']);
					$dbc->where('status', 1);
					$dbc->update('messages', $data);

					if ($dbc->affected_rows()) { // si guarda ok//

						$clientId = $this->ccourses_model->getClientofCourse($course_id);

						$datosCliente = $this->cutils_model->checkClient($clientId, $action = 'datos');
						$cliente_name  = $datosCliente['name'];
						$cliente_email = $datosCliente['email'];

						$dbc->select('u.id, emilio');
						$dbc->from('messages as m');
						$dbc->join('users as u', 'm.from_id = u.id');
						$dbc->where('u.status', 1);
						$dbc->where('m.id', $id);
						$user = $dbc->get()->row_array();

						if ($msg['from_id'] == $user['id']) {
							$from_emilio = $datosCliente['email'];
							$to_emilio = $user['emilio'];
						} else {
							$from_emilio = $user['emilio'];
							$to_emilio = $datosCliente['email'];
						}




						$url = base_url("lectura/" . $Gentoken);

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
						$message .= "<p>Has recibido respuesta a una consulta del curso: <strong>" . $course . "</strong>. Para verlo, haz clic en el siguiente enlace, o copialo y pégalo en tu browser:</p>";
						$message .= "<p><a href=\"" . $url . "\">" . $url . "</a></p>";
						$message .= "<p>Atentamente,</p>";
						$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
						$message .= "</div>";
						$message .= "</body>";
						$message .= "</html>";

						$envio = sendMail($to_emilio, $accion, $message, 'Cursovia', $details, $subject);

						if ($envio) {

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
							$message .= "<p>Te enviamos un respaldo de la respuesta has enviado por el curso: " . $course . " a través de www.cursovia.com. ¡Apenas tengas una respuesta te avisaremos!</p>";

							$message .= "<p>Atentamente,</p>";
							$message .= "<p>Tus amigos de <strong>cursovia.com</strong></p>";
							$message .= "</div>";
							$message .= "</body>";
							$message .= "</html>";

							$envio = sendMail($from_emilio, $accion, $message, 'Cursovia', $details, $subject);

							return true;
							die();
						} else {
							return false;
							die();
						}
					}
				}
			}
		} else {
			return false;
		}
	}


	function GetAllMessages()
	{
		if (check_islogin()) {
			$output = array();
			$i = 0;

			$dbc = $this->load->database('cursovia', TRUE);
			$sessionuser = $this->usersession;
			$fromUser	=	$sessionuser["id"];

			$dbc->from('messages');
			$dbc->where('from_id', $fromUser);
			$dbc->where('message_id', NULL);
			$dbc->where('status', 1);
			$dbc->order_by('created', 'desc');
			$datos = $dbc->get();


			if ($dbc->affected_rows()) { // Token valido // entonces reviso si Cliente esta vigente

				foreach ($datos->result() as $key => $msg) {

					$idmsg 		= 	$msg->id;
					$course_id 	= 	$msg->course_id;
					$fechamsg	=	$msg->created;
					$clientId = $this->ccourses_model->getClientofCourse($course_id);

					$datosCliente = $this->cutils_model->checkClient($clientId, 'datos');
					$cliente_name  = $datosCliente['name'];
					$cliente_email = $datosCliente['email'];
					$cliente_idK = $datosCliente['id'];
					$last_response = $this->GetMessagesResponses($msg->token, true);
					$vigente =  $this->cutils_model->checkClient($cliente_idK);

					$output[$i]['client'] = $this->ccourses_model->getClient($datosCliente['slug']);
					$output[$i]['client_validity'] = $vigente;
					$output[$i]['id'] = $idmsg;
					$output[$i]['from_id'] = $msg->from_id;
					// $output[$i]['subject'] = $msg->subject;
					$output[$i]['created'] = $fechamsg;
					$output[$i]['message_id'] = $msg->message_id;
					$output[$i]['message'] = $msg->content;
					$output[$i]['cliente_email'] = $cliente_email;
					$output[$i]['cliente_name'] = $cliente_name;
					$output[$i]['cliente_idK'] = $cliente_idK;
					$output[$i]['token'] = $msg->token;
					$output[$i]['ischeck'] = $msg->ischeck;
					$output[$i]['course_name'] = $this->ccourses_model->getCourseName($course_id);
					$output[$i]['course_id'] = $course_id;
					$output[$i]['unreadMsg'] = $this->GetUnreadMessages($msg->token);
					$output[$i]['last_response'] = end($last_response);

					$i++;
				}

				return $output;
			}
		}
	}


	function GetMessagesResponses($token = '', $isForLastResponse = false)
	{
		$output = array();
		$i = 0;

		$unreadMsg = $this->GetUnreadMessages($token);
		$sessionuser = $this->usersession;

		$dbc = $this->load->database('cursovia', TRUE);
		$dbc->from('messages');
		$dbc->where('token', $token);
		$dbc->or_where('message_id IN (SELECT id FROM cursovia_messages WHERE token = ' . $this->db->escape($token) . ' AND message_id IS NULL)');
		$dbc->order_by('created', 'asc');
		$datos = $dbc->get();

		if ($dbc->affected_rows()) { // Token valido // entonces reviso si Cliente esta vigente

			foreach ($datos->result() as $key => $msg) {

				$idmsg 		= 	$msg->id;
				$course_id 	= 	$msg->course_id;
				$fechamsg	=	$msg->created;
				$clientId = $this->ccourses_model->getClientofCourse($course_id);
				$justChecked = false;
				if ($msg->ischeck == 1 and !$isForLastResponse and $msg->from_id != $sessionuser["id"]) {
					$justChecked = true;
					$data = array(
						'ischeck' => 2
					);

					$dbc->where('token', $msg->token);
					$dbc->update('messages', $data);
				}

				$datosCliente = $this->cutils_model->checkClient($clientId, 'datos');
				$cliente_name  = $datosCliente['name'];
				$cliente_email = $datosCliente['email'];
				$cliente_idK = $datosCliente['id'];

				$output[$i]['client'] = $this->ccourses_model->getClient($datosCliente['slug']);
				$vigente =  $this->cutils_model->checkClient($cliente_idK);

				if (!$vigente && !$isForLastResponse) return 'NC';

				$output[$i]['id'] = $idmsg;
				$output[$i]['from_id'] = $msg->from_id;
				// $output[$i]['subject'] = $msg->subject;
				$output[$i]['created'] = $fechamsg;
				$output[$i]['message_id'] = $msg->message_id;
				$output[$i]['message'] = $msg->content;
				$output[$i]['cliente_email'] = $cliente_email;
				$output[$i]['cliente_name'] = $cliente_name;
				$output[$i]['cliente_idK'] = $cliente_idK;
				$output[$i]['token'] = $msg->token;
				$output[$i]['ischeck'] = $msg->ischeck;
				$output[$i]['unreadMsg'] = $unreadMsg;
				$output[$i]['justChecked'] = $justChecked;
				$output[$i]['course_name'] = $this->ccourses_model->getCourseName($course_id);
				$output[$i]['course_id'] = $course_id;
				/* $output[$i]['qty_responses'] = count($this->getAlllResponses($idmsg));
				$output[$i]['responses'] = $this->getAlllResponses($idmsg); */

				$i++;
			}

			return $output;
		} else {
			return 'NT';
		}
	}

	function GetUnreadMessages($token = '')
	{

		$sessionuser = $this->usersession;
		$fromUser	=	$sessionuser["id"];
		$dbc = $this->load->database('cursovia', TRUE);
		$dbc->from('messages');
		$dbc->where('message_id IN (SELECT id FROM cursovia_messages WHERE token = ' . $this->db->escape($token) . ' AND message_id IS NULL)');
		$dbc->where('from_id !=', $fromUser);
		$dbc->where('ischeck', 1);
		$count = $dbc->get()->num_rows();
		return $count;
	}

	function GetAllUnreadMessages()
	{
		$sessionuser = $this->usersession;
		$user	=	isset($sessionuser["id"]) ? $sessionuser["id"] : null;
		$count = 0;
		if ($user) {
			$dbc = $this->load->database('cursovia', TRUE);
			$dbc->from('messages');
			$dbc->where('to_id', $user);
			$dbc->where('ischeck', 1);
			$dbc->where('status', 1);
			$dbc->order_by('created', 'desc');
			$count = $dbc->get()->num_rows();
		}
		return $count;
	}


	function getAlllResponses($id)
	{
		$dbc = $this->load->database('cursovia', TRUE);

		$dbc->from('messages');
		$dbc->where('message_id', $id);
		$dbc->where('status', 1);
		$dbc->order_by('created', 'ASC');
		$responses = $dbc->get();
		return $responses->result();
	}

	function getQtyMsg()
	{
		$dbc = $this->load->database('cursovia', TRUE);
		$sessionuser = $this->usersession;
		$fromUser	=	isset($sessionuser["id"]) ? $sessionuser["id"] : null;
		$count  = 0;
		if ($fromUser) {
			$dbc->from('messages');
			$dbc->where('from_id', $fromUser);
			$dbc->where('message_id', NULL);
			$dbc->where('status', 1);
			$qty = $dbc->get();
			$count = $qty->num_rows();
		}
		return $count;
	}
}
