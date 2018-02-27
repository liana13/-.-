<?php
ini_set('memory_limit', -1);
ini_set('max_execution_time', -1);

$config = require(__DIR__ . '/../config/db.php');

$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];
try {
    $conn = new PDO($dsn, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");

    $sql = "SELECT id,
    	user_id,
    	object_id,
        status,
        (SELECT percent FROM coefficient WHERE object_id=finance.object_id) as coeff,
        (SELECT datefrom FROM coefficient WHERE object_id=finance.object_id) as datefrom,
    	created_at, price FROM finance";
    $stmt = $conn->query($sql);
    if($stmt) {
        $rows = $stmt->fetchAll();
        $data = '<table border="1">';
        $data .= '<thead><tr>
        <th>Аккаунт</th><th>Номер объекта</th><th>Название объекта</th><th>Дата допуска</th>
        <th>Ставка</th><th>Дата выставления счета</th><th>Сумма счета</th><th>Оплачено</th><th>Тип пользователя</th><th>Номер телефона</th>
        <th>ФИО</th><th>Название организации</th><th>Название ИП</th><th>Адрес</th>
        <th>ИНН</th><th>Адрес местожительства</th><th>Телефоны</th><th>Эл. почта</th><th>Примечание</th>
        </tr></thead>';

        foreach ($rows as $row) {
            $data .= '<tr>';
            $data .= '<td>'.$row["user_id"].'</td>';
            $data .= '<td>'.$row["object_id"].'</td>';

            $objid = $row["object_id"];
            $sqlobj = "SELECT title, service, person_id, act_oplata,
            (SELECT aliastwo FROM servis WHERE id=object.service) as servicealias
            FROM object WHERE id='$objid'";
            $stmtobj = $conn->query($sqlobj);
            $row_obj = $stmtobj->fetch();

            $data .= '<td>'.$row_obj["servicealias"].' '.$row_obj["title"].'</td>';
            $data .= '<td>'.explode(' ',$row_obj["act_oplata"])[0].'</td>';

            if ($row["coeff"]) {
                $coeff = $row["coeff"].'% (от '.$row["datefrom"].')';
            } else {
                $coeff = '10%';
            }
            $data .= '<td>'.$coeff.'</td>';
            $data .= '<td>'.explode(' ',$row["created_at"])[0].'</td>';
            $data .= '<td>'.$row["price"].'</td>';
            if($row["status"] == 1) {
                $status = 'оплачено';
            } else {
                $status = 'не оплачено';
            }
            $data .= '<td>'.$status.'</td>';

            $user_id = $row_obj["person_id"];
            $sqluser = "SELECT * FROM person WHERE id='$user_id'";
            $stmtuser = $conn->query($sqluser);
            $row_user = $stmtuser->fetch();

            if($row_user["type"] == 1) {
                $lico = 'Юр. лицо';
            } elseif($row_user["type"] == 2) {
                $lico = 'ИП';
            } elseif($row_user["type"] == 3) {
                $lico = 'Физ.лицо';
            }
            $data .= '<td>'.$lico.'</td>';
            $data .= '<td>'.$row_user["phone"].'</td>';
            $data .= '<td>'.$row_user["fio"].'</td>';
            $data .= '<td>'.$row_user["name_org_1"].'</td>';
            $data .= '<td>'.$row_user["name_org_2"].'</td>';
            $data .= '<td>'.$row_user["address"].'</td>';
            $data .= '<td>'.$row_user["inn"].'</td>';
            $data .= '<td>'.$row_user["address_mestozhitelstvo"].'</td>';
            $data .= '<td>'.$row_user["tphone"].'</td>';
            $data .= '<td>'.$row_user["email"].'</td>';
            $data .= '<td>'.$row_user["priming"].'</td>';
            $data .= '</tr>';
        }
        $data .= '</table>';
        header('Content-Type: text/x-csv; charset=utf-8');
        header("Content-Disposition: attachment;filename=".date("d-m-Y")."-finance-export.xlsx");
        header("Content-Transfer-Encoding: binary ");
        $csv_output ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
        <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="Andrey" />
        <title>deamur zapishi.net</title>
        </head>
        <body>';
        $csv_output .=$data;
        $csv_output .='</body></html>';
        echo $csv_output;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
