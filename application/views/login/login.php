<?php echo $header; ?>
    <section  class="slide inner">
      <div class="container inner-top">
        <?php if($this->session->flashdata('msg')){ ?>
        <div class="row">
          <div class="col-md-12"><?php echo $this->session->flashdata('msg'); ?>          </div>
        </div>
      <?php } ?>
      <div class="row ">
        <div class="col-md-6 content-detail">
                  
          <h1>Login</h1>
          <p>
            ¡Ingresa y encuentra tu curso!
          </p>

            <form action="/clogin/checkLogin" method="post" role="form" data-toggle="validator">
                <div class="form-group input-group-lg">
                  <label>Email address</label>
                  <input type="email" name="emailUser" class="form-control" aria-describedby="emailHelp" placeholder="Ingresa mail registrado" required>
                  <div class="help-block with-errors"></div>
                  <small id="emailHelp" class="form-text text-muted">¡Nunca compartiremos tu email con nadie! :)</small>
                </div>

                <div class="form-group input-group-lg">
                  <label>Password</label>
                  <input type="password" name="passUser" class="form-control" placeholder="Password"  required>
                  <div class="help-block with-errors"></div>
                  <small id="emailHelp" class="form-text text-muted"><a href="/clogin/getPassword">Recupera tu clave de acceso</a></small>

                </div>
                  <input type="hidden" name="backto" class="form-control" value="<?php echo $backto; ?>">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </form>
        </div>

        <div class="col-md-6 content-detail" style="border-left: 1px dashed #EEE;">
        
         <h1>Registro</h1>

           <p><?php echo $this->session->flashdata('msg-reg'); ?></p>

          <p>
            Registrate para tener un mejor resultado en Cursovia
          </p>

       <form action="/clogin/setAccount" method="post" autocomplete="false" id="registro" role="form" data-toggle="validator">

                <div class="form-group input-group-lg">
                          <label>Nombre completo</label>
                          <input name="fullname" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Ingresa tu nombre" data-error="Debes ingresar tu nombre" required>
                          <div class="help-block with-errors"></div>
                </div>

               
                 <div class="form-group input-group-lg">
                          <label>Empresa</label>
                          <input name="company" type="text" class="form-control" id="" aria-describedby="emailHelp" placeholder="Nombre de empresa">
                </div>

                <div class="form-group input-group-lg">
                  <label for="email">Email</label>
                  <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Ingresa tu email" required>
                        <div class="help-block with-errors"></div>

                  <span class="alert" id="alert"><strong>¡Email ya está en uso!</strong> Intente con otro.</span>
                  <small id="emailHelp" class="form-text text-muted">Deberás validarlo en tu casilla de email. <strong>¡Este será tu email de acceso!</strong></small>
                </div>

                <div class="form-group input-group-lg">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" data-minlength="6"  data-error="La password debe tener al menos 6 caracteres" required>
                        <div class="help-block with-errors"></div>
                </div>

                <div class="form-group input-group-lg">
                        <label for="exampleInputPassword1">Confirma tu password</label>
                        <input type="password" class="form-control" placeholder="Password" required data-match-error="Las password no coinciden" data-match="#password">
                        <div class="help-block with-errors"></div>
                </div>

                 <div class="form-group input-group-lg">
                  <label for="exampleInputPassword1">País</label>
  
                    <?php
                                echo form_dropdown('country', $countries, (isset($usercopy[0]->country_id) ? $usercopy[0]->country_id : set_value('country')), 'class="form-control" required');
                            ?>
                  </div>

              <div class="g-recaptcha" data-sitekey="6LeQjJsaAAAAAAChoSu2zHAOpfHjQiiNnYUZT6uF"></div>
                  

                <button id="submit"  type="submit" class="btn btn-primary" disabled>¡Registrarme!</button>

            </form>

        </div>

      </div>

    </div>

    
  </section>

 <script type="text/javascript">
      
        $( "#email" ).blur(function() {

         var email = $("#email").val();

          if(email){
            $.ajax({
            url: "clogin/validaUser",
            type: "POST",
            data: { email: email},
            context: document.body
              }).done(function(data) {
            
              if(data==false){
                 $('#submit').attr("disabled", false);
                 $('#alert').removeClass('ver');
              }else{
                 $('#submit').attr("disabled", true);
                 $('#alert').addClass('ver');
            }

          });
        }
        });


    </script>
<?php echo $footer; ?>
