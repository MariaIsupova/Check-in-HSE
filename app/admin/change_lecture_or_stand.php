<?  session_start();
    if ($_SESSION['type'] == 3){
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style_admin.css">
    <link rel="stylesheet" href="../css/modal_window_style.css">

</head>
<? 
session_start();
require_once("../php/dbconnect.php");
if (!$connect) {#проверяем все ли ок с соединением к бд
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$query_lecture=mysqli_query($connect,"SELECT * FROM `lectures` WHERE id='{$_GET['id_lecture']}'");
$lecture=mysqli_fetch_assoc($query_lecture);
?>

<body>
<div class="form-wrap">
    <div class="block_exit">
      <?       
        if ($_SESSION['name'] != '')
        {?>
        <a href='../logout.php?action=exit'>
            <div class="exit">
                Выход
            </div>
            <img class="picture_exit" src="../img/ikon1.svg" alt="lkft">
        </a>  
        <?}
        else{
        ?>
        <a href='../events.php'>
            <div class="exit">
                Вход
            </div>
            <img class="picture_in" src="../img/ikon2.svg" alt="lkft">
        </a> 
        <? } ?>
    </div>
    <a href='/info_lecture.php?id_meetup=<?=$lecture['id_meetup'];?>&id_lecture=<?=$lecture['id'];?>'>
        <img class="picture" src="../img/lec_back.svg" alt="lkft">
    </a>
    <div class="field_name">
        
        Изменение лекции/стенда
        <div class="decor"></div>
    </div>
    
    <div class="input-form">
        <form method="post">
            <p>
                <label for="lecture_name">
                    Название лекции/стенда:
                    <input name="lecture_name" type="text" id="lecture_name" minlength="2" value="<?=$lecture['title'];?>" required>
                </label>
            </p>
            <p>
                Компания организатор:
                <select name="id_company" size="1">    
                <?
                require_once("../php/dbconnect.php");
                if (!$connect) {#проверяем все ли ок с соединением к бд
                    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
                    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
                    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
                    exit;
                }
                $query_company = mysqli_query($connect,"SELECT * FROM `company`");
                while($data_company = mysqli_fetch_assoc($query_company)){
                    if($data_company['id_company'] == $lecture['id_company']){?>
                        <option value="<?=$data_company['id_company']?>" selected="selected"><?echo $data_company['name_company'];?></option>
                    <?}
                    else{?>
                        <option value="<?=$data_company['id_company']?>"><?echo $data_company['name_company'];?></option>
                    <?}
                }?>
                </select>
            </p>
             <p>
                 Мероприятие:
                <select name="id_meetup" size="1">
                    <? 
                    $query_meetup = mysqli_query($connect,"SELECT * FROM `meetups`");
                    while($data_meetups = mysqli_fetch_assoc($query_meetup)){
                        if($data_meetups['id_meetup'] == $lecture['id_meetup']){?>
                            <option value="<?=$data_meetups['id_meetup']?>" selected="selected"><?echo $data_meetups['title'];?></option>
                        <?}
                        else{?>
                            <option value="<?=$data_meetups['id_meetup']?>"><?echo $data_meetups['title'];?></option>
                        <?}
                    }?>
                </select>
            </p>
            <p>
                <?if ($lecture['type'] == 0){?>
                    <input name="l_or_s" type="radio" value=1 id="lecture"  checked>
                    <label for="lecture"> лекция </label>
                    <input name="l_or_s" type="radio" value=2 id="stand" required>
                    <label for="stand"> стенд</label>
                <?}
                else{?>
                    <input name="l_or_s" type="radio" value=1 id="lecture" required>
                    <label for="lecture"> лекция </label>
                    <input name="l_or_s" type="radio" value=2 id="stand" checked>
                    <label for="stand"> стенд</label>
                <?}?>
            </p>
            <p>
                <label for="info">
                    <textarea rows="10" cols="80" name="text" id="info" required><?=$lecture['info'];?></textarea>
                </label>
            </p>
            <p>
                <input name="change_lecture" value="Изменить лекцию/стенд" , type="submit">
            </p>
        </form>
    </div>
</div>
<?
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
        
        $upd="UPDATE lectures SET title='{$lecture_name}', id_company='{$id_company}', id_meetup='{$id_meetup}', type='{$type}', info='{$text}' WHERE id='{$lecture['id']}'";
        $res=mysqli_query($connect,$upd);
        if ($res)
        {?>
            <div class="notification">
                <?echo "Данные успешно изменены";?>
            </div>
            <?
            $str = "Location: http://";
        	$str .= (string) $_SERVER['HTTP_HOST'];
        	$str .= "/info_lecture.php?id_meetup=";
        	$str .= (string) $id_meetup;
        	$str .= "&id_lecture=";
        	$str .= (string) $lecture['id'];
        	header($str);
            ?>
        <?}
        else {?>
            <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                   Не удалось изменить данные
                               </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                    <script src="../modal_window.js"></script>
       
        <?}
    }
}?>
</body>
</html>
<?}
else{
    print('Нет прав доступа!');
}
?>