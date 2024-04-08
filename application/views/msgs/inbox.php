<?php echo $header; ?>
    <?php if($mensajes){ ?>

     <section class="section-notice">
        <div class="container">
          <div class="row notice">
            <div class="col-md-12">
              <p class="msg-results">Hemos encontrado <strong><?php echo $qty; ?></strong> mensajes. Puedes seleccionar revisar cada uno:</p>
            </div>
          </div>
        </div>
      </section>
    <?php } ?>

   <section class="padding-sct">

   <!-- pre>
   	 	<?php #print_r($mensajes); ?>
   </pre -->

      <div class="container wrap">
        <div class="row titulos">Inbox</div>
      </div>

   	<?php if($mensajes){ ?>

      <div class="container wrap">

         <?php 

         foreach ($mensajes as $key => $value) {

          $token    		    = $value["token"];
          $cliente_name     = $value["cliente_name"];
          $cliente_idK      = $value["cliente_idK"];
          $message          = $value["message"];
          $token            = $value["token"];
          $created          = $value["created"];
          $ischeck          = $value["ischeck"];
          $qty_responses    = $value["qty_responses"];
          $course_name      = $value["course_name"];
          $qty_messages     = $value["qty_messages"];
          $class            = "";
          $marca            = "";

          if ($ischeck) {
              $class = "checked";
          }

       

         ?>
      <a href="<?php echo base_url('leer/'.$token); ?>" class="row inbox <?php echo $class; ?>">

        

        <div class="col-md-2 separador">
            <?php echo $cliente_name; ?> (<?php echo $qty_responses ?>)
        </div>

        <div class="col-md-3 separador">
            <? echo $course_name;  ?>
        </div>

        <div class="col-md-6 separador">
            <p><?php echo $message ?>...</p>
        </div>

        <div class="col-md-1 separador fecha">
            <?php echo $created; ?> 
        </div>
      </a>


        <?php }?>
        <a href="<?php echo base_url(); ?>" class="content button form">Volver al home de cursos >></a>
        

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
        <a href="<?php echo base_url(); ?>" class="content button form">Volver al home de cursos >></a>
        </div>
  <?php } ?>

</section>

<script type="text/javascript">
      
        $( "#go" ).click(function() {
          $( "#buscar" ).submit();
        });

    </script>


<?php echo $footer; ?>