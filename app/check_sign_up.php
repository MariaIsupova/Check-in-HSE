<?
// Скрипт проверки
session_start();
// Соединямся с БД
require_once("php/dbconnect.php");
if ($_SESSION['type'] == 1){
    $q = mysqli_query($connect, "SELECT * FROM list_of_students WHERE login = '{$_SESSION['login']}' ");
    $student = mysqli_fetch_assoc($q);
    if ($student != '') {
        header("Location: lectures.php"); 
    }
    else{
        print('Вы не записались на это мероприятие!');
    }
    
}
if ($_SESSION['type'] == 2){
    header("Location: lectures.php"); 
}?>