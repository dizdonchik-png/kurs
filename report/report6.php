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
                <h2>Оценки абитуриента</h2>
                <form id="search" name="search" method="post" action="" class="form-inline">
                    <div class="col-xs-12 col-sm-3 col-md-3">
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
                    <div class="col-xs-12 col-sm-3 col-md-3">
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
                    <th>Предмет</th>
                    <th>Оценка</th>
                    <th>Номер группы</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $familiya = $_POST['familiya'];
                $group = $_POST['group'];
                if (isset($familiya) && isset($group)) {
                    $result = getOcenkiAbiturienta($familiya, $group);
                    if($result)
                    {
                        foreach ($result as $row) {
                            echo '<tr><td>' . $row['abiturientyName'] . '</td><td>' . $row['familiya'] . '</td><td>' . $row['predmetyName'] . '</td><td>' . $row['ocenka'] . '</td><td>' . $row['gruppyName'] . '</td></tr>';
                        }
                    }
                    else
                    {
                        echo '<tr><td colspan="5" >Нету данных</td></tr>';
                    }
                }
                else {
                    echo '<tr><td colspan="5" >Нажмите кнопку НАЙТИ</td></tr>';
                }
                ?>
                </tbody>
                <tfoot class="thead-light">
                <tr>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Предмет</th>
                    <th>Оценка</th>
                    <th>Номер группы</th>
                </tr>
                </tfoot>
            </table>
        </div>

    </main>

<?php
require_once "footer.php";
require_once "../mysql/close.php";
?>