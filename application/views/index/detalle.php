<?php echo $header; ?>

<?php


$image            = $courses[0]["image_int"];
$name             = stot($courses[0]["name"]);
$hours            = stot($courses[0]["hour"]);
$code             = stot($courses[0]["code"]);
$client_name      = stot($courses[0]["client_name"]);
$client_slug      = $courses[0]["client_slug"];
$slug             = $courses[0]["slug"];
$description      = $courses[0]["description"];
$idcliente        = $courses[0]["client_id"];
$idcurso          = $courses[0]["id"];
$fav              = $courses[0]["favorite"];
$resume           = $courses[0]["resume"];
$target           = $courses[0]["target"];
$skill            = $courses[0]["skill"];
$video              = $courses[0]["promotional_video"];
$total_favorites  = $courses[0]["total_favorites"];
$modalidad        = $courses[0]["modalidad"];
$showSence        = $courses[0]["showSence"];
$price            = $courses[0]["price"];

if (isset($courses[0]["content"]) and count($courses[0]["content"])) {

    $content      = $courses[0]["content"];
}
$class        = "";
$marca        = "";

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

if ($video) {
    $video = URL_KAMPUS . $video;
}


?>

<section class="slide inner">
    <div class="container inner-top">
        <div class="row ">
            <div class="col-lg-8 content-detail">

                <h1><?php
                    echo $name;
                    ?></h1>

                <span class="share-bar">
                    <span style="margin-right: 10px">Compartir en:</span>
                    <span style="position: absolute;">

                        <ul>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo url_actual("mostrar") ?><?php echo $slug ?>" target="_blank"><i class="fa-brands fa-square-facebook"></i></a>
                            </li>
                            <!--li><a href="#" style="background-image: url('/public/imgs/linkedin.svg');"></a></li-->
                            <!--li><a href="#" style="background-image: url('/public/imgs/twitter.svg');"></a></li-->
                            <li><a href="https://api.whatsapp.com/send?text=Hola!%20te%20Env%C3%ADo%20este%20interesante%20link%20<?php echo url_actual("mostrar") ?><?php echo $slug; ?>" target="_blank"><i class="fa-brands fa-square-whatsapp"></i></a>
                            </li>
                        </ul>
                    </span>
                </span>

                <?php if ($video) {
                ?>

                    <video class="videomodule" width="100%" preload="auto" controls="controls" controlslist="nodownload" poster="<?php echo base_url('public/imgs/poster_estandar.jpg'); ?>">
                        <source src="<?php echo $video; ?>" type="video/mp4">
                        Tu navegador no implementa el elemento <code>video</code>.
                    </video>


                <?php
                }

                ?>

                <p style="margin-top: 30px;">
                    <?php echo $description; ?>
                </p>
                <?php if ($target) { ?>
                    <h2>Dirigido a</h2>
                    <p>
                        <?php echo $target; ?>
                    </p>
                <?php } ?>

                <?php if ($target) { ?>
                    <h2>Objetivos</h2>
                    <p>
                        <?php echo $skill; ?>
                    </p>
                <?php } ?>

                <?php if (isset($content["topics"]) and count($content["topics"])) { ?>
                    <h2>Contenidos</h2>
                    <ul class="contenidos">

                        <?php
                        foreach ($content["topics"] as $key => $value) {
                            $content_title      = $value["title"];
                            $content_modules    = (isset($value["modules"])) ? $value["modules"] : array();
                        ?>

                            <li><?php echo $content_title; ?> </li>
                            <?php if (isset($content_modules) and count($content_modules)) { ?>

                                <ul>

                                    <?php foreach ($content_modules as $key => $modules) {
                                        $module_title   = $modules["title"];
                                        $module_icon   = $modules["icon"];
                                    ?>
                                        <li class="topic <?php echo $module_icon; ?>"><?php echo $module_title; ?> </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                        <?php } ?>

                    </ul>

                <?php } ?>


            </div>

            <div class="col-lg-4 content-detail pt">
                <div style="display: block;overflow: hidden;margin-bottom: 10px;">
                    <i class="fas fa-heart fav <?php echo $class; ?>" style="float: right;" data-idcurso="<?php echo $idcurso; ?>"> <span style="font-family: 'Figtree', sans-serif;" id="fav-<?php echo $idcurso; ?>"><?php echo $total_favorites; ?> me gusta</span></i>

                </div>

                <ul style="display: block;">
                    <li>Autor: <strong><a href="<?php echo base_url($client_slug); ?>"><?php echo $client_name; ?></a></strong>
                    </li>
                    <li>Precio:
                        <strong><a><?php if ($price != 'Cotizar') {
                                        echo formateaMoneda($price);
                                    } else {
                                        echo "Cotizar";
                                    } ?></a></strong>
                    </li>
                    <li>Horas: <strong><?php if ($hours) {
                                            echo $hours;
                                        } else {
                                            echo "--";
                                        } ?></strong></li>
                    <?php if ($showSence) { ?>
                        <li>Cod. Sence: <strong><?php echo $code; ?></strong></li>
                    <?php } ?>
                    <!--li>Certificado: <strong>Sí</strong></li-->
                    <li>Modalidad: <strong><?php echo $modalidad; ?></strong></li>
                </ul>
                <?php if ($isLogged) { ?>
                    <div id="login-button" class="button-primary" data-toggle="modal" data-target="#contact">Contactar</a>
                    <?php } else { ?>
                        <div class="button-disabled" data-toggle="modal">Inicie sesión para contactar</a>
                        <?php } ?>

                        </div>

                    </div>

            </div>
        </div>
    </div>
</section>

<?php if ($isLogged) { ?>


<!-- Modal -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Escribe tu solicitud y apreta el botón
                    <strong>"Contactar"</strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action=<?php echo base_url("cmessages/sendMessage") ?> method="post" role="form" data-toggle="validator" id="contact-form">
                    <div class="acount-group">
                        <span>De: <strong><?php echo $sessionuser["full_name"]; ?></strong></span>
                        <span>Para: <strong><?php echo $client_name; ?></strong></span>
                        <span>Asunto: Consulta curso <strong><?php echo $name; ?></strong></span>
                    </div>
                    <div class="respuesta"></div>
                    <div class="acount-group">
                        <label><strong>Mensaje / Consulta:</strong></label>
                        <textarea name="message" class="form-control" placeholder="Escribe tu consulta" required style="height: 200px;" id="message"></textarea>
                    </div>
                    <div class="form-group input-group-lg acount-group">
                        <label>Password:</label>
                        <input type="password" name="passUser" class="form-control" placeholder="Password" id="password" required>
                    </div>
                    <input type="hidden" name="toUser" value="<?php echo $idcliente; ?>">
                    <input type="hidden" name="course" value="<?php echo $idcurso; ?>">
                    <input type="hidden" name="subject" value="Consulta curso <?php echo $name; ?>">
                    <input type="hidden" name="idm">
                    <button type="submit" class="button-primary" id="enviar-form">Enviar contacto >></button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="button-secondary" data-dismiss="modal" id="cerrar">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<script type="text/javascript" src="<?php echo base_url('public/js/functions-fav.js'); ?>"></script>

<script type="text/javascript">
    $("#go").click(function() {
        $("#buscar").submit();
    });


    // $( "#enviar-form" ).click(function() {
    //   alert('ss');
    //   $( "#contact-form" ).submit();
    // });

    // $(document).on('click', '#enviar-form', function(){
    //   $( "#contact-form" ).submit();
    // });


    $(document).ready(function() {

        function cerrar() {
            $('#cerrar').trigger('click');
        }

        $("#contact-form").bind("submit", function() {
            // Capturamnos el boton de envío
            var btnEnviar = $("#enviar-form");
            $.ajax({
                type: $(this).attr("method"),
                url: $(this).attr("action"),
                data: $(this).serialize(),
                beforeSend: function() {
                    /*
                     * Esta función se ejecuta durante el envió de la petición al
                     * servidor.
                     * */
                    // btnEnviar.text("Enviando"); Para button 
                    $(".respuesta").html("Enviando");
                    btnEnviar.val("Enviando"); // Para input de tipo button
                    btnEnviar.attr("disabled", "disabled");
                },
                complete: function(data) {
                    /*
                     * Se ejecuta al termino de la petición
                     * */
                    btnEnviar.val("Enviar formulario");
                    btnEnviar.removeAttr("disabled");
                },
                success: function(data) {
                    /*
                     * Se ejecuta cuando termina la petición y esta ha sido
                     * correcta
                     * */
                    $(".respuesta").html(data);
                    $("#message").val('');
                    $("#password").val('');
                    setTimeout(cerrar, 1000);

                },
                error: function(data) {
                    /*
                     * Se ejecuta si la peticón ha sido erronea
                     * */
                    alert("Problemas al tratar de enviar el formulario");
                }
            });
            // Nos permite cancelar el envio del formulario
            return false;
        });
    });
</script>

<script type='text/javascript'>
    $(window).scroll(function() {
        if ($(this).scrollTop() > 97) {
            $('.pt').addClass("fixed").fadeIn();
            // $('.contenedor').addClass("margen").fadeIn();
        } else {
            $('.pt').removeClass("fixed");
            // $('.contenedor').removeClass("margen");
        }
    });
</script>





<?php echo $footer; ?>