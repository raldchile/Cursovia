<?php echo $header; ?>

<!--pre>
  <?php #print_r($datos); ?>
</pre-->

 <section  class="slide inner">
      <div class="container inner-top">
      <div class="row ">
        <div class="col-md-12 content-detail">

                 <form action="<?php echo base_url("cmessages/getTheMessage")?>" method="post" role="form" data-toggle="validator" id="contact-form">
               <div class="acount-group">
                  <span><strong>De: </strong><?php echo $datos[0]["full_name"];?></span>
                  <span><strong>Para: </strong><?php echo $datos[0]["client_name"];?></span>
                  <span><strong>Asunto:</strong> Consulta curso <?php //echo $name; ?></span>
                  <span><strong>Mensaje / Consulta:</strong> <?php echo trim($datos[0]["content"]);?></span>
                </div>
                <div class="respuesta"></div>
                <div class="acount-group">
                  <label><strong>Responde aquí: </strong></label>
                  <textarea name="message" class="form-control" placeholder="Escribe tu respuesta" required style="height: 200px;" id="message"></textarea>
                </div>
                   <input type="hidden" name="token"  value="<?php echo $datos[0]["token"];?>">
                   <input type="hidden" name="idm" value="<?php echo $datos[0]["message_id"];?>">
                   <input type="hidden" name="subject" value="RE: Consulta curso">
                <button type="submit" class="btn btn-primary" id="enviar-form">Enviar respuesta >></button>
            </form>

        </div>
    </div>

    
  </section>

  <script type="text/javascript">
      
    $(document).ready(function () {

      function cerrar(){
                $('#cerrar').trigger('click');
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
                $(".respuesta").html("Enviando");
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
                setTimeout(cerrar, 1000);

            },
            error: function(data){
                /*
                * Se ejecuta si la peticón ha sido erronea
                * */
                alert("Problemas al tratar de enviar el formulario: " + data);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    });
});

</script>



<?php echo $footer; ?>
