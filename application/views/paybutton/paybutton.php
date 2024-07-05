<?php echo $header; ?>

<link rel="stylesheet" href="<?php echo base_url("public/css/paybutton.css") ?>">

<main class="main-container">
  <div class="formCard card">
    <h1 class="titulos">Datos Facturación</h1>
    <form id="courseForm" action="" method="post" novalidate>
      <div class="form-row">
        <div class="form-group col">
          <label for="name">Nombre / Razon Social</label>
          <input type="text" class="form-control" id="name" required>
          <div class="invalid-feedback">Por favor ingresa el nombre o razon social.</div>
        </div>
        <div class="form-group col">
          <label for="giro">Giro</label>
          <input type="text" class="form-control" id="giro" required>
          <div class="invalid-feedback">Por favor ingresa el giro.</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col">
          <label for="rut">RUT</label>
          <input type="text" class="form-control" id="rut" title="Ingresa un RUT válido (sin puntos, con guion y dígito verificador)" required>
          <div class="invalid-feedback">Por favor ingresa un RUT válido (sin puntos, con guion y dígito verificador).</div>
        </div>
        <div class="form-group col">
          <label for="email">Correo</label>
          <input type="email" class="form-control" id="email" value="<?php echo $sessionuser['emilio'] ?>" required>
          <div class="invalid-feedback">Por favor ingresa el correo electrónico.</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col">
          <label for="address">Dirección</label>
          <input type="text" class="form-control" id="address" required>
          <div class="invalid-feedback">Por favor ingresa la dirección.</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col">
          <label for="city">Ciudad</label>
          <input type="text" class="form-control" id="city" required>
          <div class="invalid-feedback">Por favor ingresa la diudad.</div>
        </div>
        <div class="form-group col">
          <label for="comuna">Comuna</label>
          <input type="text" class="form-control" id="comuna" required>
          <div class="invalid-feedback">Por favor ingresa la comuna.</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col">
          <label for="courseCount">¿Cuántos cursos desea comprar?</label>
          <input type="number" class="form-control" id="courseCount" name="courseCount" placeholder="Ingrese la cantidad de cursos" min="1" required>
          <div class="invalid-feedback">Por favor, ingrese una cantidad válida de cursos.</div>
        </div>
      </div>
      <button type="submit" class="button-primary center">Continuar</button>
    </form>
  </div>
  <?php
  $client_id = $client['id'];
  $client_name = $client['name'];
  $client_slug = $client['slug'];
  $client_profile_img = $client['logo'];
  $client_color_first = $client['color_first'];
  $client_color_second = $client['color_second'];
  $user_name = $user['full_name'];
  $user_emilio = $user['emilio'];
  $user_company = $user['company'];
  $user_id = $user['id'];
  $course = $courses[0];
  $course_name = $course['name'];
  $course_hours = $course['hour'];
  $course_price = $course['price'];
  $button_token = $button['token'];
  $client_profile_img = base_url('public/imgs/ca_perfil_2.png');
  /*   $client_profile_img = ($client_profile_img) ? URL_KAMPUS . $client_profile_img : base_url('public/imgs/ca_perfil_2.png'); */

  ?>

  <div class="resumeCard card" id="summaryCard" style="display: none">
    <div class="course-name"><?php echo $course_name; ?></div>
    <div class="course-name"><?php echo $client_name ?></div>
    <div class="info-course">
      <i class="fa-solid fa-clock"></i>
      <p>
        <?php echo $course_hours ?> hrs
      </p>
      <p>$<span id="price"><?php echo $course_price ?></span> CLP
      </p>
    </div>

    <div class="summary">
      <div class="summary-item"><strong>Nombre / Razón Social: </strong> <span id="razon_social"></span></div>
      <div class="summary-item"><strong>Giro: </strong> <span id="giro_comercial"></span></div>
      <div class="summary-item"><strong>RUT: </strong> <span id="rut_comprador"></span></div>
      <div class="summary-item"><strong>Direción: </strong> <span id="full_address"></span></div>
      <div class="summary-item"><strong>Cantidad de Cursos: </strong><span id="quantity"><?php echo $quantity ?></span></div>
      <div class="summary-item"><strong>Valor Total: $</strong><span id="total_price"><?php echo formateaMoneda($total_price) ?></span> CLP</div>
    </div>

    <div class="buttons">
      <button type="button" id="backButton" class="button-secondary" style="display: none;">Volver</button>
      <button id="payButton" class="button-primary">Pagar</button>
    </div>
  </div>
</main>
<script>
  $(document).ready(function() {
    $('#courseForm').on('submit', function(event) {
      var form = this;
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      } else {
        event.preventDefault();
        var quantity = $('#courseCount').val();
        var coursePrice = <?php echo $course_price; ?>;
        var totalPrice = quantity * coursePrice;
        var razon_social = $('#name').val();
        var giro = $('#giro').val();
        var rut = $('#rut').val();
        var full_address = $('#address').val() + ', ' + $('#comuna').val() + ', ' + $('#city').val();

        $('#quantity').text(quantity);
        $('#total_price').text(totalPrice.toLocaleString());
        $('#price').text(coursePrice.toLocaleString());
        $('#razon_social').text(razon_social);
        $('#giro_comercial').text(giro);
        $('#rut_comprador').text(rut);
        $('#full_address').text(full_address);
        $('#summaryCard').show();
        $('.formCard').hide();
        $('#backButton').show();
      }
      form.classList.add('was-validated');
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

    $('#backButton').on('click', function() {
      $('#summaryCard').hide();
      $('.formCard').show();
      $(this).hide();
    });

    $('#payButton').on('click', function(event) {
      event.preventDefault();
      var buyOrder = 'OC-' + '<?php echo $sessionuser['id'] . '-' ?>' + new Date().getTime(); // Generar un número de orden único
      var sessionId = '<?php echo session_id(); ?>';
      var amount = parseInt($('#total_price').text().replace(/\D/g, ''), 10);
      var buttonToken = '<?php echo $button_token ?>';
      var quantity = $('#courseCount').val();
      var name = $('#name').val();
      var giro = $('#giro').val();
      var rut = $('#rut').val().toUpperCase();
      var email = $('#email').val();
      var address = $('#address').val();
      var city = $('#city').val();
      var comuna = $('#comuna').val();
      console.log('amount: ' + amount);
      console.log('buttonToken:' + buttonToken);

      var requestData = {
        buy_order: buyOrder,
        session_id: sessionId,
        amount: amount,
        button_token: buttonToken,
        quantity: quantity,
        name: name,
        giro: giro,
        rut: rut,
        email: email,
        address: address,
        city: city,
        comuna: comuna,
      };

      $.ajax({
        url: '<?php echo base_url('cpaybutton/createTransaction') ?>',
        method: "POST",
        data: requestData,
        dataType: "json",
        success: function(response) {
          createAndSubmitForm(response.url, response.token);
        },
        error: function(xhr, status, error) {
          console.error("Error:", error);
          console.log("Response Text:", xhr.responseText);
        },
      });
    });

    function createAndSubmitForm(url, token) {
      // Crear un formulario oculto
      var form = $('<form>', {
        'method': 'GET',
        'action': url,
        'style': 'display: none;'
      });

      var inputToken = $('<input>', {
        'type': 'hidden',
        'name': 'token_ws',
        'value': token
      });

      form.append(inputToken);

      $('body').append(form);
      form.submit();
    }
  });
</script>

<?php echo $footer; ?>