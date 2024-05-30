<?php echo $header; ?>

<?php

$ipuser = $_SERVER['REMOTE_ADDR'];
$dev = ($ipuser == '191.113.23.186') ? true : false;

$client_id = $client['id'];
$client_name = $client["name"];
$client_slug = $client["slug"];
$client_alias = $client['alias'];
$client_description = $client['description'];
$client_phone = $client['phone'];
$client_email = $client['email'];
$client_color_first = $client['color_first'];
$client_color_second = $client['color_second'];
$client_background = $client['background'];
$client_logo = $client['logo'];
$client_profile_img = $client['profile_img'];
$client_profile_cover = $client['profile_cover'];
$client_profile_description = $client['profile_description'];
$client_favicon = $client['favicon'];
$client_mobileicon = $client['mobileicon'];
$client_email_support = $client['email_support'];
$client_address = $client['address'];

if (!$client_profile_cover) {
    $noimage = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    $random_key = array_rand($noimage, 1);
    $noimage_value = $noimage[$random_key];
    $client_profile_cover = base_url("/public/imgs/profile-cover/profile-Cover-") . $noimage_value . ".jpg";    
} else {
    $image = URL_KAMPUS . $image;
}

$client_profile_img = ($client_profile_img) ? URL_KAMPUS . $client_profile_img : base_url('public/imgs/ca_perfil_2.png');

// print_r($paid_banner_courses);

?>

<!-- Imagen de portada -->
<section class="profile-cover" style="background-image: url('<?php echo $client_profile_cover ?>')">
    <div class="profile-image">
        <img src="<?php echo $client_profile_img ?>" alt="Imagen perfil">
        <h1><?php echo $client_alias ? $client_alias : $client_name ?></h1>
    </div>
