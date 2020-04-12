<?php

/**
 * Calls a Stored Procedure and returns the results as an array of rows.
 * @param mysqli $dbLink An open mysqli object.
 * @param string $procName The name of the procedure to call.
 * @param string $params The parameter string to be used
 * @return array An array of rows returned by the call.
 */
function c_mysqli_call(mysqli $dbLink, $procName, $params="")
{
    if(!$dbLink) {
        throw new Exception("Соединение MySQLi недействительно.");
    }
    else
    {
        $sql = "CALL {$procName}({$params});"; // присвование переменной скрипта для вызова хранимой процедуры
        $sqlSuccess = $dbLink->multi_query($sql); // выполнить SQL-команду multi_query (выполнить много запросов: можно и несколько, и один)
        if($sqlSuccess) // проверка, успешн ли выполнен запрос
        {
            // получить первый результат выполнения запроса
            $result = $dbLink->use_result();
            $output = array();
            // вставить в строки в результирующий массив
            while($row = $result->fetch_assoc())
            {
                $output[] = $row;
            }
            // очистить память
            $result->free();
            // затем удалить все дополнительные результаты
            while($dbLink->more_results() && $dbLink->next_result())
            {
                $extraResult = $dbLink->use_result();
                if($extraResult instanceof mysqli_result){
                    $extraResult->free();
                }
            }
            return $output; // вернуть массив
        }
        else
        {
            return $dbLink->error; // вернуть ошибку
        }
    }
}

function getMaxOcenka()
{
    global $mysql;

    return c_mysqli_call($mysql, "getMaxOcenka", "");

    return $result;
}

function getAvgBal()
{
    global $mysql;

    return c_mysqli_call($mysql, "getAvgBal", "");

    return $result;
}

function getAbiturientFamiliya() {
    global $mysql;
    $query = "SELECT DISTINCT abiturienty.familiya FROM abiturienty ORDER BY abiturienty.familiya asc;";
    if (!$result = $mysql->query($query)) {
        die ('При извлечении записей возникла ошибка: '.$mysql->errno.' - '.$mysql->error);
    }

    return $result;
}

function getGroupName() {
    global $mysql;
    $query = "SELECT gruppy.name FROM gruppy;";
    if (!$result = $mysql->query($query)) {
        die ('При извлечении записей возникла ошибка: '.$mysql->errno.' - '.$mysql->error);
    }

    return $result;
}

function getPredmetName() {
    global $mysql;
    $query = "SELECT predmety.name FROM predmety;";
    if (!$result = $mysql->query($query)) {
        die ('При извлечении записей возникла ошибка: '.$mysql->errno.' - '.$mysql->error);
    }

    return $result;
}

function getFacultet() {
    global $mysql;
    $query = "SELECT fakultety.name FROM fakultety;";
    if (!$result = $mysql->query($query)) {
        die ('При извлечении записей возникла ошибка: '.$mysql->errno.' - '.$mysql->error);
    }

    return $result;
}

function getOcenkiAbiturienta($familiya, $group)
{
    global $mysql;

    return c_mysqli_call($mysql, "getOcenkiAbiturienta", "'{$familiya}', '{$group}'");

    return $result;
}

function getRaspisanieConsultAndExzamenov($familiya, $group, $predmet)
{
    global $mysql;

    return c_mysqli_call($mysql, "getRaspisanieConsultAndExzamenov", "'{$familiya}', '{$group}', '{$predmet}'");

    return $result;
}

function getExzameny($group)
{
    global $mysql;

    return c_mysqli_call($mysql, "getExzameny", "'{$group}'");

    return $result;
}

function getSpisokAbitNaFacultet($facultet)
{
    global $mysql;

    return c_mysqli_call($mysql, "getSpisokAbitNaFacultet", "'{$facultet}'");

    return $result;
}
?>

