<!doctype html>

<style>
    .no-view-logo {
        display: none;
    }
</style>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bitter:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url("/public/js/jquery-3.5.1.min.js"); ?>" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>


    <!-- carrousel bxslider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>


    <script src="https://kit.fontawesome.com/33f877f153.js" crossorigin="anonymous"></script>


    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <?php
    $domain = $_SERVER['SERVER_NAME'];
    $baseUrl = ($domain == 'cursovia.local') ? 'https://cursovia.local/Cursovia' : $domain; 
    echo $prueba ?>
    <script>
        var baseURL = '<?php echo $baseUrl ?>';
    </script>
   

    <meta name="facebook-domain-verification" content="kabvm4o52mle3toe5a7wh5k6qmunah" />

    <link rel="icon" type="image/png" href="<?php echo base_url("public/imgs/ca.png") ?>" />

    <link rel="stylesheet" href="<?php echo base_url("public/css/cursovia.css") ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <title>Cursovia</title>

    <script>
        $(document).ready(function() {
            $(".slider").bxSlider({
                infiniteLoop: true,
                // slideWidth: 600,
                // randomStart: true,
                // tickerHover: true,
                touchEnabled: false,
                controls: false,
                auto: true,
                autoDelay: 20,
                slideWidth: 363,
                minSlides: 1,
                maxSlides: 3,
                slideMargin: 6,
                speed: 1500,
                shrinkItems: true,
                captions: true,
                autoHover: true
            });
        });
    </script>

</head>

<body>

    <video class="home" src="<?php echo base_url("/public/video/RALD_video.mp4") ?>" autoplay="true" muted="true" loop="true"></video>

    <section class="barra sect">
        <div class="container">
            <div class="row">
                <div class="col-md-6"><a href="<?php echo base_url(); ?>" class="no-view-logo"><img src="<?php echo base_url("public/imgs/logo_cursovia-fiwh.svg") ?>" class="Logotipo curso"></a></div>
                <div class="col-md-6">
                    <ul class="menu">
                        <!--li class="header-button"><a href="/ingresar" class="fa-lock-menu">¿Qué es Cursovia?</a></li-->

                        <?php $validated = ($sessionuser) ? $sessionuser["validated"] : false;
                        if ($validated) { ?>
                            <li class="header-button"><a href="<?php echo base_url("cursos-favoritos") ?>" class="fa-heart-menu white">Ver Favoritos <span id="cantfav">(<?php echo $cant_favorites; ?>)</span></a></li>
                            <li class="header-button"><a href="javascript:void(0);" class="fa-user white"><?php echo $sessionuser["full_name"]; ?></a>
                                <ul class="submenu">
                                    <li><a href="<?php echo base_url("cuenta") ?>" class="fa-gear">Mi cuenta</a></li>
                                    <li><a href="<?php echo base_url("inbox") ?>" class="fa-msg">Mis mensajes</a></li>
                                    <li><a href="<?php echo base_url("salir") ?>"><i class="icon fa-solid fa-right-from-bracket"></i>Logout</a></li>
                                </ul>

                            </li>
                        <?php } else { ?>
                            <li class="header-button"><a href="<?php echo base_url("ingresar") ?>" class="white"><i class="icon fa-solid fa-right-to-bracket"></i>Login / Registro</a></li>
                        <?php } ?>
                    </ul>


                </div>

            </div>
        </div>
    </section>