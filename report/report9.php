<?php
session_start();

if(!isset($_SESSION["login"])){ // если не задана переменная сессии login, то перейти на страницу авторизации, в противном случае далее выполнять код
    header("location: ../login.php");
    exit;
}

require_once "../mysql/connect.php";
require_once "../mysql/functions.php";
require_once "header.php";
?>
<main>
    <div id="block-top">
        <div class="container">
            <h2>Получить список абитуриентов на факультет</h2>
            <form id="search" name="search" method="post" action="" class="form-inline">
                <div class="col-xs-12 col-sm-1 col-md-3">
                    <p class="itemName">Факультет:</p>
                    <div class="form-group">
                        <select id="facultet" name="facultet" class="form-control" required>
                            <?php
                            $result = getFacultet();
                            $rowCount = $result->num_rows;
                            if($rowCount > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if($_POST['facultet'] === $row['name']) {
                                        echo '<option value="' . $row['name'] . '" selected>' . $row['name'] . '</option>';
                                    }
                                    else {
                                        echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                    }
                                }
                            } else {
                                echo '<option value="-1">Факультеты недоступны</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-xs-offset-0 col-sm-3 col-sm-offset-0 col-md-3 col-md-offset-0">
                    <input id="search-btn" type="submit" class="btn btn-outline-success" value="НАЙТИ" />
                </div>
            </form>
        </div>
    </div>

    <div class="container-fluid mt-2">
        <table id="requestsTable" class="table table-hover table-sm table-bordered text-center">
            <!--Table head-->
            <thead class="thead-light">
            <tr>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Факультет</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $facultet = $_POST['facultet'];
            if (isset($facultet)) {
                $result = getSpisokAbitNaFacultet($facultet);
                if($result)
                {
                    foreach ($result as $row) {
                        echo '<tr><td>' . $row['abiturientName'] . '</td><td>' . $row['familiya'] . '</td><td>' . $row['fakultetName'] . '</td></tr>';
                    }
                }
                else
                {
                    echo '<tr><td colspan="3" >Нету данных</td></tr>';
                }
            }
            else {
                echo '<tr><td colspan="3" >Нажмите кнопку НАЙТИ</td></tr>';
            }
            ?>
            </tbody>
            <tfoot class="thead-light">
            <tr>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Факультет</th>
            </tr>
            </tfoot>
        </table>
    </div>

</main>

<?php
require_once "footer.php";
require_once "../mysql/close.php";
?>
