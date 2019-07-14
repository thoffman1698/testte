<?php

require_once 'assets/includes/header.php';

?>
<!-- PNotify -->
<link href="assets/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="assets/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="assets/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

<section>
  <div class="container-fluid login">
    <div class="row">
      <div class="col-md-4">
        <h3>Dimensiona</h3>
      </div>
    </div>
    <div class="row">
      <form class="form-signin col-md-4 col-md-offset-4" method="POST" action="valida.php" align="right">
        <div class="form-group">
          <!-- <label>Login: </label> -->
          <input type="text" name="Login" class="form-control has-feedback-left" required autofocus maxlength="45" placeholder="Login...">
        </div>

        <div class="form-group">
          <input type="password" name="Senha" id="inputPassword" class="form-control has-feedback-left" maxlength="45" placeholder="Senha..." required />
        </div>

        <button class="btn btn-login" data-loading-text="Loading..." autocomplete="off">Acessar</button>
        <!-- <a class="btn btn-lg btn-success btn-block" href="cad_login.php">Cadastrar</a> -->
      </form>
    </div>
    <?php
      //Incluindo a conexão com banco de dados
      require_once 'assets/includes/conexao.php';	
    ?>
      <p class="text-center text-danger">
        <?php if(isset($_SESSION['loginErro'])){
          echo $_SESSION['loginErro'];
          unset($_SESSION['loginErro']);
        }?>
      </p>
      <p class="text-center text-success">
        <?php 
        if(isset($_SESSION['logindeslogado'])){
          echo $_SESSION['logindeslogado'];
          unset($_SESSION['logindeslogado']);
        }
        ?>
      </p>
      <?php mysqli_close($ligacao); ?>
  </div> <!-- /container -->
</section>

<?php

require_once 'assets/includes/footer.php';

?>

<!-- PNotify -->
<script src="assets/vendors/pnotify/dist/pnotify.js"></script>
<script src="assets/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="assets/vendors/pnotify/dist/pnotify.nonblock.js"></script>

<!-- PNotify -->
<?php 
  if(isset($_SESSION['Mensagem'])){
    ?>
    <script>
      $(document).ready(function() {
        new PNotify({
          title: "<?php echo $_SESSION['TituloMensagem'];?>",
          type: "<?php echo $_SESSION['TipoMensagem'];?>",
          text: "<?php echo $_SESSION['Mensagem'];?>",
          nonblock: {
              nonblock: true
          },
          styling: 'bootstrap3',
          hide: false,
          before_close: function(PNotify) {
            PNotify.update({
              title: PNotify.options.title + " - Enjoy your Stay",
              before_close: null
            });

            PNotify.queueRemove();

            return false;
          }
        });

      });
    </script>

    <?php 
    unset($_SESSION['TituloMensagem']);
    unset($_SESSION['TipoMensagem']);
    unset($_SESSION['Mensagem']);
    unset($_SESSION['Fo']);
  }
?>
<!-- /PNotify -->