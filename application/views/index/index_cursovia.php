<?php echo $header; ?>

<?php 

    $ipuser = $_SERVER['REMOTE_ADDR']; 
    $dev = ( $ipuser == '191.113.23.186' ) ? true : false;

    // print_r($paid_banner_courses);

?>



<!-- <section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
      </div>
    </div>
  </div>
</section> -->
<form action="<?php echo base_url()?>" method="get" autocomplete="off" id="buscar">
    <section class="slide">
        <div class="container top">
            <div class="row">
                <div class="col-md-12 img" style="padding-top: 22px;">
                    <img src="<?php echo base_url("public/imgs/logo_cursovia-fiwh.svg") ?>" />
                </div>
            </div>
            <div class="row opacity-bkg">
                <div class="col-md-12" style="padding-top: 10px" ;>
                    <input type="text" class="buscar" style="text-align: center"
                        placeholder="¿Qué quieres aprender hoy?" name="buscar" id="SearchBar" />
                </div>
                <div class="col-md-12">
                    <a href="#" class="button m-top" id="go">Buscar</a>
                </div>
            </div>
        </div>
    </section>
</form>
</section>

<?php if($courses){ ?>

<main class="padding-sct">
    <div class="container wrap">


        <?php if($paid_banner_courses){ 

// Array ( [0] => stdClass Object ( [course_id] => 2926 [name] => Administración de empresas [slug] => administracion-de-empresas [hour] => [code] => [description] => [resume] => [id] => 48 [cursovia_description] => Curso de Administración [cursovia_target] => Personas naturales, estudiantes, microempresarios. [cursovia_learn_objective] => Entregar herramientas avanzadas de administración a emprendedores. [cursovia_structure] => 0 [cursovia_sence_code] => 0 [cursovia_elearning] => 1 [cursovia_inRoom] => 1 [cursovia_from] => 2024-03-31 17:05:48 [cursovia_tothe] => 2024-07-31 18:06:09 [cursovia_forever] => 1 [cursovia_isbanner] => 1 [cursovia_banner_url] => /public/cliente/uploads/478/02_660869908593e.jpg [cursovia_ispaid] => 1 [cursovia_order] => 0 [cursovia_url] => https://www.rald.cl [cursovia_utm_source] => cursovia [cursovia_utm_medium] => top_banner [cursovia_utm_campaign] => DEMO-CAMPANA [cursovia_utm_term] => administracion [cursovia_utm_content] => curso-de-administracion-demo [cursovia_status] => 1 [token] => TOKENPRUEBA01 [promotional_video] => [cursovia_price] => 0 [created] => 0000-00-00 00:00:00 [client_name] => Cursovia [client_logo] => /public/cliente/uploads/478/logokampusrojo_6407be60d72e2.png [client_id] => 478 ) )
            ?>
        <div class="row">
            <div class="col-md-12 slider_container" style="margin-top:-90px">
                <div class="slider">
                    <?php foreach ($paid_banner_courses as $key => $paidBanner) {
                    $pb_token   =   $paidBanner->token;
                    $pb_image   =   base_url().$paidBanner->cursovia_banner_url;
                    $pb_name    =   $paidBanner->name;

                ?>
                    <div>
                        <a href="<?php echo base_url('link-externo/'.$pb_token); ?>" target="_blank"
                            title="<?php echo $pb_name ?>">
                            <img src="<?php echo $pb_image ?>" /></a>
                    </div>

                    <?php } ?>

                </div>



            </div>
        </div>
        <?php } ?>


        <script>
        $(".target01").on("click", function() {
            alert("Handler for `click` called.");
        });
        </script>


        <div class="row grid" id="cursos-container">

            <?php 

      $cuantos_registros = count($courses);
      $cuantas_paginas = ceil($cuantos_registros/10);


        //$courses = array_slice( $courses, 0, 25 );

        foreach ($courses as $key => $value) {

        $image        = $value["image_int"];
        $name         = stot($value["name"]);
        $client_name  = stot($value["client_name"]);
        $client_slug  = $value["client_slug"];
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

          if(!$image){
            $noimage = array(1,2,3,4,5,6,7,8);
            $noimage = array_rand($noimage, 1);
            $image = "https://cursovia.com/public/imgs/nophoto0".$noimage.".jpg";
          }else{
            $image = "https://w3.kampusproject.com/".$image;
          }
          
           $hover = array('one','two','thr','fou');
           // var_export ($hover);
           $hover = $hover[array_rand($hover)];

         ?>

            <div class="course col-md-4 courses-box grid-item <?php echo $marca; ?>">
                <div class="content <?php echo $class; ?> <?php echo $hover; ?>">
                    <div class="datos">
                        <a href="<?php echo base_url('mostrar/'.$slug); ?>">
                            <h1><?php echo $name;?></h1>
                        </a>
                        <a href="<?php echo base_url($client_slug); ?>">
                            <h2 class="client-name"><?php echo $client_name; ?></h2>
                        </a>
                    </div>
                    <?php if($price!='Cotizar'){ ?>
                    <a href="<?php echo base_url('mostrar/'.$slug); ?>"><span
                            class="price"><?php echo formateaMoneda($price); ?></span></a>
                    <?php }else{ ?>
                    <a href="<?php echo base_url('mostrar/'.$slug); ?>"><span
                            class="price"><?php echo $price; ?></span></a>
                    <?php } ?>
                    <a href="<?php echo base_url('mostrar/'.$slug); ?>">
                        <div class="imgViewer"
                            style="background:url('<?php echo $image; ?>') no-repeat; background-size: cover;"></div>
                    </a>



                    <div class="row share-menu">

                        <div class="col-3" style="padding-left: 0;">
                            <i class="fas fa-heart fav <?php echo $class; ?>" data-idcurso="<?php echo $idcurso; ?>">
                                <span style="font-family:arial; font-size: 12px;"
                                    id="fav-<?php echo $idcurso; ?>"><?php echo $total_favorites; ?></span>
                            </i>
                        </div>

                        <div class="col-9 share-bar index">

                            <span style="margin-right: 10px">Compartir en:</span>
                            <span>

                                <ul>
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo url_actual("mostrar")?><?php echo $slug?>"
                                            style="background-image: url('public/imgs/facebook.svg');"
                                            target="_blank"></a></li>
                                    <!--li><a href="#" style="background-image: url('/public/imgs/linkedin.svg');"></a></li-->
                                    <!--li><a href="#" style="background-image: url('/public/imgs/twitter.svg');"></a></li-->
                                    <li><a href="https://api.whatsapp.com/send?text=Hola!%20te%20Env%C3%ADo%20este%20interesante%20link%20<?php echo url_actual("mostrar")?><?php echo $slug; ?>"
                                            style="background-image: url('public/imgs/whatsapp.svg');"
                                            target="_blank"></a></li>
                                </ul>
                            </span>
                        </div>


                    </div>
                </div>


            </div>

            <?php }?>
        </div>


        <div id="load-more-courses" class="content button" style="display:none;">Ver más cursos </div>
        <div class="subir" style="display:none;"><a class="fa-arrow-up"></a></div>

    </div>
</main>
<?php }else{ ?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p class="msg-results">No encontramos ningún curso con "<?php echo $this->session->flashdata('txt'); ?>"
                    :'(</p>
            </div>
        </div>
        <div class="content"><a href="/" class="button">Ver todos los cursos >></a></div>





    </div>
</section>



<?php } ?>

<script>
$("#go").click(function() {
    $("#buscar").submit();
    console.log("xlixkkk");
});
</script>

<?php if($courses){ ?>

<script type="text/javascript" src="<?php echo base_url('public/js/functions-fav.js?'.rand()); ?>"></script>
<script>
var clientSlug = '';
var totalCourses = <?php echo $total_courses?>;
var offset = <?php echo count($courses)?>
</script>
<script type="text/javascript" src="<?php echo base_url('public/js/load-more-courses.js')?>"></script>
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
$(document).ready(function() {
    var $subir = $(".subir");

    $(window).scroll(function() {
        // Obtener la posición vertical del scroll
        var scrollPos = $(window).scrollTop();

        // Mostrar el botón si la posición del scroll es mayor a 500px (ajusta este valor según tus necesidades)
        if (scrollPos > 500) {
            $subir.fadeIn();
        } else {
            $subir.fadeOut();
        }
    });

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
});
</script>

<?php } ?>

<?php echo $footer; ?>