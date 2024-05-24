<?php echo $header; ?>
<link rel="stylesheet" href="<?php echo base_url("public/css/inbox.css") ?>">
<?php if ($mensajes) { ?>

  <section class="section-notice">
    <div class="container">
      <div class="row notice">
        <div class="col-md-12">
          <p class="msg-results">Hemos encontrado <strong><?php echo $qty; ?></strong> mensajes. Puedes revisar cada uno:</p>
        </div>
      </div>
    </div>
  </section>
<?php } ?>

<section class="padding-sct">

  <div class="container">
    <div class="row titulos">Inbox</div>
  </div>

  <?php if ($mensajes) { ?>

    <div class="container">

      <?php

      usort($mensajes, function ($a, $b) {
        return strtotime($b['last_response']['created']) - strtotime($a['last_response']['created']);
      });

      foreach ($mensajes as $key => $value) {

        $token            = $value["token"];
        $cliente_name     = $value["cliente_name"];
        $cliente_idK      = $value["cliente_idK"];
        $client_validity      = $value["client_validity"];
        $message          = $value["message"];
        $token            = $value["token"];
        $created          = FechaToCastle($value["created"]);
        $ischeck          = $value["ischeck"];
        $unreadMsg        = $value["unreadMsg"];
        $last_response    = $value["last_response"];
        $course_name      = $value["course_name"];
        $class            = "";
        $marca            = "";

        if ($unreadMsg > 0) {
          $class = "unreadMsgs";
        }


        $validity = $client_validity ? '' : 'disabled';

        $client_name = stot($value['client']["name"]);
        $client_slug = $value['client']["slug"];
        $client_status    = $value['client']["status"];
        $client_expire = $value['client']["expire"];
        $client_alias = $value['client']['alias'];
        $client_profile_img = $value['client']['profile_img'];
        $client_profile_img = ($client_profile_img) ? URL_KAMPUS . $client_profile_img : base_url('public/imgs/ca_perfil_2.png');
      ?>

        <a href="<?php echo base_url('leer/' . $token); ?>" class="inbox <?php echo $class . ' ' . $validity ?>">

          <div class="client-name">
            <img src="<?php echo $client_profile_img ?>" alt="Imagen perfil">
            <p>
              <?php echo $client_alias ? $client_alias : $client_name ?>
            </p>
          </div>

          <div class="course">
            <p>
              Consulta: <?php echo $course_name;  ?>
            </p>
          </div>
          <?php if ($client_validity) {
            if ($last_response['from_id'] != $cliente_idK) { ?>
              <div class="last-msg">
                <p><i class="fa-solid fa-check-double" style="<?php echo ($last_response['ischeck'] == 2) ? 'color: #067aff' : 'color: #3b4a54' ?>"></i><?php echo $last_response['message'] ?></p>
              </div>
            <?php } else { ?>
              <div class="last-msg">
                <p><?php echo $last_response['message'] ?></p>
              </div>
            <?php }
          } else { ?>
            <div class="last-msg">
              <p>Lo sentimos, este cliente ya no forma parte de Cursovia</p>
            </div>
          <?php } ?>


          <div class="fecha">
            <?php if ($unreadMsg == 0) { ?>
              <p>
                <?php echo FechaToCastle($last_response['created']) ?>
              </p>
            <?php } else { ?>
              <div class="unread-msg">
                <?php echo ($unreadMsg == 1) ? $unreadMsg . ' mensaje nuevo' : $unreadMsg . ' mensajes nuevos' ?>
              </div>
              <p class=unread-msg>
                <?php echo FechaToCastle($last_response['created']) ?>
              </p>
            <?php } ?>
          </div>
        </a>


      <?php } ?>
      <a href="<?php echo base_url(); ?>" class="button-primary center">Volver al home de cursos >></a>



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
      <a class="button-primary center" href="<?php echo base_url(); ?>">Volver al home de cursos >></a>
    </div>
  <?php } ?>

</section>

<script type="text/javascript">
  $("#go").click(function() {
    $("#buscar").submit();
  });
</script>


<?php echo $footer; ?>