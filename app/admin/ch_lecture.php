<?php
if(isset($_POST["change_lecture"]))
{
    if(!empty($_POST['lecture_name']) && !empty($_POST['id_company']) && !empty($_POST['id_meetup']) && !empty($_POST['l_or_s']) && !empty($_POST['text']))
    {
        require_once("../php/dbconnect.php");
        if (!$connect) {#проверяем все ли ок с соединением к бд
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        
        $lecture_name = mysqli_real_escape_string($connect,$_POST['lecture_name']);
        $id_company = mysqli_real_escape_string($connect,$_POST['id_company']);
        $id_meetup = mysqli_real_escape_string($connect,$_POST['id_meetup']);
        $type = mysqli_real_escape_string($connect,$_POST['l_or_s']);
        $type -= 1;
        $text = mysqli_real_escape_string($connect,$_POST['text']);
        
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        
        $upd="UPDATE lecures SET title='{$name}', date='{$date}', lim_lectures='{$num_lectures}', lim_stands='{$num_stands}' WHERE id='{$lecture['id']}'";
        $res=mysqli_query($connect,$upd);
        if ($res)
        {?>
            <div class="notification">
                <?echo "Лекция успешно обновлена";?>
            </div>
            <?header('Location: http://'.$_SERVER['HTTP_HOST'].'/');?>
        <?}
        else {?>
            <div class="notification">
                <?echo "Не удалось обновить лекцию";?>
            </div>
        <?}
    }
    else 
    {?>
        <div class="notification">
            <?echo "Все поля обязательны для заполнения!";?>
        </div>
        
    <?}
}?>