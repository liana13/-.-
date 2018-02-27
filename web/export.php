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
    	title,
    	service,
        person_id,
        (SELECT aliastwo FROM servis WHERE id=object.service) as servicealias,
    	user_id,
        end_date,
    	tarif_id FROM object";
    $stmt = $conn->query($sql);
    if($stmt) {
        $rows = $stmt->fetchAll();
        $data = '<table border="1">';
        $data .= '<thead><tr>
        <th>Аккаунт</th><th>Номер объекта</th><th>Название</th>
        <th>Тариф</th><th>Оплачено до</th><th>Тип пользователя</th><th>Номер телефона</th>
        <th>ФИО</th><th>Название организации</th><th>Название ИП</th><th>Адрес</th>
        <th>ИНН</th><th>Адрес местожительства</th><th>Телефоны</th><th>Эл. почта</th><th>Примечание</th>
        </tr></thead>';
        foreach ($rows as $row) {
            $data .= '<tr>';
            $data .= '<td>'.$row["user_id"].'</td>';
            $data .= '<td>'.$row["id"].'</td>';
            $data .= '<td>'.$row["servicealias"].' '.$row["title"].'</td>';
            $tarif = 'Бесплатник';
            if($row["tarif_id"] > '0' && $row["tarif_id"] < '4') {
                if ($row["tarif_id"] == '1') {
                    $tarif = 'Тариф №3';
                } elseif ($row["tarif_id"] == '2') {
                    $tarif = 'Тариф №2';
                } elseif ($row["tarif_id"] == '3') {
                    $tarif = 'Тариф №1';
                }
            } elseif ($row["tarif_id"] == '4') {
                $tarif = 'Онлайн бронирование';
            }
            $data .= '<td>'.$tarif.'</td>';
            if($row["end_date"] != '0000-00-00 00:00:00') {
                $data .= '<td> '.$row["end_date"].' </td>';
            } else {
                $data .= '<td> </td>';
            }

            $user_id = $row["person_id"];
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
        header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.xlsx");
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
