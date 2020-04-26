<?php
session_start();
if(!isset($_SESSION["login"])){ // если не задана переменная сессии login, то перейти на страницу авторизации, в противном случае далее выполнять код
    header("location: ../login.php");
    exit;
}
/*
 C:\OSPanel\modules\database\MySQL-5.6\bin\mysqldump.exe --default-character-set=cp1251 --opt --host=127.0.0.1 --port=3306 --user=root institution > backup.sql
 \. back.sql
SELECT * FROM abiturienty INTO OUTFILE 'D:/passwd.txt' fields terminated by ',' enclosed by "" lines terminated by '\r\n';
mysqld.exe -u root
mysql.exe -u root
 */

require_once "../mysql/connect.php";

/*** ЭКСПОРТ. НАЧАЛО ***/
if (isset($_POST['task']) && isset($_POST['fileName']) && $_POST['task'] === "1") {
    $filename = $_POST['fileName'] . "-" . date("d-m-Y") . ".sql";
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $pathToDump = "C:\OSPanel\modules\database\MySQL-5.6\bin\mysqldump.exe";
    $DBUSER = "root";
    $DATABASE = "institution";

    $cmd = "$pathToDump --default-character-set=cp1251 --opt --host=127.0.0.1 --port=3306 --user=$DBUSER $DATABASE";

    passthru($cmd);
    exit;
}
/*** ЭКСПОРТ. КОНЕЦ ***/

/*** ИМПОРТ. НАЧАЛО ***/
else if (isset($_POST['task']) && $_POST['task'] === "2" && isset($_FILES['fileToUpload']['name']) && isset($_POST['nameDb'])) {
    $fileContent = file_get_contents($_FILES['fileToUpload']['tmp_name']);

    $sql = "CREATE DATABASE " . $_POST['nameDb'] . ";";
    if ($mysql->query($sql) === TRUE) {
        $mysql->select_db($_POST['nameDb']);
        $sqlSuccessImport = $mysql->multi_query($fileContent);
        if($sqlSuccessImport) {         // очистка данных
            while ($mysql->more_results() && $mysql->next_result()) {
                $extraResult = $mysql->use_result();
                if ($extraResult instanceof mysqli_result) {
                    $extraResult->free();
                }
            }
        }
    }
}
/*** ИМПОРТ. КОНЕЦ ***/


/*** ЭКСПОРТ ТАБЛИЦЫ В СТРУКТУРИРОВАННЫЙ ФАЙЛ CSV. НАЧАЛО ***/
else if (isset($_POST['task']) && $_POST['task'] === "3") {
    $fileName = $_POST['fileName'];
    $pathToFile = "D:/" . $fileName;
    $sql = "SELECT * FROM " . $_POST['tableName'] . " INTO OUTFILE '$pathToFile' character set cp1251 fields terminated by ',' enclosed by \"\" lines terminated by '\r\n'";
    if ($mysql->query($sql) === TRUE) {
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=cp1251');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($pathToFile));
        readfile($pathToFile);
    }
    exit;
}
/*** ЭКСПОРТ. КОНЕЦ ***/



/*** Импорт (LOAD DATA INFILE .. INTO TABLE) НАЧАЛО ***/
else if (isset($_POST['task']) && $_POST['task'] === "4") {
    $filePath = str_replace("\\", "/", $_FILES['fileToUpload']['tmp_name']);
    $sql = "LOAD DATA INFILE '$filePath' INTO TABLE " . $_POST['tableName'] . " character set cp1251 FIELDS TERMINATED BY ',' ENCLOSED BY \"\" LINES TERMINATED BY '\r\n';";
    $sqlSuccessImport4 = $mysql->query($sql);
}

/*** Импорт (LOAD DATA INFILE .. INTO TABLE). КОНЕЦ ***/

require_once "header.php";
?>
    <main>
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="card mt-2">
                        <div class="card-header text-uppercase"><strong>Экспорт (SELECT ... INTO OUTFILE)</strong></div>
                        <div class="card-body">
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                                <input type="hidden" name="task" value="3">
                                <div class="form-group">
                                    <label>Введите имя экспортируемой таблицы</label>
                                    <input type="text" class="form-control" name="tableName" placeholder="Введите имя таблицы" value="strana" required>
                                </div>
                                <div class="form-group">
                                    <label>Введите имя файла для экспорта</label>
                                    <input type="text" class="form-control" name="fileName" placeholder="Введите имя файла" value="strana.csv" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Выгрузить</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mt-2">
                        <div class="card-header text-uppercase"><strong>Импорт (LOAD DATA INFILE .. INTO TABLE)</strong></div>
                        <div class="card-body">
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="task" value="4">
                                <div class="form-group">
                                    <label>Введите имя таблицы для импорта</label>
                                    <input type="text" class="form-control" name="tableName" placeholder="Введите имя таблицы" value="strana" required>
                                </div>
                                <div class="form-group">
                                    <label>Выберите файл с данными для импорта</label>
                                    <input type="file" class="form-control-file" name="fileToUpload" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Загрузить</button>
                                <?php if($sqlSuccessImport4) { ?>
                                    <h3><span class="badge badge-success">Импортировано успешно!</span></h3>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    <div class="card mt-2">
                        <div class="card-header text-uppercase"><strong>Выгрузка (mysqldump)</strong></div>
                        <div class="card-body">
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                                <input type="hidden" name="task" value="1">
                                <div class="form-group">
                                    <label for="nameFile">Введите имя файла для экспорта</label>
                                    <input type="text" class="form-control" id="nameFile" name="fileName" placeholder="Введите имя файла" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Выгрузить</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mt-2">
                        <div class="card-header text-uppercase"><strong>Импорт (\. back.sql)</strong></div>
                        <div class="card-body">
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="task" value="2">
                                <div class="form-group">
                                    <label for="nameDb">Введите имя новой базы данных</label>
                                    <input type="text" class="form-control" id="nameDb" name="nameDb" placeholder="Имя базы данных" required>
                                </div>
                                <div class="form-group">
                                    <label for="fileToUpload">Выберите файл для импорта</label>
                                    <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Импортировать</button>
                                <?php if($sqlSuccessImport) { ?>
                                    <h3><span class="badge badge-success">Импортировано успешно!</span></h3>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>

<?php
require_once "footer.php";
require_once "../mysql/close.php";
?>