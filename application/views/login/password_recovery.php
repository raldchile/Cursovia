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
                  
          <h1>Recupera tu password</h1>
          <p>
            Ingresa el email registrado para enviarte una nueva clave:
          </p>

            <form action="<?php echo base_url('clogin/getPassword')?>" method="post" role="form" data-toggle="validator">
                <div class="form-group input-group-lg">
                  <label>Email address</label>
                  <input type="email" name="emailUser" class="form-control" aria-describedby="emailHelp" placeholder="E-mail" required>
                  <div class="help-block with-errors"></div>
                </div>
                  <!-- <input type="hidden" name="backto" class="form-control" value="<?php echo $backto?>"> -->
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>

        <div class="col-md-6 content-detail">
          <img src="<?php echo base_url("/public/imgs/1mp.svg") ?>"/>
          
          
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
