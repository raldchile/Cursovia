<?php echo $header; ?>

<?php 

    $ipuser = $_SERVER['REMOTE_ADDR']; 
    $dev = ( $ipuser == '191.113.23.186' ) ? true : false;
    $client_name      = stot($courses[0]["client_name"]);

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


    <form action="<?php echo base_url() ?>" method="post" autocomplete="off" id="buscar">
    <section class="client-profile-picture">
      <div class="container top">

      <div class="row opacity-bkg">
        <div class="col-md-12" style="padding-top: 10px";>
          <h1 type="text" style="text-align: center; font-size: 50px; color: green;"><?php echo $client_name?> </h1>
        </div>
    </div>
  </section>
</form>

  <?php if($courses){ ?>

   <section class="padding-sct">
      <div class="container wrap">

        <?php if( $dev ) { ?>
        <?php if($paid_banner_courses){ 

// Array ( [0] => stdClass Object ( [course_id] => 2926 [name] => Administración de empresas [slug] => administracion-de-empresas [hour] => [code] => [description] => [resume] => [id] => 48 [cursovia_description] => Curso de Administración [cursovia_target] => Personas naturales, estudiantes, microempresarios. [cursovia_learn_objective] => Entregar herramientas avanzadas de administración a emprendedores. [cursovia_structure] => 0 [cursovia_sence_code] => 0 [cursovia_elearning] => 1 [cursovia_inRoom] => 1 [cursovia_from] => 2024-03-31 17:05:48 [cursovia_tothe] => 2024-07-31 18:06:09 [cursovia_forever] => 1 [cursovia_isbanner] => 1 [cursovia_banner_url] => /public/cliente/uploads/478/02_660869908593e.jpg [cursovia_ispaid] => 1 [cursovia_order] => 0 [cursovia_url] => https://www.rald.cl [cursovia_utm_source] => cursovia [cursovia_utm_medium] => top_banner [cursovia_utm_campaign] => DEMO-CAMPANA [cursovia_utm_term] => administracion [cursovia_utm_content] => curso-de-administracion-demo [cursovia_status] => 1 [token] => TOKENPRUEBA01 [promotional_video] => [cursovia_price] => 0 [created] => 0000-00-00 00:00:00 [client_name] => Cursovia [client_logo] => /public/cliente/uploads/478/logokampusrojo_6407be60d72e2.png [client_id] => 478 ) )
            ?>
        <div class="row">
          <div class="col-md-12" style="margin-top:-90px">

            <div class="slider">

                <?php foreach ($paid_banner_courses as $key => $paidBanner) {

                    $pb_token   =   $paidBanner->token;
                    $pb_image   =   "https://w3.kampusproject.com/".$paidBanner->cursovia_banner_url;
                    $pb_name    =   $paidBanner->name;

                ?>
                  <div>
                    <a href="<?php echo base_url('/link-externo/'.$pb_token); ?>" target="_blank" title="<?php echo $pb_name ?>">
                            <img src="<?php echo $pb_image ?>" /></a>
                    </div>

                <?php } ?>

            </div>

            

          </div>
        </div>
        <?php } ?>


        <script>
            $( ".target01" ).on( "click", function() {
  alert( "Handler for `click` called." );
} );
        </script>


        <?php } ?>

      <div class="row grid">

       <?php 

      $cuantos_registros = count($courses);
      $cuantas_paginas = ceil($cuantos_registros/10);


        $courses = array_slice( $courses, 0, 25 );

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

         <div class="col-md-4 courses-box grid-item <?php echo $marca; ?>">
          <div class="content <?php echo $class; ?> <?php echo $hover; ?>">
          <a href="<?php echo base_url('/mostrar/'.$slug); ?>">

              <div class="datos">
                <h1><?php echo $name; ?></h1>
                <h2><?php echo $client_name; ?></h2>
              </div>
              <?php if($price!='Cotizar'){ ?>
                <span class="price"><?php echo formateaMoneda($price); ?></span>
              <?php }else{ ?>
                <span class="price"><?php echo $price; ?></span>
              <?php } ?>
              <div class="imgViewer" style="background:url('<?php echo $image; ?>') no-repeat; background-size: cover;"></div>
        </a>

        <div class="row share-menu">

          <div class="col-3" style="padding-left: 0;">
                <i class="fas fa-heart fav <?php echo $class; ?>" data-idcurso="<?php echo $idcurso; ?>">
                    <span style="font-family:arial; font-size: 12px;" id="fav-<?php echo $idcurso; ?>"><?php echo $total_favorites; ?></span>
                </i>
          </div>

          <div class="col-9 share-bar index">

            <span style="margin-right: 10px">Compartir en:</span> 
            <span> 

            <ul>
              <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo url_actual(); ?>mostrar/<?php echo $slug; ?>" style="background-image: url('/public/imgs/facebook.svg');" target="_blank"></a></li>
              <!--li><a href="#" style="background-image: url('/public/imgs/linkedin.svg');"></a></li-->
              <!--li><a href="#" style="background-image: url('/public/imgs/twitter.svg');"></a></li-->
              <li><a href="https://api.whatsapp.com/send?text=Hola!%20te%20Env%C3%ADo%20este%20interesante%20link%20<?php echo url_actual(); ?>mostrar/<?php echo $slug; ?>" style="background-image: url('/public/imgs/whatsapp.svg');"  target="_blank"></a></li>
            </ul>
            </span>
          </div>


          </div>
          </div>


        </div> 

        <?php }?>
    </div>
        <!--div class="content button">Ver más cursos >></div-->

      </div>
      </div>
    </section>
    <?php }else{ ?>

      <section>
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <p class="msg-results">No encontramos ningún curso con "<?php echo $this->session->flashdata('txt'); ?>" :'(</p>
            </div>
          </div>
        <div class="content"><a href="/" class="button">Ver todos los cursos >></a></div>


        


        </div>
      </section>



    <?php } ?>

    <?php if($courses){ ?>

   <script type="text/javascript" src="<?php echo base_url('public/js/functions-fav.js?'.rand()); ?>"></script>

<script type='text/javascript'>

$(window).on('load', function () {
  loadMasonry();
});

function loadMasonry(){

var elem = document.querySelector('.grid');
var msnry = new Masonry( elem, {
  itemSelector: '.grid-item',
  columnWidth: 270
});

var msnry = new Masonry( '.grid', {
});

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


  function loadMore(page){
              var data = "";

              $(".grid").append(data);
                loadMasonry();

  }

  (function($){

$(document).ready(

  function(){

        $( "#SearchBar" ).focus();

      // Hacemos que el sitio haga scroll down hasta poner el foco en el buscador.

      // $('html, body').animate( {
      //   scrollTop : 60
      // }, 800, function(){

      //   $( "#SearchBar" ).focus();

      // });


    // Comprobar si estamos, al menos, 100 px por debajo de la posición top
    // para mostrar o esconder el botón
    $(window).scroll(function(){

      if ( $(this).scrollTop() > 100 ) {

        $('.scroll-to-top').fadeIn();

      } else {

        $('.scroll-to-top').fadeOut();

      }

    });

    // al hacer click, animar el scroll hacia arriba
    $('.scroll-to-top').click( function( e ) {

      e.preventDefault();
      $('html, body').animate( {scrollTop : 0}, 800 );

    });

  });

})(jQuery);
</script>

<?php } ?>

<?php echo $footer; ?>