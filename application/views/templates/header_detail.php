<!doctype html>
<html lang="en">

<?php 
          $image        = $courses[0]["image_int"];
          
          if($image){
          $image        = "https://w3.kampusproject.com". $image;
          }else{
          $image        = "public/imgs/ca.png";
          }
          $name         = stot($courses[0]["name"]);
          $description  = $courses[0]["description"];
          $client_name  = $courses[0]["client_name"];

#          print_r($courses);
         
?>


  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--     <meta property="og:url"                content="<?php echo url_actual(); ?>" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="<?php echo $name; ?>" />
    <meta property="og:description"        content="<?php echo $description; ?>" />
    <meta property="og:image"              content="<?php echo $image; ?>" /> -->

    <meta name="description" content="<?php echo $description; ?>"/>
    <meta property="og:type" content="www.cursovia.com" />
    <meta property="og:title" content="<?php echo $name; ?>" />
    <meta property="og:description" content="<?php echo $description; ?>" />
    <meta property="og:image" content="<?php echo $image; ?>" />
    <meta property="og:image:width" content="475" />
    <meta property="og:image:height" content="220" />
    <meta property="og:url" content="<?php echo url_actual(); ?>" />
    <meta property="og:site_name" content="<?php echo $client_name; ?> está en Cursovia" />
    <meta property="fb:app_id" content="361681531771993" />

    <link rel="icon" type="image/png" href="<?php echo base_url()?>/public/imgs/ca.png" />

    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

      <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url("/public/js/jquery-3.5.1.min.js"); ?>" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/168dbd124a.js" crossorigin="anonymous"></script>


    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

    <link rel="stylesheet" href="<?php echo base_url("public/css/cursovia.css") ?>">
    

    <title>Cursovia: <?php echo $name; ?></title>
  </head>
  <body>

    <video class="home" src="<?php echo base_url("public/video/RALD_video.mp4") ?>" autoplay="true" muted="true" loop="true"></video>
    

 <section class="barra">
      <div class="container">
        <div class="row" >
          <div class="col-md-6"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url()?> public/imgs/logo_cursovia-fiwh.svg" class="header-logo"></a></div>
          <div class="col-md-6">
              <ul class="menu">
                <?php if($sessionuser["validated"]){ ?>
                    <li class="header-button"><a href="<?php echo base_url() ?>/cursos-favoritos" class="fa-heart-menu white">Ver Favoritos <span id="cantfav">(<?php echo $cant_favorites; ?>)</span></a></li>
                    <li class="header-button"><a href="javascript:void(0);" class="fa-user white"><?php echo $sessionuser["full_name"]; ?></a>
                      <ul class="submenu">
                        <li><a href="<?php echo base_url() ?>/cuenta" class="fa-gear">Mi cuenta</a></li>
                        <li><a href="<?php echo base_url() ?>/inbox" class="fa-msg">Mis mensajes</a></li>
                        <li><a href="<?php echo base_url() ?>/salir" class="fa-sign-out-alt">Logout</a></li>
                      </ul>

                    </li>
                  <?php }else{ ?>
                    <li class="header-button"><a href="<?php echo base_url() ?>/ingresar" class="fa-lock-menu white">Login / Registro</a></li>
                <?php } ?>
              </ul>


          </div>
        
        </div>
      </div>
    </section>