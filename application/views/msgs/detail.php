<?php echo $header; ?>
   <section class="padding-sct">

    <!--pre>
    <?php #print_r($mensajes); ?>
   </pre-->

    <div class="container wrap">
        <div class="row titulos">Inbox</div>
    </div>

   	<?php if($mensajes){ ?>

      <div class="container wrap">
        <div class="row">

         <?php 

         foreach ($mensajes as $key => $value) {

          $cliente_name     = $value["cliente_name"];
          $cliente_idK      = $value["cliente_idK"];
          $message          = $value["message"];
          $token            = $value["token"];
          $created          = $value["created"];
          $ischeck          = $value["ischeck"];
          $course_name      = $value["course_name"];
          $course_id        = $value["course_id"];
          $responses        = $value["responses"];
          $message_id        = $value["message_id"];
          $class            = "";
          $marca            = "";

          if ($ischeck) {
              $class = "checked";
          } 
         ?>
      


        <?php }?>

         <div class="col-md-8 content-detail mensaje">
                  
          <h1>Contacto para curso <?php echo $course_name ;?></h1>

          <span><strong>Mensaje enviado a <?php echo $cliente_name; ?>,  el <?php echo $created ; ?>:</strong> 
          </span>

          <p><?php echo $message; ?></p>

       </div>

      </div>


       <div class="row msg-list">

         <?php 

         foreach ($responses as $key => $value) {

          $message          = $value->content;
          $token            = $value->token;
          $created          = $value->created;
          $ischeck          = $value->ischeck;
         ?>
      
         <div class="col-md-8 content-detail mensaje">
                  
         <span><strong><?php echo $cliente_name; ?> respondió el <?php echo $created ; ?>:</strong></span> 
          <p>"<?php echo $message; ?>"</p>
       </div>
<?php }?>
      </div>

    </div>

    <div class="container msg-box">
            <div class="row">
        <div class="col-md-12">
          
           <div class="modal-body">
           <form action="<?php echo base_url("cmessages/sendMessage")?>" method="post" role="form" data-toggle="validator" id="contact-form" method="post">
                <div class="respuesta"></div>
                <div class="acount-group">
                  <label><strong>Envía una respuesta o consulta:</strong></label>
                  <textarea  id="mess" name="message" class="form-control" placeholder="Escribe tu consulta" required style="height: 200px;"></textarea>
                </div>
                <div class="form-group input-group-lg acount-group">
                  <label>Por seguridad, ingresa tu password:</label>
                  <input type="password" name="passUser" class="form-control" placeholder="Password" id="password"  required>
                </div>
                  <input type="hidden" name="toUser"  value="<?php echo $cliente_idK; ?>">
                  <input type="hidden" name="course" value="<?php echo $course_id; ?>">
                  <input type="hidden" name="idm" value="<?php echo $message_id; ?>">
                  <input type="hidden" name="subject" value="Consulta curso <?php echo $course_name; ?>">
                <button type="submit" class="btn btn-primary" id="enviar-form">Enviar contacto >></button>
            </form>
      </div>

        </div>
      </div>

    </div>

      </div>
    <?php }else{ ?>
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <p class="msg-results">No encontramos ningún mensaje o contacto realizado.</p>
            </div>
          </div>
        </div>
  <?php } ?>
</section>

<script>
$(document).ready(function () {

      function borrar(){
        var txt = $.trim($("#mess").val());
        var html = "<div class=\"col-md-8 content-detail mensaje\"><span><strong>Demo de Ventas respondiste hace pocos segundos...</strong></span><p>\""+txt+"\"</p></div>";
                $('.content-msg').fadeOut("slow", function(){
                   $(".msg-list").append(html);
                   $.trim($("#mess").val(''));
                });
      }

    $("#contact-form").bind("submit",function(){
        // Capturamnos el boton de envío
        var btnEnviar = $("#enviar-form");
        $.ajax({
            type: $(this).attr("method"),
            url: $(this).attr("action"),
            data:$(this).serialize(),
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                txt = "<span class=\"content-msg\">Enviando...</span>";
                $(".respuesta").html(txt);
                btnEnviar.val("Enviando"); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Enviar formulario");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
                /*
                * Se ejecuta cuando termina la petición y esta ha sido
                * correcta
                * */
                $(".respuesta").html(data);
                $("#message").val('');
                $("#password").val('');
                setTimeout(borrar, 1000);

            },
            error: function(data){
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


<?php echo $footer; ?>