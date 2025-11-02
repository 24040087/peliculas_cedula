<?php
    include 'php/components.php';
    include 'php/session.php';
?>
<!DOCTYPE html>
<html lang="es" data-mdb-theme="light">
    <head>
        <?php headContent("Administracion de Peliculas"); ?>
    </head>
    <body>
    
        <h1>Administrar Peluclas</h1>
        <h2>Bienvenido <?php echo $_SESSION['username'] ?></h2>


        <?php footerScripts(); ?>
    </body>
</html>