<?php echo $header; ?>
  <?php if($courses){ ?>


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

  <?php if($courses){ ?>


    <form action="" method="post">
      <div class="container wrap">
       <?php 

         foreach ($courses as $key => $value) {

          $image            = $value["image_int"];
          $name             = stot($value["name"]);
          $client_name      = stot($value["client_name"]);
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

          if(!$image){
            $noimage = array(1,2,3,4,5,6,7,8);
            $noimage = array_rand($noimage, 1);
            $image = "https://cursovia.com/public/imgs/nophoto0".$noimage.".jpg";
          }else{
            $image = "https://w3.kampusproject.com/".$image;
          }
         

         ?>

      <div class="row favorite">

   
        <div class="col-md-10">
          <div class="content <?php echo $class; ?>">

            <label class="custom-radio-checkbox">
                  <!-- Input oculto -->
                  <!--input class="custom-radio-checkbox__input" type="checkbox" name="cheched_courses" value="<?php echo $idcurso; ?>"-->
                  <!-- Imagen en sustitucion -->
                  <span class="custom-radio-checkbox__show custom-radio-checkbox__show--checkbox"></span>
                  <!-- Texto -->
                  <span class="custom-radio-checkbox__text"><?php echo $name; ?></span>     
          </label>
          <h2><?php echo $client_name; ?></p>
          <!--h2><a href="/mostrar/<?php echo $slug; ?>"> <?php echo $client_name; ?></h2></p-->
        </div>
        </div> 
        <div class="col-md-2 a-btn">
          <a href="mostrar/<?php echo $slug; ?>" class="ver_button">Ver >></a>
        </div>

    </div>


        <?php }?>
        <a href="<?php echo base_url(); ?>" class="content button form">Volver al home de cursos >></a>

      </div>
    </form>
      </div>
    <?php }else{ ?>
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <p class="msg-results">No encontramos ningún curso marcado como favorito.</p>
            </div>
          </div>
        <div class="content"><a href="/" class="button form">Ver todos los cursos >></a></div>
        </div>
  <?php } ?>
</section>

<script type="text/javascript">
      
        $( "#go" ).click(function() {
          $( "#buscar" ).submit();
        });

    </script>


<?php echo $footer; ?>