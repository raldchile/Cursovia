<?php echo $header; ?>
<section class="slide inner">
  <div class="container inner-top">
    <?php if ($this->session->flashdata('msg')) { ?>
      <div class="row">
        <div class="col-md-12"><?php echo $this->session->flashdata('msg'); ?> </div>
      </div>
    <?php } ?>
    <div class="row">
      <div class="col-md-6 content-detail">

        <h1>Login</h1>
        <p>
          ¡Ingresa y encuentra tu curso!
        </p>

        <form action="<?php echo base_url('clogin/checkLogin') ?>" method="post" role="form" id="loginForm" novalidate>
          <div class="form-group input-group-lg">
            <label>Email</label>
            <input type="email" name="emailUser" class="form-control" aria-describedby="emailHelp" placeholder="Ingresa mail registrado" required>
            <div class="invalid-feedback">Por favor, ingresa un email válido.</div>
            <small id="emailHelp" class="form-text text-muted">¡Nunca compartiremos tu email con nadie! :)</small>
          </div>

          <div class="form-group input-group-lg">
            <label>Password</label>
            <input type="password" name="passUser" class="form-control" placeholder="Password" required>
            <div class="invalid-feedback">Por favor, ingresa una contraseña.</div>
            <small class="form-text text-muted"><a href="<?php echo base_url('clogin/getPassword') ?>">Recupera tu clave de acceso</a></small>
          </div>
          <input type="hidden" name="backto" class="form-control" value="<?php echo $backto; ?>">
          <button type="submit" class="button-primary">Ingresar</button>
        </form>
      </div>

      <div class="col-md-6 content-detail" style="border-left: 1px dashed #EEE;">

        <h1>Registro</h1>

        <p><?php echo $this->session->flashdata('msg-reg'); ?></p>

        <p>
          Registrate para tener un mejor resultado en Cursovia
        </p>

        <form action="<?php echo base_url('clogin/setAccount') ?>" method="post" autocomplete="on" id="registroForm" novalidate>

          <div class="form-group input-group-lg">
            <label>Nombre completo</label>
            <input name="fullname" type="text" class="form-control" placeholder="Ingresa tu nombre" required>
            <div class="invalid-feedback">Debes ingresar tu nombre.</div>
          </div>

          <div class="form-group input-group-lg">
            <label>Empresa</label>
            <input name="company" type="text" class="form-control" placeholder="Nombre de empresa">
          </div>

          <div class="form-group input-group-lg">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Ingresa tu email" required>
            <div class="invalid-feedback">Por favor, ingresa un email válido.</div>
            <span class="alert" id="alert"><strong>¡Email ya está en uso!</strong> Intente con otro.</span>
            <small id="emailHelp" class="form-text text-muted">Deberás validarlo en tu casilla de email. <strong>¡Este será tu email de acceso!</strong></small>
          </div>

          <div class="form-group input-group-lg">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" data-error="La password debe tener al menos 6 caracteres, una letra mayúscula, una letra minúscula y un número">
            <div class="invalid-feedback">La password debe tener al menos 6 caracteres, una letra mayúscula, una letra minúscula y un número.</div>
          </div>

          <div class="form-group input-group-lg" id="confirmPaawordGroup">
            <label for="confirmPassword">Confirma tu password</label>
            <input type="password" class="form-control" id="confirmPassword" placeholder="Password" required data-match="#password" data-match-error="Las password no coinciden">
            <div class="invalid-feedback">Por favor, confirma tu password</div>
          </div>

          <div class="form-group input-group-lg">
            <label for="country">País</label>
            <?php
            echo form_dropdown('country', $countries, (isset($usercopy[0]->country_id) ? $usercopy[0]->country_id : set_value('country')), 'class="form-control" required');
            ?>
            <div class="invalid-feedback">Por favor, selecciona un país.</div>
          </div>

          <div class="g-recaptcha" data-sitekey="6LeQjJsaAAAAAAChoSu2zHAOpfHjQiiNnYUZT6uF"></div>

          <button id="submitRegister" type="submit" class="button-primary">¡Registrarme!</button>
        </form>

      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
      var form = this;
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
    $('#registroForm').on('submit', function(event) {
      var form = this;
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
    $('#confirmPassword').on('input', function() {
      var password = $('#password').val();
      var confirmPassword = $('#confirmPassword');

      if (confirmPassword.val() !== password) {
        console.log('invalida');
        confirmPassword.get(0).setCustomValidity('Las passwords no coinciden');
      } else {
        console.log('valida');
        confirmPassword.get(0).setCustomValidity('');
      }
    });
  });
</script>


<script type="text/javascript">
  $("#email").blur(function() {
    var email = $("#email").val();
    if (email) {
      $.ajax({
        url: "<?php echo base_url() ?>clogin/validaUser",
        type: "POST",
        data: {
          email: email
        },
        context: document.body
      }).done(function(data) {

        if (data == false) {
          $('#submitRegister').attr("disabled", false);
          $('#alert').removeClass('ver');
        } else {
          $('#submitRegister').attr("disabled", true);
          $('#alert').addClass('ver');
        }

      });
    }
  });
</script>
<?php echo $footer; ?>