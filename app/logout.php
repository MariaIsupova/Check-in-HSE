<?php
    //Запускаем сессию
    session_start();
    if ($_SESSION['type'] == 2){
        require_once("php/dbconnect.php");
        if (!$connect) {#проверяем все ли ок с соединением к бд
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        $query_id_company = mysqli_query($connect, "SELECT id_company FROM `company` WHERE email_company='{$_SESSION['login']}'");
        $id_company = mysqli_fetch_assoc($query_id_company);
        $id_company = $id_company['id_company'];
        
        $path = "/tmp/";
        $file = "file_company";
        $file .= (string) $id_company;
        $file .= "meetup*.csv";
        foreach (glob($path.$file) as $csvFile) {
            unlink($csvFile);
        }
        
    }
    unset($_SESSION["login"]);
    unset($_SESSION["password"]);
    unset($_SESSION['type']);
    unset($_SESSION['name']);
    // Возвращаем пользователя на ту страницу, на которой он нажал на кнопку выход.
    //header("HTTP/1.1 301 Moved Permanently");
    SetCookie("login", ""); //удаляем cookie с логином 	

    SetCookie("password", ""); //удаляем cookie с паролем 
    $_SESSION = Array();
    session_destroy();
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
?>