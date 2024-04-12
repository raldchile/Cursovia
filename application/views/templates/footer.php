  <section  class="footer">
    <div class="container">
      <div class="row" style="width: 100%; max-width: 1280px">
        <div class="col-md-4"><img src=<?php echo base_url("public/imgs/logo_cursovia-fiwh.svg")?> class="footer-logo">
          <p style="font-size: 13px;margin-left: 7px;"><strong>Cursovia&#174;</strong> es Marca Registrada de <strong>Rald&#174;</strong>.</p></div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <ul>
            <!--li>¿Qué es Cursovia?</li-->
            <li>www.cursovia.com</li>
          </ul>
        </div>
      </div>
    </div>

  </section>



<!-- Button trigger modal -->
<button type="button" id="login-button" class="btn btn-primary" data-toggle="modal" data-target="#checkLogin" style="display:none;" >
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="checkLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¡Ups! Para poder marcar uno o más cursos como favoritos o recomendado, <strong>debes iniciar sesión:</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <form action=<?php echo base_url("clogin/checkLogin")?> method="post" role="form" data-toggle="validator">
                <div class="form-group input-group-lg">
                  <label>Email/Usuario:</label>
                  <input type="email" name="emailUser" class="form-control" aria-describedby="emailHelp" placeholder="Ingresa mail registrado" required>
                  <div class="help-block with-errors"></div>
                  <small id="emailHelp" class="form-text text-muted">¡Nunca compartiremos tu email con nadie! :)</small>
                </div>

                <div class="form-group input-group-lg">
                  <label>Password:</label>
                  <input type="password" name="passUser" class="form-control" placeholder="Password"  required>
                  <div class="help-block with-errors"></div>
                </div>
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">¡No por ahora!</button>
        <button type="button" class="btn btn-primary"><a href="/ingresar">Registrarse</a></button>
      </div>
    </div>
  </div>
</div>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-72022775-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-72022775-1');
</script>






  </body>
</html>