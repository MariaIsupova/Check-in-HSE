<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lectures</title>

    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style_info.css">
    <link rel="stylesheet" href="../css/modal_window_style.css">

</head>
<?php
require_once("php/dbconnect.php");
session_start();?>

<body>
    
    <div class="user_area">

        <div class="block_exit">
            <?       
            if ($_SESSION['name'] != '')
            {?>
            <a href='logout.php?action=exit'>
                <div class="exit">
                    Выход
                </div>
                <img class="picture_exit" src="../img/ikon1.svg" alt="lkft">
            </a>
            <?}
            else{
            ?>
            <a href='index.php'>
                <div class="exit">
                    Вход
                </div>
                <img class="picture_in" src="../img/ikon2.svg" alt="lkft">
            </a>
            <? } ?>

        </div>
        <a href='lectures.php?id_meetup=<?=$_GET['id_meetup'];?>'>
            <img class="picture" src="../img/lec_back.svg" alt="lkft">
        </a>
        <? 
        $query = mysqli_query($connect, "SELECT * FROM `lectures` WHERE id='{$_GET['id_lecture']}'");
        $numr = mysqli_num_rows($query);
        if($numr != 0)
        {
            if ($_SESSION['type'] == 1)
            {
                //$q_id_meetup=mysqli_query($connect,"SELECT id_meetup FROM `lectures` WHERE id ='{$_GET['id_lecture']}'");
                //$id_meetup=mysqli_fetch_assoc($q_id_meetup);
                if($_SESSION['login'])
                {?>
                    <div class="code_form">
                        <form method="post">
                            <input name="confirm_participation" , value="Подтвердить" , type="submit">
                        </form>
                    </div>
                    
                <?}
            }
            if ($_SESSION['name'] == '')
            {?>
                <div class="notification">
                    <a href='index.php'>
                        Если хочешь подтвердить прохождение лекции, авторизуйся и не забудь записаться на мероприятие.
                    </a>
                </div>
            <?}
        }
        else
        {?>
            <div class="notification">
            <?echo "Данной лекции/стенда не существует";?>
        </div>
        <?}
        if (isset($_POST["confirm_participation"])){
            $q_check_student=mysqli_query($connect,"SELECT login  FROM list_of_students WHERE id_events = '{$_GET['id_meetup']}' AND login ='{$_SESSION['login']}'");
            $numr=mysqli_num_rows($q_check_student);
            if($numr != 0){
                $q_lecture=mysqli_query($connect,"SELECT code, id_meetup, id_company, type  FROM `lectures` WHERE id ='{$_GET['id_lecture']}'");
                $lectures=mysqli_fetch_assoc($q_lecture);
                $q_name_stud=mysqli_query($connect,"SELECT * FROM `list_for_company` WHERE id_lecture ='{$_GET['id_lecture']}' AND email_stud = '{$_SESSION['login']}' ");
                $numr=mysqli_num_rows($q_name_stud);
                $student=mysqli_fetch_assoc($q_name_stud);
                if ($numr == 0)
                {
                    $id_lecture=mysqli_real_escape_string($connect, $_GET['id_lecture']);
                    $email_stud=mysqli_real_escape_string($connect,$_SESSION['login']);
                    $company = $lectures['id_company'];
                    $id_meetup = $lectures['id_meetup'];
                    $sql_q1="INSERT INTO `list_for_company` (id_lecture, email_stud, id_company, id_meetup) VALUES ('{$id_lecture}','{$email_stud}', '{$company}', '{$id_meetup}')";
                    $res1=mysqli_query($connect, $sql_q1);
                    if ($lectures['type'] == 0)
                    {
                        $sql_q1="UPDATE `list_of_students` SET num_of_lectures=num_of_lectures+1 WHERE id_events = '{$lectures['id_meetup']}' AND login ='{$_SESSION['login']}'";
                        $res1=mysqli_query($connect, $sql_q1);
                    }
                    if ($lectures['type'] == 1)
                    {
                        $sql_q1="UPDATE `list_of_students` SET num_of_stands=num_of_stands+1 WHERE id_events = '{$lectures['id_meetup']}' AND login ='{$_SESSION['login']}'";
                        $res1=mysqli_query($connect, $sql_q1);
                    }

                    if($res1){?>
                        <div class="notification">
                            <?echo "Прохождение лекции подтверждено";?>
                        </div>
                    <?}

                    else {?>
                        <div class="notification">
                            <?echo "Не удалось записать данные";?>
                        </div>
                    <?}    

                }
                else{?>
                    <div class="notification">
                        <?echo "Вы уже записаны";?>
                    </div>
                <?}
            }
            else{?>
                <div class="notification">
                    <?echo "Запишитесь на мероприятие";?>
                </div>
            <?}
        }
        ?>
    </div>
</body>
</html>