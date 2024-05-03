<?php setlocale(LC_MONETARY, 'es_CL'); ?>
<?php

date_default_timezone_set('America/Santiago');


function check_islogin(){
    $CI =& get_instance();
    $is_logged_in = $CI->session->userdata('user_data');
    
    if( !$is_logged_in or $is_logged_in["validated"] != true ) {
        return false;
    }else{
        return true;
    }
    
}

function stot($txt){
  // $name = ucfirst(mb_strtolower($txt));
	$name = ucfirst(mb_strtoupper($txt));
	return $name;
}

function soloCaracteresPermitidos($word,$tipo='') {
    if($tipo=='telefono'){
        $word = preg_replace("/[^0-9\.\-\(\)\s]/", "", $word);
    }elseif($tipo=='rut'){
        $word = preg_replace("/[^0-9\k\K]/", "", $word);
    }elseif($tipo=='letras'){
        $word = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/", "", $word);
    }elseif($tipo=='numeros'){
        $word = preg_replace("/[^0-9]/", "", $word);
    }elseif($tipo=='user'){
        $word = preg_replace("/[^a-zA-Z0-9\_\-]/", "", $word);
    }elseif($tipo=='title'){
        $word = preg_replace("/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\_\-\s]/", "", $word);
    }elseif($tipo=='content_html'){
        $word = preg_replace('#<script(.*?)>(.*?)</script>#is', "", $word);
    }elseif($tipo=='text_welcome'){
        $word = preg_replace("/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑäëïöüÄËÏÖÜ\.\@\&\_\-\s\,\#\:\/\%\|\?\¿\=\*\(\)\!\¡]/", "", $word);
    }elseif($tipo==''){
        $word = preg_replace("/[^a-zA-Z0-9áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙñÑäëïöüÄËÏÖÜ\.\@\&\_\-\s\,\#\:\/\%\|\*\?\¿\=\"\"\''\(\)\!\¡]/", "", $word);
        // $word = addslashes( $word );
    }

    $word = trim($word);
    // $word = utf8_encode($word);
    return $word;
}

 function url_actual(){
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $url = "https://"; 
  }else{
    $url = "http://"; 
  }
  return $url . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI'];
 }

 function sendMail( $email='', $accion='', $message='', $nameclient='', $details = '', $subject = '') {
            
        if( !empty( $email ) ) {
        $CI =& get_instance();
        $CI->load->library('email');

            // Conectamos sendmail
            $configGmail = array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.mandrillapp.com',
                'smtp_port' => 587,
                'smtp_user' => 'Rald',
                'smtp_pass' => 'md-BxSG__wuRCSqSARsg9nc-w',
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n"
            );

            $subject = $subject;
            //cargamos la configuración para enviar con gmail
            $CI->email->initialize($configGmail);

            // Creamos HTML del Body
            // $html = "Estimado administrador de plataforma " .$nameclient. ", ";
            // $html .= "enviamos un resumen de acción realizada en lote:<br /><br />";
            // $html .= "<strong>Fecha:</strong> " .date('Y-m-d H:i:s')." (GMT-4)<br />";
            // $html .= "<strong>Acción(es) realizada(s):</strong> " .$accion."<br />";
            // $html .= "<strong>Usuarios procesados:</strong> ".$message."<br /><br />";
            // $html .= "Si esta acción le parece sospechosa, por favor contáctenos a <strong>soporte@kampusproject.com</strong> <br /><br />";
            // $html .= "Revise a continuación, el detalle de los errores ocurridos.<br /><br />";
            // $html .= "Atte., <br />Equipo Kampüs<br /><br />";
            // $html .= "------------------------------------------------------------------------<br />";
            // $html .= "DETALLE DE ERRORES EN EL PROCESAMIENTO:<br />";
            // $html .= "------------------------------------------------------------------------<br /><br />";
            // $html .= $details."<br /><br />";
            // $html .= "---------------------------------------------<br />";



            $CI->email->from('noreply@cursovia.com', "Cursovia.com");
            $CI->email->to($email);
            $CI->email->subject($subject);
            $CI->email->message($message);
        // $CI->email->send();

        if( $CI->email->send() ) {
            return true;
        }else{
            return false;
        }
        //con esto podemos ver el resultado
        // var_dump($CI->email->print_debugger());

        }

    }

    function GenToken(){
            $fecha   = date('YmdHis');
            $token      = sha1($fecha);
            return $token;
    }

    function FechaToCastle($FechaMsg) {
          $fecha_consulta   = date('Y-m-d');
          $fecha_consulta_am   = date('Y-m');
          $fecha = substr($FechaMsg, 0, 10);
          $fecha_am = substr($fecha, 0, 7);

          // return $fecha_consulta." ".$fecha; die();

          

          $numeroDia = date('d', strtotime($fecha));
          $dia = date('l', strtotime($fecha));
          $mes = date('F', strtotime($fecha));
          $anio = date('Y', strtotime($fecha));
          $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
          $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
          $nombredia = str_replace($dias_EN, $dias_ES, $dia);
          $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
          $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
          $nombreMes = str_replace($meses_EN, $meses_ES, $mes);

          if($fecha_consulta==$fecha){
            return substr($FechaMsg, 10, 9);
          }elseif($fecha_consulta_am ==  $fecha_am){
            return $numeroDia." ".substr($nombreMes, 0, 3);
          }else{
            return date('d', strtotime($fecha))."/".date('m', strtotime($fecha))."/".date('Y', strtotime($fecha));
          }
     

          #return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
}

function formateaMoneda($monto){
      //return $monto."ggg"; die();
                //$monto_format = money_format('%.0i', $monto);
                //return $monto_format;
                $monto_format = number_format($monto, 0, ',', '.');
                return '$' . $monto_format;
}
