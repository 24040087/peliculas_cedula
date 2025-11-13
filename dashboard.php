<?php
    include 'php/components.php';
    include 'php/session.php';
?>
<!DOCTYPE html>
<html lang="es" data-mdb-theme="dark">
    <head>
        <?php headContent("Administracion de Peliculas"); ?>
    </head>
    <body>
        <nav>
            <div>
            <img src="static/img/utc_logo.webp" alt="xd no funciona ff" height="50px">
            <span></span>
            </div>
            <a href="php/logout.php"></a>
            <!-- <i class="fa-solid fa-right-from-bracket"></i> -->
        </nav>
        <h1>Administrar Peluclas</h1>
        <h2>Bienvenido <?php echo $_SESSION['username'] ?></h2>
        <h2><?php echo $_SESSION['fullname'] ?></h2>
        <h2><?php echo $_SESSION['role'] ?></h2>

        <?php footerScripts(); ?>
    </body>
</html>