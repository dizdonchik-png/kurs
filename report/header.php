<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Отчёты</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,900" rel="stylesheet">
    <link href="../crud/web/resources/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/bootstrap-4.3.1/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="../css/DataTables-1.10.20/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../css/report.css">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>
<body>
<main>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" role="navigation">
        <div class="container-fluid">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb" aria-expanded="true">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navb">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="../crud/web" class="nav-link">Админ-панель</a></li>
                    <li class="nav-item active"><a href="../index.php" class="nav-link">Регистрация</a></li>
                    <li class="nav-item active"><a href="report2.php" class="nav-link">Какие предметы сдавать студенту</a></li>
                    <li class="nav-item active"><a href="report3.php" class="nav-link">Количество мест на факультете</a></li>
                    <li class="nav-item active"><a href="report4.php" class="nav-link">Максимальный балл по предмету</a></li>
                    <li class="nav-item active"><a href="report5.php" class="nav-link">Средний балл для предметов по факультету</a></li>
                    <li class="nav-item active"><a href="report6.php" class="nav-link">Оценки абитуриента</a></li>
                    <li class="nav-item active"><a href="report7.php" class="nav-link">Получить расписание консультаций и экзаменов</a></li>
                    <li class="nav-item active"><a href="report8.php" class="nav-link">Получить экзамены для группы</a></li>
                    <li class="nav-item active"><a href="report9.php" class="nav-link">Получить список абитуриентов на факультет</a></li>
                    <li class="nav-item active"><a href="export.php" class="nav-link">Экспорт и импорт структуры БД</a></li>
                </ul>
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item mr-sm-2"><?php echo (isset($_SESSION["login"])) ? ('<a class="navbar-text"><span>Вы вошли как <strong>' . $_SESSION["login"] . '</strong></span></a>') : ''; ?></li>
<li class="nav-item"><?php echo (isset($_SESSION["login"])) ? '<a href="../logout.php" class="btn btn-outline-danger">Выход</a>' : ''; ?></li>
</ul>
</div>
</header>
