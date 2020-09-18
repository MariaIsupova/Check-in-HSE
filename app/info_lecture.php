<? include "/admin/delete_lectures_or_stands.php"?>
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
    <div class="modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-header">
                <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
            </div>
            <div class="modal-body"> 
                <div class="n">
                Вы действительно хотите удалить лекцию/стенд?
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
            <?}?>

        </div>
        
        <a href='lectures.php?id_meetup=<?=$_GET['id_meetup'];?>'>
            <img class="picture" src="../img/lec_back.svg" alt="lkft">
        </a>
        
        <div class="title_name">
            Подробнее о лекции/стенде
            <div class="decor"></div>
        </div>
        
        <div class="button-list">
        <?if ($_SESSION['type'] == 3){?>
            <div onclick="location.href='#'" class="btn-big openmodal">
                <form method="post">
                   <input name='delete_lecture_or_stand', type='submit', value='Удалить'>
                </form>
            </div>
            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script src="modal_window.js"></script>
                <div onclick="location.href='/admin/change_lecture_or_stand.php?id_lecture=<?=$_GET['id_lecture'];?>' ">
                    <input name="change_lecture_or_stand" type='submit', value='Изменить'>
                </div>
           
        <?}
        
        if(isset($_POST['delete_confirm'])){
        	$id_lecture=$_GET['id_lecture'];
        	$query=mysqli_query($connect, "SELECT type FROM `lectures` WHERE id='{$id_lecture}'");
        	$type=mysqli_fetch_assoc($query);
        	$type=$type['type'];
        	
        	//удаляем из lectures
        	$delete_lecture = "DELETE FROM `lectures` WHERE id='{$id_lecture}'";
        	$res1=mysqli_query($connect, $delete_lecture);
        	//удаляем из list_for_company
        	$delete_lecture = "DELETE FROM `list_for_company` WHERE id_lecture='{$id_lecture}'";
        	$res2=mysqli_query($connect, $delete_lecture);
        	
        	if($res1 && $res2){
        		if($type==0){?>
        			<div class="notification"><?echo "Лекция успешно удалена";?></div>
        		<?}
        		else{
        			if($type==1){?>
        				<div class="notification"><?echo "Стенд успешно удален";?></div>
        			<?}
        		}
        // 		$path = "../admin/qr_codes/";
        // 		$file = "qr_code";
        //      $file .= (string) $id_lecture;
        //      $file .= ".png";
        //      unlink($path.$file);
        	}
        	else {
        		if($type==0){?>
        			<div class="notification"><?echo "Не удалось удалить лекцию";?></div>
        		<?}
        		else{
        			if($type==1){?>
        				<div class="notification"><?echo "Не удалось удалить стенд";?></div>
        			<?}
        		}
        	}
        	
        	$str = "Location: http://";
        	$str .= (string) $_SERVER['HTTP_HOST'];
        	$str .= "/lectures.php?id_meetup=";
        	$str .= (string) $_GET['id_meetup'];
        	header($str);
        }?>
        </div> 
        
        
        
        <div class="lect_info">
           <? 
            $q_info_lecture=mysqli_query($connect,"SELECT info  FROM `lectures` WHERE id ='{$_GET['id_lecture']}'");
            $info=mysqli_fetch_assoc($q_info_lecture);
            print($info['info']);
            ?>
        </div>

        <?if(isset($_POST['confirm']))
        {
            if(!empty($_POST['code']))
            {
                $code_stud=mysqli_real_escape_string($connect,$_POST['code']);
                $q_lecture=mysqli_query($connect,"SELECT code, id_meetup, id_company, type  FROM `lectures` WHERE id ='{$_GET['id_lecture']}'");
                $lectures=mysqli_fetch_assoc($q_lecture);
                $q_name_stud=mysqli_query($connect,"SELECT * FROM `list_for_company` WHERE id_lecture ='{$_GET['id_lecture']}' AND email_stud = '{$_SESSION['login']}' ");
                $numr=mysqli_num_rows($q_name_stud);
                $student=mysqli_fetch_assoc($q_name_stud);
                if ($numr == 0)
                {
                    if ( $code_stud == $lectures['code'])
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
                                <?echo "Код введен верно";?>
                            </div>
                        <?}

                        else {?>
                            <div class="notification">
                                <?echo "Не удалось записать данные";?>
                            </div>
                        <?}    
                    }
                    
                    else {?>
                        <div class="notification">
                            <?echo "Неверный код. Попробуйте ещё раз";?>
                        </div>
                    <? }
                }
                else{?>
                    <div class="notification">
                        <?echo "Вы уже записаны";?>
                    </div>
                <?}
            }
            else{?>
                <div class="notification">
                    <?echo "Вы забыли ввести код";?>
                </div>
            <?}
        }
        ?>

        <? 
        if ($_SESSION['type'] == 1)
        {
            $id_meetup = $_GET['id_meetup'];
            $query = mysqli_query($connect, "SELECT * FROM list_of_students WHERE login = '{$_SESSION['login']}' AND id_events='{$id_meetup}'");
            $numr=mysqli_num_rows($query);
            if($_SESSION['login'])
            {
                if($numr){?>
                    <div class="notification">
                        <?echo "Если не удалось отсканировать qr-код, то введите текстовый код в поле ниже";?>
                    </div>
                    <div class="code_form">
                        <form method="post">
                            <input type="text" , name="code" , placeholder="введите код">
                            <input name="confirm" , value="Подтвердить" , type="submit">
                        </form>
                    </div>
                <?}
                else{?>
                    <div class="notification">
                        <?echo "Если Вы хотите подтвердить прохождение лекции/стенда, то сначала запишитесь на мероприятие";?>
                    </div>
                <?}
            }
        }
        if ($_SESSION['name'] == '')
        {?>
            <div class="notification">
                <a href='index.php'>
                    Для подтверждения прохождения лекции/стенда необходимо авторизоваться и записаться на мероприятие.
                </a>
            </div>
        <?}
        if ($_SESSION['type'] == 2)
        {
            $query_id_company = mysqli_query($connect, "SELECT id_company FROM `company` WHERE name_company='{$_SESSION['name']}'");
            $id_company = mysqli_fetch_assoc($query_id_company);
            $id_company = $id_company['id_company'];
            $q_code=mysqli_query($connect,"SELECT code FROM `lectures` WHERE id ='{$_GET['id_lecture']}' AND id_company ='{$id_company}'");
            $code=mysqli_fetch_assoc($q_code);?>
            <div class="code"> 
                Код:
                <?if ($code['code'])
                {
                    print( $code['code']);?>
                    <br>
                    <br>
                    <?
                    $name_png_file = "../admin/qr_codes/qr_code";
                    $name_png_file .= (string) $_GET['id_lecture'];
                    $name_png_file .= ".png";?>
                    <img src=<?=$name_png_file?> alt="lkft">
                <?}
                else{?>
                    -
                    <?
                }?>
            </div>
            <?
        }?>
    </div>
</body>
</html>