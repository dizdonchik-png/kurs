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
                <h2>Средний балл для предметов по факультету</h2>
            </div>
        </div>

        <div class="container-fluid mt-2">
            <table id="requestsTable" class="table table-hover table-sm table-bordered text-center">
                <!--Table head-->
                <thead class="thead-light">
                <tr>
                    <th>Предмет</th>
                    <th>Название факультета</th>
                    <th>Средний балл</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $result = getAvgBal();
                if ($result)
                {
                    foreach ($result as $row) {
                        echo '<tr><td>' . $row['predmetyName'] . '</td><td>' . $row['fakultetyName'] . '</td><td>' . $row['sredniBal'] . '</td></tr>';
                    }
                }
                else
                {
                    echo '<tr><td colspan="3" >Нету данных</td></tr>';
                }
                ?>
                </tbody>
                <tfoot class="thead-light">
                <tr>
                    <th>Предмет</th>
                    <th>Название факультета</th>
                    <th>Средний балл</th>
                </tr>
                </tfoot>
            </table>
        </div>

    </main>

<?php
require_once "footer.php";
require_once "../mysql/close.php";
?>