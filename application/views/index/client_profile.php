<?php echo $header; ?>

<?php 

    $ipuser = $_SERVER['REMOTE_ADDR']; 
    $dev = ( $ipuser == '191.113.23.186' ) ? true : false;

    $client_id = $client['id'];
    $client_name = stot($client["name"]);
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



<!-- Imagen de portada -->
<section class="profile-cover" style="background-image: url('public/imgs/nophoto05.jpg')">
    <div class="profile-image">
        <img src="public/imgs/whatsapp.svg" alt="">
        <h1><?php echo $client_name; ?></h1>
    </div>
</section>
<main class="padding-sct">
    <section class="container search-client-courses">
        <div class="row justify-content-end align-items-center">
            <div class="col-7 client-searchbar">
                <form action="<?php echo base_url($client_slug)?>" method="get" autocomplete="off" id="buscar">
                    <input type="text" style="text-align: center"
                        placeholder="¿Qué quieres aprender de <?php echo $client_alias?>?" name="buscar"
                        id="SearchBar" />
                </form>
            </div>
            <div class="col-2"><a href="#" class="client-search-button"
                    style="background:<?php echo $client_color_first?>" id="go">Buscar</a></div>
        </div>
    </section>
    <section class="container">
        <div class="row">
            <div class="col-md-3 client-profile-description">
                <h1>Descripción</h1>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Velit cupiditate dolorem sint animi
                perspiciatis necessitatibus. Nesciunt at enim blanditiis doloribus voluptatem sint eos, voluptatibus id
                debitis saepe vel accusamus quos.
                <?php echo $client_profile_description?>
            </div>
            <div class="col-md-9 col-courses">
                <?php if($courses){ ?>
                <div class="container wrap">
                    <div class="row grid courses-list" id="cursos-container">
                        <?php 
    
                        $cuantos_registros = count($courses);
                        $cuantas_paginas = ceil($cuantos_registros/10);
    
    
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
                                        <h1><?php echo $name; ?></h1>
                                    </a>
                                    <a href="<?php echo base_url($client_slug); ?>">
                                        <h2 class="client-name"><?php echo $client_name; ?></h2>
                                    </a>
                                </div>
                                <?php if($price!='Cotizar'){ ?>
                                <a href="<?php echo base_url('mostrar/'.$slug); ?>" class="price"
                                    style="color:<?php echo $client_color_second?>"><span><?php echo formateaMoneda($price); ?></span></a>
                                <?php }else{ ?>
                                <a href=" <?php echo base_url('mostrar/'.$slug)?>" class="price"
                                    style="color:<?php echo $client_color_second?>"><span><?php echo $price; ?></span></a>
                                <?php } ?>
                                <a href=" <?php echo base_url('mostrar/'.$slug); ?>">
                                    <div class="imgViewer"
                                        style="background:url('<?php echo $image; ?>') no-repeat; background-size: cover;">
                                    </div>
                                </a>

                                <div class="row share-menu">
                                    <div class="col-md-3" style="padding-left: 0;">
                                        <i class="fas fa-heart fav <?php echo $class; ?>"
                                            data-idcurso="<?php echo $idcurso; ?>">
                                            <span style="font-family:arial; font-size: 12px;"
                                                id="fav-<?php echo $idcurso; ?>"><?php echo $total_favorites; ?></span>
                                        </i>
                                    </div>
                                    <div class="col-md-9 share-bar index">
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
                    <div class="subir" ><a class="fa-arrow-up"></a></div>
                </div>
                <?php }
                else{ ?>
                <div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="msg-results">Lo sentimos no encontramos cursos de
                                    <?php echo $client_name?>"
                                    :'(</p>
                            </div>
                        </div>
                        <div class="content"><a href="<?php echo base_url()?>" class="button">Ver todos los cursos
                                >></a></div>

                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>


<?php if($courses){ ?>

<script type="text/javascript" src="<?php echo base_url('public/js/functions-fav.js'); ?>"></script>
<script>
var clientSlug = '<?php echo $client_slug?>';
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
var page = 1;
// var total_pages = <?php #print $count?>;
var total_pages = <?php echo $cuantas_paginas ?>;


// $(window).scroll(function() {
//     if($(window).scrollTop() + $(window).height() >= $(document).height()) {
//         page++;
//         if(page < total_pages) {
//           loadMore(page);
//         }
//     }
// });


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