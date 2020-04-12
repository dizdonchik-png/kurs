<?php
session_start();

require('mysql/connect.php');

if(isset($_POST['login']) and isset($_POST['pass'])) {
    $login = $_POST['login'];
    $pass = $_POST['pass'];

    $role = $mysql->query("SELECT role_id AS role FROM users WHERE login='$login' and password='$pass'")->fetch_object()->role; // запрос на получение роли по логину и пароли

    if (isset($role)) { // проврека есть в результате выполнения запроса роль, если есть до сохраняем в сессию
        $_SESSION['role'] = $role;
        $_SESSION['login'] = $login;
        if($role == 1)
        {
            header("location: /crud/web/"); // редирект на админку
        } else {
            header("location: report/index.php"); // редирект на отчёты
        }
    } else {
        $fmsg = "Ошибка";
    }
}

if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
    echo "Hello, " , $login . " ";
    echo "Вы вошли ";
    echo "<a href='logout.php' class='btn btn-lg btn-primary' > Выйти </a>";
}

require('mysql/close.php');
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
</head>
<body>



<div class="container">
    <h1>Форма авторизации</h1>
    <form class="form-signin" method="post">
        <input type="text" class="form-control" name="login"
               id="login" placeholder="Введите логин"><br>
        <input type="password" class="form-control" name="pass"
               id="pass" placeholder="Введите пароль"><br>
        <button class="btn btn-lg btn-success btn-block" type="submit">Авторизоваться</button>
        <a href="index.php" class="btn btn-primary btn-block">Регистрация</a>
    </form>
</div>
    
</body>
</html>