<?php
session_start();
if(!isset($_SESSION["login"])){ // если не задана переменная сессии login, то перейти на страницу авторизации, в противном случае далее выполнять код
    header("location: ../login.php");
    exit;
}
/*
 C:\OSPanel\modules\database\MySQL-5.6\bin\mysqldump.exe --default-character-set=cp1251 --opt --host=127.0.0.1 --port=3306 --user=root institution > backup.sql
 \. back.sql
1. отображение всех таблиц
2. выгрузка определённой таблицы
 */

/*** ЭКСПОРТ. НАЧАЛО ***/
if (isset($_POST['fileName'])) {
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

require_once "../mysql/connect.php";
/*** ИМПОРТ. НАЧАЛО ***/
if (isset($_FILES['fileToUpload']['name']) && isset($_POST['nameDb'])) {
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

require_once "header.php";
?>
    <main>
        <div class="container">
            <div class="card mt-2">
                <div class="card-header text-uppercase"><strong>Выгрузка</strong></div>
                <div class="card-body">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <div class="form-group">
                            <label for="nameFile">Введите имя файла для экспорта</label>
                            <input type="text" class="form-control" id="nameFile" name="fileName" placeholder="Введите имя файла">
                        </div>
                        <button type="submit" class="btn btn-primary">Выгрузить</button>
                    </form>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-header text-uppercase"><strong>Импорт</strong></div>
                <div class="card-body">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nameDb">Введите имя новой базы данных</label>
                            <input type="text" class="form-control" id="nameDb" name="nameDb" placeholder="Имя базы данных">
                        </div>
                        <div class="form-group">
                            <label for="fileToUpload">Выберите файл для импорта</label>
                            <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload">
                        </div>
                        <button type="submit" class="btn btn-primary">Импортировать</button>
                        <?php if($sqlSuccessImport) { ?>
                            <h3><span class="badge badge-success">Импортировано успешно!</span></h3>
                        <?php } ?>
                    </form>
                </div>
            </div>

        </div>

    </main>

<?php
require_once "footer.php";
require_once "../mysql/close.php";
?>