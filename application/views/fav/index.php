<?php echo $header; ?>
<?php if ($courses) { ?>


  <section class="section-notice">
    <div class="container">
      <div class="row notice">
        <div class="col-md-12">
          <p class="msg-results">Hemos encontrado <strong><?php echo $cant_favorites; ?></strong> cursos marcados como favoritos:</p>
        </div>
      </div>
    </div>
  </section>

<?php } ?>



<section class="padding-sct">

  <div class="container wrap">
    <div class="row titulos">Favs</div>
  </div>

  <?php if ($courses) { ?>


    <!-- <form action="" method="post"> -->
    <div class="container wrap">
      <?php

      foreach ($courses as $key => $value) {

        $image            = $value["image_int"];
        $name             = stot($value["name"]);
        $client_name      = stot($value["client_name"]);
        $client_slug      = $value["client_slug"];
        $slug             = $value["slug"];
        $idcliente        = $value["client_id"];
        $idcurso          = $value["id"];
        $fav              = $value["favorite"];
        $total_favorites  = $value["total_favorites"];
        $class            = "";
        $marca            = "";

        if ($fav) {
          $class = "active";
          $marca = "isFav";
        }

        if (!$image) {
          $noimage = array(1, 2, 3, 4, 5, 6, 7, 8);
          $noimage = array_rand($noimage, 1);
          $image = base_url("public/imgs/nophoto0") . $noimage . ".jpg";
        } else {
          $image = URL_KAMPUS . $image;
        }
      ?>

        <div class="row favorite fav-hover">


          <div class="col-sm-9">
            <div class="content <?php echo $class; ?>">

              <label class="custom-radio-checkbox">
                <!-- Input oculto -->
                <!--input class="custom-radio-checkbox__input" type="checkbox" name="cheched_courses" value="<?php echo $idcurso; ?>"-->
                <!-- Imagen en sustitucion -->
                <!-- <span class="custom-radio-checkbox__show custom-radio-checkbox__show--checkbox"></span> -->
                <!-- Texto -->
                <span class="custom-radio-checkbox__text"><?php echo $name; ?></span>
              </label>
              <a class="client-name" href="<?php echo base_url($client_slug) ?>">
                <h2><?php echo $client_name ?></h2>
              </a>
            </div>
          </div>
          <div class="col-sm-3">
            <a href="mostrar/<?php echo $slug; ?>" class="button-primary center">Ver >></a>
          </div>
        </div>
      <?php } ?>
      <br>
      <a href="<?php echo base_url(); ?>" class="button-primary center">Volver al home de cursos >></a>

    </div>
    <!-- </form> -->
    </div>
  <?php } else { ?>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <p class="msg-results">No encontramos ningún curso marcado como favorito.</p>
        </div>
      </div>
      <a href="<?php echo base_url() ?>" class="button-primary center">Ver todos los cursos >></a>
    </div>
  <?php } ?>
</section>

<script type="text/javascript">
  $("#go").click(function() {
    $("#buscar").submit();
  });
</script>


<?php echo $footer; ?>