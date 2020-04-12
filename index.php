<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
</head>
<body>
    <?php
    require('mysql/connect.php');

    if(isset($_POST['login']) && isset($_POST['pass'])) {
        $login = $_POST['login'];
        $name = $_POST['name'];
        $pass = $_POST['pass'];

        $query = "INSERT INTO users (`login`, `password`, `fullName`, `role_id`) VALUES('$login', '$pass', '$name', 2)";
        $result = mysqli_query($mysql, $query);

        if($result) {
            $smsg = "Регистрация прошла успешно";
        } else {
            $fsmsg = "Ошибка";
        }
    }

    require('mysql/close.php');
    ?>


    <div class="container">
        <h1>Форма регистрации</h1>
        <form class="form-signin" method="post">
            <?php if(isset($smsg)) { ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php }?>
            <?php if(isset($fsmsg)) { ?><div class="alert alert-danger" role="alert"> <?php echo $fsmsg; ?> </div><?php }?>

            <input type="text" class="form-control" name="login"
                   id="login" placeholder="Введите логин"><br>
            <input type="text" class="form-control" name="name"
                   id="name" placeholder="Введите имя"><br>
            <input type="password" class="form-control" name="pass"
                   id="pass" placeholder="Введите пароль"><br>
            <button class="btn btn-lg btn-success btn-block" type="submit">Зарегистрироваться</button>
            <a href="login.php" class="btn btn-primary btn-block">Авторизоваться</a>
        </form>
    </div>
</body>
</html>