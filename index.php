<?php
include 'php/components.php';

session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es" data-mdb-theme="dark">

<head>
  <?php headContent("Inicio de Sesion"); ?>
</head>

<body>

  <main>
    <form action="login">
      <div class="card mb-4">
        <div class="card-body text-center">
          <h4 class="m-0">Iniciar Sesion</h4>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <div class="form-outline mb-4" data-mdb-input-init>
            <input type="text" id="userlog" name="userlog" class="form-control" />
            <label class="form-label" for="userlog">Usuario:</label>
          </div>

          <div class="form-outline" data-mdb-input-init>
            <i class="far fa-eye" id="btnShowPass" data-input="#passlog"></i>
            <input type="password" id="passlog" name="passlog" class="form-control form-icon-trailing" />
            <label class="form-label" for="passlog">Contraseña:</label>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <button type="submit" class="btn btn-detail btn-block">
            <i class="fas fa-paper-plane"></i><span class="mx-2">Entrar</span><i class="fas fa-paper-plane"></i>
          </button>
        </div>
      </div>
    </form>
    <div class="index-icons" id="indexIcons" style="--icon-size: 50px;" data-icon-size="50"></div>
  </main>
  <?php footerScripts(); ?>
</body>

</html>