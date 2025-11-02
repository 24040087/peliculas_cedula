<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php");
        session_unset();
        session_destroy();
        exit;
    }
?>