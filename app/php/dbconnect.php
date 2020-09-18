
<?php
    // Указываем кодировку
    header('Content-Type: text/html; charset=utf-8');
 
    $server = "81.90.180.168";
    $username = "adminhse";
    $password = "123456qQ";
    $database = "events";
     
    // Подключение к базе данный через MySQLi
    $connect = new mysqli($server, $username, $password, $database);
 
    // Проверяем, успешность соединения. 
    if (mysqli_connect_errno()) { 
        echo "<p><strong>Ошибка подключения к БД</strong>. Описание ошибки: ".mysqli_connect_error()."</p>";
        exit(); 
    }
 
    // Устанавливаем кодировку подключения
    $connect->set_charset('utf8');
 
    //Для удобства, добавим здесь переменную, которая будет содержать название нашего сайта
    $address_site = "http://eventshse.h1n.ru/index.php";
?>