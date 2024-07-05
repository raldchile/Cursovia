<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Compra de Cursos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Formulario de Compra de Cursos</h2>
    <form id="courseForm" action="<?php echo base_url('cpaybutton/pay')?>" method="post" novalidate>
        <input type="hidden" name="token" value="<?php echo $token; ?>"> 
        <div class="form-group">
            <label for="courseCount">¿Cuántos cursos comprará?</label>
            <input type="number" class="form-control" id="courseCount" name="courseCount" placeholder="Ingrese la cantidad de cursos" min="1" required>
            <div class="invalid-feedback">Por favor, ingrese una cantidad válida de cursos.</div>
        </div>
        <div id="studentsContainer"></div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>

<script>
    $(document).ready(function() {

        // Validación del formulario
        $('#courseForm').on('submit', function(event) {
            var form = this;
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
</script>
</body>
</html>
