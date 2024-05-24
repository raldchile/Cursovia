<?php

use function PHPSTORM_META\type;

echo $header; ?>
<link rel="stylesheet" href="<?php echo base_url("public/css/inbox-detail.css") ?>">
<section class="padding-sct">
  <div class="container" style="padding: 0;">
    <?php if ($mensajes) {

      $user_id          = $mensajes[0]["from_id"];
      $cliente_idK      = $mensajes[0]["cliente_idK"];
      $message          = $mensajes[0]["message"];
      $token            = $mensajes[0]["token"];
      $created          = FechaToCastle($mensajes[0]["created"]);
      $ischeck          = $mensajes[0]["ischeck"];
      $course_name      = $mensajes[0]["course_name"];
      $course_id        = $mensajes[0]["course_id"];
      $message_id       = $mensajes[0]["id"];
      $class            = "";
      $marca            = "";
      $client_name = stot($mensajes[0]['client']["name"]);
      $client_slug = $mensajes[0]['client']["slug"];
      $client_alias = $mensajes[0]['client']['alias'];
      $client_phone = $mensajes[0]['client']['phone'];
      $client_email = $mensajes[0]['client']['email'];
      $client_color_first = $mensajes[0]['client']['color_first'];
      $client_color_second = $mensajes[0]['client']['color_second'];
      $client_background = $mensajes[0]['client']['background'];
      $client_logo = $mensajes[0]['client']['logo'];
      $client_profile_img = $mensajes[0]['client']['profile_img'];

      $client_profile_img = ($client_profile_img) ? URL_KAMPUS . $client_profile_img : base_url('public/imgs/ca_perfil_2.png');
      $user_profile_img =  base_url('public/imgs/ca_user_perfil.png');
    ?>
      <div class="row">
        <div class="col-lg-8">
          <div class="msg-list">
            <div class="titulos">Inbox</div>
            <div class="inbox-msg">
              <h1 class="inbox-title">Contacto para curso <?php echo $course_name; ?></h1>
              <div class="inbox-first-msg">
                <div class="profile">
                <img src="<?php echo $user_profile_img?>" alt="Imagen perfil">
                  <p class="msg-title">Consulta enviada a <?php echo $client_alias ?>:</p>
                </div>
                <p class="msg-content"><i class="fa-solid fa-check-double" style="<?php echo ($ischeck == 2) ? 'color: #067aff' : 'color: #3b4a54' ?>"></i><?php echo $message ?></p>
                <p class="msg-date"><?php echo $created; ?></p>
              </div>
            </div>
            <?php
            $responses = array_slice($mensajes, 1);
            $firstUnread = false;
            foreach ($responses as $index => $msg) {
              $message = $msg['message'];
              $created = $msg['created'];
              $ischeck = $msg['ischeck'];
              $from_id = $msg['from_id'];
              $unreadMsgs = $msg['unreadMsg'];
              $classVar = "";
              $marca = "";

              if ($msg['justChecked'] and !$firstUnread and $from_id == $cliente_idK) {
                $firstUnread = true;
            ?>
                <div class="first-unread">
                  <?php echo ($unreadMsgs == 1) ? $unreadMsgs . ' mensaje sin leer' : $unreadMsgs . ' mensajes sin leer' ?><i class="fa-solid fa-arrow-down"></i>
                </div>
              <?php } ?>
              <div class="response">
                <div class="profile">
                  <img src="<?php echo $from_id == $cliente_idK ? $client_profile_img : $user_profile_img; ?>" alt="Imagen perfil">
                  <p class="msg-title"><?php echo $from_id == $cliente_idK ? ($client_alias ? $client_alias : $client_name) : 'Tú'; ?></p>
                </div>
                <div class="inbox-msg inbox-responses-msg">
                  <?php if ($from_id != $cliente_idK) { ?>
                    <p class="msg-content"><i class="fa-solid fa-check-double" style="<?php echo ($ischeck == 2) ? 'color: #067aff' : 'color: #3b4a54' ?>"></i><?php echo $message ?></p>
                  <?php } else { ?>
                    <p class="msg-content"><?php echo $message ?></p>
                  <?php } ?>
                  <p class="msg-date"><?php echo FechaToCastle($created); ?></p>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="col-lg-4" style="padding: 0;">
          <div class="container msg-box">
            <div class="row">
              <div class="col-md-12">
                <div class="modal-body">
                  <form action="<?php echo base_url("cmessages/sendMessage") ?>" method="post" role="form" data-toggle="validator" id="contact-form" method="post">
                    <div class="respuesta"></div>
                    <div class="acount-group">
                      <label><strong>Envía una respuesta o consulta:</strong></label>
                      <textarea id="msg" name="message" class="form-control" placeholder="Escribe tu consulta" required style="height: 200px;"></textarea>
                    </div>
                    <div class="form-group input-group-lg acount-group">
                      <label>Por seguridad, ingresa tu password:</label>
                      <input type="password" name="passUser" class="form-control" placeholder="Password" id="password" required>
                    </div>
                    <input type="hidden" name="toUser" value="<?php echo $cliente_idK ?>">
                    <input type="hidden" name="course" value="<?php echo $course_id ?>">
                    <input type="hidden" name="idm" value="<?php echo $message_id ?>">
                    <input type="hidden" name="subject" value="Consulta curso <?php echo $course_name; ?>">
                    <button type="submit" class="button-primary center" id="enviar-form">Enviar contacto >></button>
                  </form>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
  </div>

<?php } else { ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <p class="msg-results">No encontramos ningún mensaje o contacto realizado.</p>
      </div>
    </div>
  </div>
<?php } ?>
</section>
<?php if ($mensajes) { ?>
  <script>
    $(document).ready(function() {
      /*  
            var mensajes = <?php echo json_encode(array_slice($mensajes, 1)); ?>;
            var zIndex = -1;
            var translateY = -15;
            var colorUser = 'rgb(255, 255, 255)';
            var colorCli = 'rgb(135 142 150/ 10%)';
            $.each(mensajes, function(index, value) {



              var message = value.message;
              var created = value.created;
              var ischeck = value.ischeck;
              var from_id = value.from_id;
              var classVar = "";
              var marca = "";

              if (ischeck) {
                classVar = "checked";
              }

              var lastMsg = $(".msg-list .row:last");
              var firstMsg = $(".msg-list .row:first");
              var left = lastMsg.position().left;
              var width = lastMsg.width();
              var newTop = lastMsg.position().top + lastMsg.outerHeight() - 20;




              var msgHeader = '<div class="msg-header">' +
                '<strong class="msg-title">' + (from_id == <?php echo $cliente_idK; ?> ? '<?php echo $client_name; ?>' : 'Tú') + '</strong>' +
                '<p class="msg-date">' + created + '</p>' +
                '</div>';
              var msgContent = '<div class="msg-content">' + message + '</div>';
              var messageRow = '<div class="row justify-content-end">' +
                '<div class="col-md-10 inbox-msg inbox-responses-msg" style="z-index: ' + zIndex + '; transform: translateY(' + translateY + 'px); background-color:' + (from_id == <?php echo $cliente_idK; ?> ? colorCli : colorUser) +
                ';">' +
                msgHeader + msgContent +
                '</div>' +
                '</div>';

              $(messageRow).appendTo(".msg-list");



              zIndex -= 1;
              translateY -= 15;
            }); */

      function newMsg() {
        var msg = $.trim($("#msg").val());
        /*  var lastMsg = $(".msg-list .row:last");
         var firstMsg = $(".msg-list .row:first");
         var left = lastMsg.position().left;
         var width = lastMsg.width();
         var newTop = lastMsg.position().top + lastMsg.outerHeight() - 20; */
        var newMsg = `<div class="response">
                <div class="profile">
                  <img src="<?php echo $user_profile_img ?>" alt="Imagen perfil">
                  <p class="msg-title">Tú:</p>
                </div>
                <div class="inbox-msg inbox-responses-msg">
                <p class="msg-content"><i class="fa-solid fa-check-double" style="color: #3b4a54"></i>${msg}</p>
                  <p class="msg-date">Hace unos segundos</p>
                </div>
              </div>`

        $('.content-msg').fadeOut("slow", function() {
          $(newMsg).appendTo(".msg-list");
          $.trim($("#msg").val(''));
        });

        /* zIndex -= 1;
        translateY -= 15; */
      }

      if ($('.first-unread').length) {
        // Si existe, le hace focus
        console.log("si");
        $('.first-unread')[0].scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }

      $("#contact-form").bind("submit", function() {
        // Capturamnos el boton de envío
        var btnEnviar = $("#enviar-form");
        $.ajax({
          type: $(this).attr("method"),
          url: $(this).attr("action"),
          data: $(this).serialize(),
          beforeSend: function() {
            /*
             * Esta función se ejecuta durante el envió de la petición al
             * servidor.
             * */
            // btnEnviar.text("Enviando"); Para button 
            txt = "<span class=\"content-msg\">Enviando...</span>";
            $(".respuesta").html(txt);
            btnEnviar.val("Enviando"); // Para input de tipo button
            btnEnviar.attr("disabled", "disabled");
          },
          complete: function(data) {
            /*
             * Se ejecuta al termino de la petición
             * */
            btnEnviar.val("Enviar formulario");
            btnEnviar.removeAttr("disabled");
          },
          success: function(data) {
            /*
             * Se ejecuta cuando termina la petición y esta ha sido
             * correcta
             * */
            if (data == 'noLogin') window.location.href = '<?php echo base_url('ingresar') ?>';
            $(".respuesta").html(data);
            $("#message").val('');
            $("#password").val('');
            setTimeout(newMsg(), 1000);

          },
          error: function(data) {
            /*
             * Se ejecuta si la peticón ha sido erronea
             * */
            txt = "<span class=\"content-msg\">No pudimos enviar tu mensaje :(</span>";
            $(".respuesta").html(txt);
          }
        });
        // Nos permite cancelar el envio del formulario
        return false;
      });
    });
  </script>
<?php } ?>


<?php echo $footer; ?>