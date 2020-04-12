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
                <h2>Конкурс на факультеты</h2>
            </div>
        </div>

        <div class="container-fluid mt-2">
            <table id="requestsTable" class="table table-hover table-sm table-bordered text-center">
                <!--Table head-->
                <thead class="thead-light">
                <tr>
                    <th>Факультет</th>
                    <th>Количество мест</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /* $requests = getRequestsForAgent($agentId);
                if ($requests)
                {
                    foreach ($requests as $req) {
                        echo '<tr><td scope="row">' . $req['requestId'] . '</td><td>' . $req['createdDate'] . '</td><td>' . $req['tourName'] . '</td><td>' . $req['hotelName'] . '</td>
<td>' . $req['dateDeparture'] . '</td><td>' . $req['touristName'] . ' +' . $req['touristCount'] . '</td><td class="word-wrap"><span class="badge '.$stateClass.'">'.$stateText.'</span></td><td>' . $req['paymentTour'] . 'р.-' . ($req['isBill'] === "1" ? "да" : "нет"). '</td>
<td>' . ($req['isReport'] === "1" ? "Да" : "Нет") . '</td><td>' . $req['tourCost'] . '</td><td>' . $req['commission'] . '</td><td><button data-toggle="modal" data-target="#modal-editingRequest" class="btn btn-primary btn-sm mt-1" data-id="'. $req['requestId'] .'" '.($isEditBtn ? '' : 'disabled').'>Редактировать</button>
<button onclick="CancelRequest('.$req['requestId'].')" class="btn btn-danger btn-sm mt-1" '.($isCancelBtn ? '' : 'disabled').'>Аннулировать</button> <button onclick="RestoreRequest('.$req['requestId'].')" class="btn btn-warning btn-sm mt-1" '.($isRestoreBtn ? '' : 'disabled').'>Восстановить</button></td></tr>';
                    }
                }
                else
                {
                    echo '<tr><td colspan="12" >Нету данных</td></tr>';
                }
                */

                $sql = "SELECT fakultety.name AS fakultetyName, fakultety.kolichestvo_mest FROM fakultety ORDER BY fakultetyName asc;";
                $query = $mysql->query($sql);
                $rowCount = $query->num_rows;
                if($rowCount > 0){
                    while($row = $query->fetch_assoc()){
                        echo '<tr><td>' . $row['fakultetyName'] . '</td><td>' . $row['kolichestvo_mest'] . '</td></tr>';
                    }
                }
                ?>
                </tbody>
                <tfoot class="thead-light">
                <tr>
                    <th>Факультет</th>
                    <th>Количество мест</th>
                </tr>
                </tfoot>
            </table>
        </div>

    </main>

<?php
require_once "footer.php";
require_once "../mysql/close.php";
?>