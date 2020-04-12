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
                <h2>Получить расписание консультаций и экзаменов</h2>
                <form id="search" name="search" method="post" action="" class="form-inline">
                    <div class="col-xs-12 col-sm-1 col-md-3">
                        <p class="itemName">Фамилия:</p>
                        <div class="form-group">
                            <select id="familiya" name="familiya" class="form-control" required>
                                <?php
                                $result = getAbiturientFamiliya();
                                $rowCount = $result->num_rows;
                                if($rowCount > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        if($_POST['familiya'] === $row['familiya']) {
                                            echo '<option value="' . $row['familiya'] . '" selected>' . $row['familiya'] . '</option>';
                                        }
                                        else {
                                            echo '<option value="' . $row['familiya'] . '">' . $row['familiya'] . '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="-1">Фамилии недоступны</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-1 col-md-3">
                        <p class="itemName">Группа:</p>
                        <div class="form-group">
                            <select id="group" name="group" class="form-control" required>
                                <?php
                                $result = getGroupName();
                                $rowCount = $result->num_rows;
                                if($rowCount > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        if($_POST['group'] === $row['name']) {
                                            echo '<option value="' . $row['name'] . '" selected>' . $row['name'] . '</option>';
                                        }
                                        else {
                                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="-1">Группы недоступны</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-1 col-md-3">
                        <p class="itemName">Предмет:</p>
                        <div class="form-group">
                            <select id="predmet" name="predmet" class="form-control" required>
                                <?php
                                $result = getPredmetName();
                                $rowCount = $result->num_rows;
                                if($rowCount > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        if($_POST['predmet'] === $row['name']) {
                                            echo '<option value="' . $row['name'] . '" selected>' . $row['name'] . '</option>';
                                        }
                                        else {
                                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="-1">Предметы недоступны</option>';
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
                    <th>Тип расписания</th>
                    <th>Дата проведения</th>
                    <th>Аудитория</th>
                    <th>Фамилия</th>
                    <th>Номер группы</th>
                    <th>Предмет</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $familiya = $_POST['familiya'];
                $group = $_POST['group'];
                $predmet = $_POST['predmet'];
                if (isset($familiya) && isset($group) && isset($predmet)) {
                    $result = getRaspisanieConsultAndExzamenov($familiya, $group, $predmet);
                    if($result)
                    {
                        foreach ($result as $row) {
                            echo '<tr><td>' . $row['tipRaspisaniya'] . '</td><td>' . $row['dataProvedenia'] . '</td><td>' . $row['auditoriya'] . '</td><td>' . $row['familiya'] . '</td><td>' . $row['gruppaName'] . '</td><td>' . $row['predmetName'] . '</td></tr>';
                        }
                    }
                    else
                    {
                        echo '<tr><td colspan="6" >Нету данных</td></tr>';
                    }
                }
                else {
                    echo '<tr><td colspan="6" >Нажмите кнопку НАЙТИ</td></tr>';
                }
                ?>
                </tbody>
                <tfoot class="thead-light">
                <tr>
                    <th>Тип расписания</th>
                    <th>Дата проведения</th>
                    <th>Аудитория</th>
                    <th>Фамилия</th>
                    <th>Номер группы</th>
                    <th>Предмет</th>
                </tr>
                </tfoot>
            </table>
        </div>

    </main>

<?php
require_once "footer.php";
require_once "../mysql/close.php";
?>