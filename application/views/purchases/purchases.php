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
        <div class="titulos">Compras</div>
        <?php if ($purchases) { ?>
            <div class="container container-purchases">
                <?php foreach ($purchases as $key => $oc) {
                    $added = count($this->cpurchases_model->getAdded($oc->oc));
                ?>
                    <a class="row purchase" href=<?php echo base_url('detalle-compra/' . $oc->oc) ?>>
                        <h1><?php echo $this->ccourses_model->getCourseName($oc->course_id); ?></h1>
                        <h2><?php echo $oc->oc ?></h2>
                        <p>Participantes añadidos: <?php echo $added ?>/<?php echo $oc->quantity ?><i class="fa-light fa-users"></i></p>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>


</main>

<?php echo $footer; ?>