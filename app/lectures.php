<?php include "functions.php";?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lectures</title>

    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style_event.css">
    <link rel="stylesheet" href="../css/modal_window_style.css">


</head>
<?php
session_start();
require_once("php/dbconnect.php");
$lectures_by_id = mysqli_query($connect, "SELECT * FROM `lectures` WHERE id_meetup = '{$_GET['id_meetup']}' ");




if (!$connect) {#проверяем все ли ок с соединением к бд
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
session_start();
$que = mysqli_query($connect,"SELECT * FROM meetups");
?>

<body>
    <div class="modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-header">
                <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body"> 
                <div class="n">
                Вы действительно хотите удалить мероприятие?
                </div>
                <div class="modal-footer">
                    <form method='post'>
                        <input name='delete_confirm', type='submit', value='Подтвердить'>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
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

         <div class="fio">
            <!-- Здесь имя участника-->
            <? 
            if ($_SESSION['name'] != '')
            { 
                print($_SESSION['name']);
            }
            else{
                print('НИУ ВШЭ');
            }
            ?>
            <div class="decor"></div>
        </div>
        
        <div class="button-list">
           <div class="back" onclick="location.href='events.php'">
               <img class="picture" src="../img/back.svg" alt="lkft">
               Назад к мероприятиям
            </div>
        <? if ($_SESSION['type'] == 1){?>
            <form method="post">
                <input name='sign_up', type='submit', value='Записаться'>
            </form>
        <?}
        if ($_SESSION['type'] == 2){?>
            <form method="post">
                <input name='get_data', type='submit', value='Выгрузить данные'>
            </form>
        <?}
        if ($_SESSION['type'] == 3){?>
            <form method="post">
                <input name='get_admin_data', type='submit', value='Выгрузить данные'>
            </form>
            <div onclick="location.href='#'" class="btn-big openmodal">
                <form method="post">
                    <input name='delete_meetup', type='submit', value='Удалить мероприятие'>
                </form>
            </div>
            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script src="modal_window.js"></script> 
            
            <div onclick="location.href='/admin/change_meetup.php?id_meetup=<?=$_GET['id_meetup'];?>' ">
                <input name='change_meetup' type='submit', value='Изменить мероприятие'>
            </div>
        <?}
        
        if(isset($_POST['delete_confirm'])){
            $id_meetup=$_GET['id_meetup'];
            //удаляем из meetups
            $delete_meetups = "DELETE FROM `meetups` WHERE id_meetup='{$id_meetup}'";
            $res1=mysqli_query($connect, $delete_meetups);
            //удаляем из lectures
            $delete_meetups = "DELETE FROM `lectures` WHERE id_meetup='{$id_meetup}'";
            $res2=mysqli_query($connect, $delete_meetups);
            //удаляем из list_of_students
            $delete_meetups = "DELETE FROM `list_of_students` WHERE id_events='{$id_meetup}'";
            $res3=mysqli_query($connect, $delete_meetups);
            //удаляем из list_for_company
            $delete_meetups = "DELETE FROM `list_for_company` WHERE id_meetup='{$id_meetup}'";
            $res4=mysqli_query($connect, $delete_meetups);
            if($res1 && $res2 && $res3 && $res4){?>
                <div class="notification"><?echo "Мероприятие успешно удалено";?></div>
            <?}
            else {?>
                <div class="notification"><?echo "Не удалось удалить мероприятие";?></div>
            <?}
            //удаляем файл с результатами мероприятия
            $path = "/tmp/";
            $file = "file_meetup";
            $file .= (string) $id_meetup;
            $file .= ".csv";
            unlink($path.$file);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
        }?>
        </div>
        
        <?
        if(isset($_POST['sign_up']))
        {
            if ($_SESSION['name'] != ''){
                $stud_login = $_SESSION['login'];
                $id_meetup = $_GET['id_meetup'];
                $num = 0;
                $query = mysqli_query($connect, "SELECT * FROM list_of_students WHERE login = '{$stud_login}' AND id_events='{$id_meetup}'");
                $numr=mysqli_num_rows($query);
                $stud_log = mysqli_fetch_assoc( $query );
                if ($numr != 0){?>
                    <div class="notification"><?echo "Вы уже записаны на это мероприятие";?></div>
                <?}
                else{
                    $sql_q1 = "INSERT INTO `list_of_students` (id_events, login, num_of_lectures) VALUES ('{$id_meetup}', '{$stud_login}', '{$num}')";
                    $res=mysqli_query($connect, $sql_q1);
                    if($res){?>
                        <div class="notification"><?echo "Вы успешно записались на мероприятие";?></div>
                    <?}
                    else {?>
                        <div class="notification"><?echo "Не удалось записаться";?></div>
                    <?}
                }
            }
            else{?>
               <div class="notification"><? print('Сначала зарегестрируйтесь!');?></div>
           <? }
        }
        
        
        if(isset($_POST['get_data'])){
            $id_meetup = $_GET['id_meetup'];
            
            $query_id_company = mysqli_query($connect, "SELECT id_company FROM `company` WHERE name_company='{$_SESSION['name']}'");
            $id_company = mysqli_fetch_assoc($query_id_company);
            $id_company = $id_company['id_company'];
            
            //set name of file for company
            $path = "/tmp/";
            $file = "file_company";
            $file .= (string) $id_company;
            $file .= "meetup";
            $file .= (string) $id_meetup;
            $file .= ".csv";
            
            $output = fopen($path.$file,"a+");
            file_put_contents($path.$file, "");
            fwrite($output, "\xEF\xBB\xBF");
            
            fputcsv($output, array('Название лекции/стенда', 'email', 'ФИО', 'Университет', 'Группа'), $delimiter = ';');
            
            $query_students = mysqli_query($connect, "SELECT * FROM `list_for_company` WHERE  id_company = '{$id_company}' AND id_meetup = '{$id_meetup}'");
            while($data = mysqli_fetch_assoc($query_students)){
                $id_lecture = $data['id_lecture'];
                $query_name_lecture = mysqli_query($connect, "SELECT * FROM `lectures` WHERE id = '{$id_lecture}'");
                $name_lecture = mysqli_fetch_assoc($query_name_lecture);
                $name_lecture = $name_lecture['title'];
                
                $email_stud = $data['email_stud'];
                $query_students_data = mysqli_query($connect, "SELECT * FROM `students` WHERE  email='{$email_stud}'");
                $stud_data = mysqli_fetch_assoc($query_students_data);
                $fio_stud = $stud_data['FIO'];
                $univer_stud = $stud_data['university'];
                $group_stud = $stud_data['student_group'];
                $results = array($name_lecture, $email_stud, $fio_stud, $univer_stud, $group_stud);
                fputcsv($output, $results, $delimiter = ';');
            }
            fclose($output);//закрываем файл.
            header('Content-Type: '.$ctype.'; charset=utf-8');
            header("Content-Disposition: attachment; filename=".$file);
            ob_clean();
            readfile($path.$file);
            exit();
        }
        
        if(isset($_POST['get_admin_data'])){
            $id_meetup = $_GET['id_meetup'];
            $query = mysqli_query($connect, "SELECT * FROM `list_of_students` WHERE id_events='{$id_meetup}'");
            //;
            $path = "/tmp/";
            $file = "file_meetup";
            $file .= (string) $id_meetup;
            $file .= ".csv";
            
            $output = fopen($path.$file,"a+");
            file_put_contents($path.$file, "");
            fwrite($output, "\xEF\xBB\xBF");
            
            fputcsv($output, array('email', 'ФИО', 'Университет', 'Группа', 'Количество лекций', 'Количество стендов'), $delimiter = ';');
            while($data = mysqli_fetch_assoc($query)){
                $num_lecture = $data['num_of_lectures'];
                $num_stands = $data['num_of_stands'];
                $login_stud = $data['login'];
                $query_students_data = mysqli_query($connect, "SELECT * FROM `students` WHERE  email='{$login_stud}'");
                $stud_data = mysqli_fetch_assoc($query_students_data);
                $fio_stud = $stud_data['FIO'];
                $univer_stud = $stud_data['university'];
                $group_stud = $stud_data['student_group'];
                $results = array($login_stud, $fio_stud, $univer_stud, $group_stud, $num_lecture, $num_stands);
                fputcsv($output, $results, $delimiter = ';');
            }
            fclose($output);//закрываем файл.
            header('Content-Type: '.$ctype.'; charset=utf-8');
            header("Content-Disposition: attachment; filename=".$file);
            ob_clean();
            readfile($path.$file);
            exit();
        }
        ?>
        <?php
        $page_content = render('template_lectures.php', array('lecture' => $lectures_by_id));
        print($page_content);
        ?>

</body>
</html>
