<?php echo $header; ?>

<link rel="stylesheet" href="<?php echo base_url("public/css/paybutton.css") ?>">

<main class="main-container">
    <div class="transaction-summary card">
        <h1 class="titulo-exito">Transacción exitosa</h1>
        <div class="summary-details">
            <div class="course"><strong><?php echo $course_name ?></strong></div>
            <div class="client"><strong>por <?php echo $client['name'] ?></strong></div>
            <div class="quantity"><strong>Cantidad: </strong><?php echo $oc['quantity'] ?></div>
            <div class="amount"><strong>Monto pagado: </strong><?php echo number_format($resp->getAmount(), 0, ',', '.') . ' ' . $currency  ?></div>
            <div><strong>Número de orden:</strong> <?php echo $resp->getBuyOrder(); ?></div>
            <div><strong>Código de autorización: </strong> <?php echo $resp->getAuthorizationCode(); ?></div>
            <div><strong>Fecha:</strong>
                <?php
                $transactionDate = new DateTime($resp->getTransactionDate());
                echo $transactionDate->format('d/m/Y H:i:s');
                ?>
            </div>
            <div><strong><?php echo 'Tarjeta de ' . (($resp->getPaymentTypeCode() == 'VD') ? 'Débito' : 'Crédito') . ': ' ?></strong>**** **** **** <?php echo $resp->getCardDetail()['card_number']; ?></div>
            <?php if ($resp->getPaymentTypeCode() != 'VD' and $resp->getPaymentTypeCode() != 'VP') : ?>
                <div><strong>Tipo de cuota:</strong> <?php echo $resp->getPaymentTypeCode(); ?></div>
                <div><strong>Cantidad de cuotas:</strong> <?php echo $resp->getInstallmentsNumber(); ?></div>
            <?php endif; ?>
            <div><strong>Folio Factura:</strong> <?php echo isset($response_invoice->data->folio) ? $response_invoice->data->folio : 'Error facturación'  ?></div>
            

        </div>
        <div class="buttons">
            <button id="downloadPdf" class="button-primary center">Descargar Factura</button>
            <a href="<?php echo base_url('compras'); ?>" class="button-primary center">Ir a mis compras</a>
        </div>

    </div>
</main>

<script>
    $(document).ready(function() {
        $('#downloadPdf').on('click', function() {
            var folio = <?php echo $response_invoice->data->folio ?>;
            //console.log('Folio: ' + folio);

            var $button = $(this);
            $button.prop('disabled', true).text('Descargando...');

            $.ajax({
                url: '<?php echo base_url('cpaybutton/downloadInvoicePdf') ?>',
                data: {
                    folio: folio
                },
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
                    $button.prop('disabled', false).text('Descargar Factura');
                    //console.log('Descarga iniciada y PDF mostrado');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + textStatus);
                    if (jqXHR.responseText) {
                        console.log('Response: ' + jqXHR.responseText);
                    }
                    $button.prop('disabled', false).text('Descargar Factura');
                }
            });
        });
    });
</script>




<?php echo $footer; ?>