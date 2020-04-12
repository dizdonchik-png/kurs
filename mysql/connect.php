<?php
    $mysql = mysqli_connect('localhost', 'root', '','institution');

    // Проверка соединения
    if($mysql->connect_error) {
        die('Соединение не удалось: Код ошибки: '.$mysql->connect_errno.' - '.$mysql->connect_error);
    }
    // Установка кодировки соединения
    if(!$mysql->set_charset("utf8")) {
        die('Ошибка при загрузке набора символов utf8: '.$mysql->errno.' - '.$mysql->error);
    }
?>