</section>
<main class="padding-sct client-profile-main">
    <section class="container search-client-courses">
        <div class="row justify-content-end align-items-center">
            <div class="col-7 client-searchbar">
                <form action="<?php echo base_url($client_slug) ?>" method="get" autocomplete="off" id="buscar">
                    <input type="text" style="text-align: center" placeholder="¿Qué quieres aprender con <?php echo (($client_alias) ? $client_alias : $client_name)?>?" name="buscar" id="SearchBar" />
                </form>
            </div>
            <div class="col-sm-2">
                <div id="go" class="client-search-button">Buscar</div>
            </div>
        </div>
    </section>
    <section class="container">
        <div class="row">
            <div class="col-md-3 description-col">
                <h1 class="description-title">
                    Descripción
                    <i class="description-button fa-regular"></i>
                </h1>
                <p class="description">
                    <strong><?php if (isset($description)) echo $description;
                            else echo (($client_alias) ? $client_alias : $client_name) ?></strong>
                    no ha puesto una descripción aún
                </p>
            </div>
            <div class="col-md-9 col-courses">
                <?php if ($courses) { ?>
                    <div class="container wrap">
                        <div class="row grid courses-list" id="cursos-container">
                            <?php

                            $cuantos_registros = count($courses);
                            $cuantas_paginas = ceil($cuantos_registros / 10);


                            //$courses = array_slice( $courses, 0, 25 );

                            foreach ($courses as $key => $value) {

                                $image        = $value["image_int"];
                                $name         = stot($value["name"]);
                                $client_name  = stot($value["client_name"]);
                                $slug         = $value["slug"];
                                $description  = $value["description"];
                                $idcliente    = $value["client_id"];
                                $idcurso      = $value["id"];
                                $fav          = $value["favorite"];
                                $price        = $value["price"];
                                $class        = "";
                                $marca        = "";
                                $total_favorites  = $value["total_favorites"];

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

                                $hover = array('one', 'two', 'thr', 'fou');
                                // var_export ($hover);
                                $hover = $hover[array_rand($hover)];

                            ?>

                                <div class="course col-md-4 courses-box grid-item <?php echo $marca; ?>">
                                    <div class="content <?php echo $class; ?> <?php echo $hover; ?>">
                                        <div class="datos">
                                            <a href="<?php echo base_url('mostrar/' . $slug); ?>">
                                                <h1><?php echo $name; ?></h1>
                                            </a>
                                            <a href="<?php echo base_url($client_slug); ?>">
                                                <h2 class="client-name"><?php echo $client_alias ? $client_alias : $client_name ?></h2>
                                            </a>
                                        </div>
                                        <?php if ($price != 'Cotizar') { ?>
                                            <a href="<?php echo base_url('mostrar/' . $slug); ?>"><span class="price"><?php echo formateaMoneda($price); ?></span></a>
                                        <?php } else { ?>
                                            <a href=" <?php echo base_url('mostrar/' . $slug) ?>"><span class="price"><?php echo $price; ?></span></a>
                                        <?php } ?>
                                        <a href=" <?php echo base_url('mostrar/' . $slug); ?>">
                                            <div class="imgViewer" style="background:url('<?php echo $image; ?>') no-repeat; background-size: cover;">
                                            </div>
                                        </a>

                                        <div class="row share-menu">
                                            <div class="col-md-3" style="padding-left: 0;">
                                                <i class="fas fa-heart fav <?php echo $class; ?>" data-idcurso="<?php echo $idcurso; ?>">
                                                    <span style="font-family:arial; font-size: 12px;" id="fav-<?php echo $idcurso; ?>"><?php echo $total_favorites; ?></span>
                                                </i>
                                            </div>
                                            <div class="col-md-9 share-bar index">
                                                <span style="margin-right: 10px">Compartir en:</span>
                                                <span>
                                                    <ul>
                                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo url_actual("mostrar") ?><?php echo $slug ?>" target="_blank"><i class="fa-brands fa-square-facebook"></i></a>
                                                        </li>
                                                        <!--li><a href="#" style="background-image: url('/public/imgs/linkedin.svg');"></a></li-->
                                                        <!--li><a href="#" style="background-image: url('/public/imgs/twitter.svg');"></a></li-->
                                                        <li><a href="https://api.whatsapp.com/send?text=Hola!%20te%20Env%C3%ADo%20este%20interesante%20link%20<?php echo url_actual("mostrar") ?><?php echo $slug; ?>" target="_blank"><i class="fa-brands fa-square-whatsapp"></i></a>
                                                        </li>
                                                    </ul>
                                                </span>
                                            </div>


                                        </div>
                                    </div>


                                </div>

                            <?php } ?>
                        </div>
                        <div id="load-more-courses" class="load-more-button" style="display:none;">Ver más cursos </div>
                        <div class="subir" style="display:none;"><i class="fa-solid fa-arrow-up"></i></div>
                    </div>
                <?php } else { ?>
                    <div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="msg-results">Lo sentimos no encontramos cursos de
                                        <?php echo (($client_alias) ? $client_alias : $client_name) ?>"
                                        :'(</p>
                                </div>
                            </div>
                            <div>
                            <a  class="button-primary center" href=" <?php echo base_url('/'.$client_slug) ?>" class="button">Ver todos los cursos de <?php echo (($client_alias) ? $client_alias : $client_name)?> >></a>
                            </div>

                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>


<script>
    $("#go").click(function() {
        $("#buscar").submit();
    });
</script>

<?php if ($courses) { ?>

    <script type="text/javascript" src="<?php echo base_url('public/js/functions-fav.js?' . rand()); ?>"></script>
    <script>
        var clientSlug = '<?php echo $client_slug ?>';
        var totalCourses = <?php echo $total_courses ?>;
        var offset = <?php echo count($courses) ?>;
        var buscar = '<?php echo $this->session->flashdata('txt'); ?>';
    </script>
    <script type='text/javascript'>
        var $subir = $(".subir");

        $subir.click(function() {
            // Obtener la posición superior de la barra de búsqueda
            var searchBarTop = $("#SearchBar").offset().top;

            // Calcular la posición a la que deseas desplazarte
            var scrollToPos = searchBarTop -
                200; // Resta una cantidad de píxeles para dejar la barra en el medio

            // Hacer scroll suavemente a la posición calculada
            $("html, body").animate({
                    scrollTop: scrollToPos,
                },
                800
            ); // Duración de la animación en milisegundos
        });

        $(window).scroll(function() {
            // Obtener la posición vertical del scroll
            var scrollPos = $(window).scrollTop();

            // Mostrar el botón si la posición del scroll es mayor a 500px (ajusta este valor según tus necesidades)
            if (scrollPos > 500) {
                var windowWidth = $(window).width();
                if (windowWidth > 768) {
                    $subir.fadeIn();
                }
            } else {
                $subir.fadeOut();
            }
        });


        var $descriptionTitle = $(".description-title");
        var $description = $('.description');
        var $descriptionButton = $(".description-button");
        var $descriptionCol = $(".description-col");

        $descriptionCol.click(function() {

            if ($descriptionButton.hasClass("fa-arrow-turn-up")) {
                $description.fadeIn();
                $descriptionButton.removeClass("fa-arrow-turn-up");
                $descriptionButton.addClass("fa-arrow-turn-left-down");
            } else if ($descriptionButton.hasClass("fa-arrow-turn-left-down")) {
                $description.fadeOut();
                $descriptionButton.removeClass("fa-arrow-turn-left-down");
                $descriptionButton.addClass("fa-arrow-turn-up");
            }
        });

        function responsive_profile() {
            var $searchButton = $('.client-search-button');
            var $searchBar = $(".client-searchbar");;
            var $course = $(".course");

            var windowWidth = $(window).width();

            if (windowWidth < 768) {
                $searchButton.text('');
                $subir.fadeOut();
                $description.fadeOut();
                $searchBar.removeClass("col-7");
                $searchBar.addClass("col-sm-10");
                $descriptionButton.addClass("fa-arrow-turn-up");
                $searchButton.addClass("fa-light fa-magnifying-glass");
            } else if (windowWidth >= 768 && windowWidth < 1024) {
                $course.removeClass("col-md-4");
                $course.addClass("col-md-6");
                $searchBar.removeClass("col-sm-10");
                $searchBar.addClass("col-7");
                $descriptionButton.removeClass("fa-arrow-turn-up");
                $descriptionButton.removeClass("fa-arrow-turn-left-down");
            } else {
                $searchButton.removeClass("fa-light fa-magnifying-glass");
                $searchBar.removeClass("col-sm-10");
                $course.removeClass("col-md-6");
                $course.addClass("col-md-4");
                $searchBar.addClass("col-7");
                $searchButton.text('');
                $searchButton.text('BUSCAR');
            }

        }
        $(document).ready(function() {
            responsive_profile();

            $(window).resize(responsive_profile);

        });
    </script>
    <script type="text/javascript" src="<?php echo base_url('public/js/load-more-courses.js?' . rand()) ?>"></script>
    <script type='text/javascript'>
        $(window).on('load', function() {
            loadMasonry();
        });

        function loadMasonry() {

            var elem = document.querySelector('.grid');
            var msnry = new Masonry(elem, {
                itemSelector: '.grid-item',
                columnWidth: 270
            });

            var msnry = new Masonry('.grid', {});

        }
    </script>

    <script type="text/javascript">
        var page = 1;
        // var total_pages = <?php #print $count
                                ?>;
        var total_pages = <?php echo $cuantas_paginas ?>;

        function loadMore(page) {
            var data = "";

            $(".grid").append(data);
            loadMasonry();

        }

        (function($) {

            $(document).ready(

                function() {

                    $("#SearchBar").focus();

                    // Hacemos que el sitio haga scroll down hasta poner el foco en el buscador.

                    // $('html, body').animate( {
                    //   scrollTop : 60
                    // }, 800, function(){

                    //   $( "#SearchBar" ).focus();

                    // });


                    // Comprobar si estamos, al menos, 100 px por debajo de la posición top
                    // para mostrar o esconder el botón
                    $(window).scroll(function() {

                        if ($(this).scrollTop() > 100) {

                            $('.scroll-to-top').fadeIn();

                        } else {

                            $('.scroll-to-top').fadeOut();

                        }

                    });

                    // al hacer click, animar el scroll hacia arriba
                    $('.scroll-to-top').click(function(e) {

                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: 0
                        }, 800);

                    });

                });

        })(jQuery);
    </script>

<?php } ?>

<?php echo $footer; ?>