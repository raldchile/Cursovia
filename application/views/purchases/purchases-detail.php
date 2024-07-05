<?php echo $header; ?>
<link rel="stylesheet" href="<?php echo base_url("public/css/purchases.css") ?>">
<?php

$fullname = $user['full_name'];
$emilio = $user['emilio'];
$company = $user['company'];
$id = $user['id'];

?>



<main class="padding-sct">
    <div class="container inner-top">
        <div class="titulos">Detalle compra</div>
        <h1><?php echo $this->ccourses_model->getCourseName($oc['course_id']) ?></h1>
        <p ><?php echo $oc['oc'] ?></p>
        <p id="downloadPdfLink" class="download-link">Descargar Factura</p>
        <div class="container container-added">
            <?php $added = count($details);
            if ($added != 0) { ?>
                <h4>Participantes <?php echo $added ?>/<?php echo $oc['quantity'] ?><i class="fa-light fa-users"></i></h4>
                <?php foreach ($details as $key => $detail) { ?>
                    <div class="row participants">
                        <h1><?php echo $detail->name . ' ' . $detail->last_name ?></h1>
                        <h2><?php echo $detail->rut ?></h2>
                        <p><?php echo $detail->email ?></p>
                    </div>
                <?php } ?>
        </div>
    <?php } else { ?>
        <div>No tienes participantes agregados</div>
    <?php } ?>
    <?php if ($added < $oc['quantity']) { ?>
        <button type="button" class="button-primary" id="openModal">
            Agregar participante
        </button>
    <?php } else {
        $current_date = new DateTime();
        $expiration_date = new DateTime($button['expiration_date']);
        $is_not_expired = $expiration_date > $current_date;

    ?>
        
        <?php if ($is_not_expired) { ?>
            <h5 class="no-cupos">¡Te quedaste sin cupos!</h5>
            <a href="<?php echo base_url('pay/' . $button['token']) ?>" type="button" class="button-primary">
                Comprar más
            </a>
        <?php } else { ?>
            <h5 class="no-cupos">¡Te quedaste sin cupos y por el momento no puedes comprar más!</h5>
    <?php }
    } ?>
    </div>


</main>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Agregar participante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form" novalidate>
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre.</div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Apellido</label>
                        <input type="text" class="form-control" id="last_name" required>
                        <div class="invalid-feedback">Por favor ingresa el apellido.</div>
                    </div>
                    <div class="form-group">
                        <label for="rut">RUT (opcional)</label>
                        <input type="text" class="form-control" id="rut" title="Ingresa un RUT válido (sin puntos, con guion y dígito verificador)">
                        <div class="invalid-feedback">Por favor ingresa un RUT válido (sin puntos, con guion y dígito verificador).</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" class="form-control" id="email" required>
                        <div class="invalid-feedback">Por favor ingresa el correo electrónico.</div>
                    </div>
                    <div class="respuesta"></div>
                    <button type="submit" class="button-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#openModal').on('click', function() {
            $('#modal').modal('show');
        });

        $('#form').submit(function(event) {
            event.preventDefault();
            var form = this;
            if (!form.checkValidity()) {
                event.stopPropagation();
                $(form).addClass('was-validated');
                return;
            }
            $(form).removeClass('was-validated');

            var name = $('#name').val();
            var last_name = $('#last_name').val();
            var rut = $('#rut').val();
            var email = $('#email').val();

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('add-participant/' . $oc['id']) ?>",
                data: {
                    name: name,
                    last_name: last_name,
                    rut: rut,
                    email: email
                },
                dataType: "json",
                beforeSend: function() {
                    txt = "<span class=\"content-msg\">Agregando...</span>";
                    $(".respuesta").html(txt);
                },
                success: function(response) {
                    console.log(response);
                    if (response.added) {
                        let new_participant = `<div class="row participants">
                        <h1>${name} ${last_name}</h1>
                        <h2>${rut}</h2>
                        <p>${email}</p>
                    </div>`;
                        try {
                            $('.container-added').append(new_participant);
                            txt = "<span class=\"content-msg\">Agregado correctamente</span>";
                            setTimeout(function() {
                                $(".respuesta").html(txt);
                            }, 1500);
                            setTimeout(function() {
                                location.reload();
                                $('#modal').modal('hide');
                            }, 1500);
                        } catch (e) {
                            alert('Error');
                        }
                    } else {
                        txt = "<span class=\"content-msg\">Error al agregar, ya tienes más cupos</span>";
                        $(".respuesta").html(txt);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error al enviar los datos. Por favor, inténtalo de nuevo.');
                }
            });
        });

        $('#rut').on('input', function() {
            let rutVal = $('#rut').val();
            let rut = $('#rut');
            if (rutVal === '' || validateRut(rutVal)) {
                console.log('valida');
                rut.get(0).setCustomValidity('');
            } else if (!validateRut(rutVal)) {
                console.log('invalida');
                rut.get(0).setCustomValidity('rut invalido');
            }
        });

        // Restablece la validación cuando se oculta el modal
        $('#modal').on('hidden.bs.modal', function() {
            $('#form').removeClass('was-validated');
        });

        function validateRut(rut) {
            // Verifica el formato del RUT
            if (!/^[0-9]{7,8}-[0-9kK]{1}$/.test(rut)) {
                return false;
            }

            // Elimina los puntos y convierte la 'k' en mayúscula si es necesario
            rut = rut.replace(/\./g, '').toUpperCase();

            // Extrae el número y el dígito verificador
            var number = rut.slice(0, -2);
            var dv = rut.slice(-1);

            // Calcula el dígito verificador esperado
            var sum = 0;
            var multiplo = 2;

            for (var i = number.length - 1; i >= 0; i--) {
                sum += parseInt(number.charAt(i)) * multiplo;
                if (multiplo < 7) {
                    multiplo++;
                } else {
                    multiplo = 2;
                }
            }

            var dvEsperado = 11 - (sum % 11);
            dvEsperado = (dvEsperado === 11) ? 0 : (dvEsperado === 10) ? 'K' : dvEsperado;

            // Compara el dígito verificador calculado con el proporcionado
            if (dv != dvEsperado) {
                return false;
            }

            return true;
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('#downloadPdfLink').on('click', function(e) {
            e.preventDefault();
            
            var folio = <?php echo $invoice['folio']?>;
            console.log('Folio: ' + folio);
            
            var $link = $(this);
            $link.text('Descargando...').addClass('disabled-link');

            $.ajax({
                url: '<?php echo base_url('cpaybutton/downloadInvoicePdf') ?>',
                method: 'GET',
                data: { folio: folio },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var url = window.URL.createObjectURL(data);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'factura_RALD_' + folio + '.pdf';
                    document.body.append(a);
                    a.click();
                    a.remove();
                    $link.text('Descargar Factura').removeClass('disabled-link');
                    console.log('Descarga iniciada y PDF mostrado');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + textStatus);
                    if (jqXHR.responseText) {
                        console.log('Response: ' + jqXHR.responseText);
                    }

                    $link.text('Descargar Factura').removeClass('disabled-link');
                }
            });
        });
    });
</script>


<?php echo $footer; ?>