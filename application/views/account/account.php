<?php echo $header; ?>
<!-- <pre> -->
<?php // print_r($user); ?>
<!-- </pre> -->

     <?php foreach ($user as $key => $usr) {

      $fullname = $usr->full_name;
      $emilio = $usr->emilio;
      $company = $usr->company;
      $id = $usr->id;

     }
     ?>



    <section  class="slide inner">
      <div class="container inner-top">
      <div class="row ">
        <div class="col-md-6 content-detail">
                  
           <span class="titulos">Mi cuenta</span>

           <p>
            <?php echo $this->session->flashdata('msg'); ?>
           </p>
          <p>
            <strong>Actualiza tus datos:</strong>
          </p>

       <form action="/caccount/updateUser" method="post" autocomplete="false" id="registro" role="form" data-toggle="validator">

                <div class="form-group input-group-lg">
                          <label>Nombre completo</label>
                          <input value="<?php echo $fullname; ?>" name="fullname" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Ingresa tu nombre" data-error="Debes ingresar tu nombre" required>
                          <div class="help-block with-errors"></div>
                </div>

               
                 <div class="form-group input-group-lg">
                          <label>Empresa</label>
                          <input value="<?php echo $company; ?>" name="company" type="text" class="form-control" id="" aria-describedby="emailHelp" placeholder="Nombre de empresa">
                </div>

                <div class="form-group input-group-lg">
                  <label for="email">Email / Usuario</label>
                  <input  value="<?php echo $emilio; ?>" type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" disabled>
                </div>

               

                 <div class="form-group input-group-lg">
                  <label for="exampleInputPassword1">País</label>
                   
                    <?php
                                echo form_dropdown('country', $countries, $id, 'class="form-control" required');
                    ?>
                  </div>

                  <div class="form-group input-group-lg">
                        <label for="exampleInputPassword1">Password:</label>
                        <input type="password" name="current-password" class="form-control" id="curent-password" placeholder="Password" data-minlength="1"  data-error="Debe completar este campo" required>
                        <div class="help-block with-errors"></div>
                </div>

                <button type="submit" class="btn btn-primary">¡Modificar mis datos!</button>

            </form>

          </div>

        <div class="col-md-6 content-detail" style="border-left: 1px dashed #EEE;">

           <span class="titulos">Seguridad</span>

          <p>
            <?php echo $this->session->flashdata('msg-pw'); ?>
          </p>

          <p>
           <strong>Cambia tu password en el siguiente formulario:</strong>
          </p>

            <form action="/caccount/changePassword" method="post" role="form" data-toggle="validator">

              <div class="form-group input-group-lg">
                        <label for="exampleInputPassword1">Password actual:</label>
                        <input type="password" name="current-password" class="form-control" id="password" placeholder="Password" data-minlength="1"  data-error="Debe completar este campo" required>
                        <div class="help-block with-errors"></div>
                </div>

                <div class="form-group input-group-lg">
                        <label for="exampleInputPassword1">Ingresa una nueva Password:</label>
                        <input type="password" name="npassword" class="form-control" id="npassword" placeholder="Nueva password" data-minlength="6"  data-error="La password debe tener al menos 6 caracteres" required>
                        <div class="help-block with-errors"></div>
                </div>

                <div class="form-group input-group-lg">
                        <label for="exampleInputPassword1">Confirma tu nueva password</label>
                        <input type="password" class="form-control" placeholder="Confirma nueva password" required data-match-error="Las password nuevas no coinciden" data-match="#npassword">
                        <div class="help-block with-errors"></div>
                </div>
                <button type="submit" class="btn btn-primary">¡Cambiar password!</button>
            </form>
        </div>


        
        


      </div>

    </div>

    
  </section>

<?php echo $footer; ?>
