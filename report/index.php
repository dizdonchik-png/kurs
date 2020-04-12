<?php
session_start();

if(!isset($_SESSION["login"])){ // если не задана переменная сессии login, то перейти на страницу авторизации, в противном случае далее выполнять код
    header("location: ../login.php");
    exit;
}

require_once "header.php";
?>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap-4.3.1/popper.min.js"></script>
    <script src="../js/bootstrap-4.3.1/bootstrap.min.js"></script>
    </body>
    </html>