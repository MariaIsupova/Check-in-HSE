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
require_once("../php/dbconnect.php");
if (!$connect) {#проверяем все ли ок с соединением к бд
    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$query_meetup=mysqli_query($connect,"SELECT * FROM meetups WHERE id_meetup='{$_GET['id_meetup']}'");
$meetup=mysqli_fetch_assoc($query_meetup);

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
    <a href='/lectures.php?id_meetup=<?=$meetup['id_meetup'];?>'>
        <img class="picture" src="../img/lec_back.svg" alt="lkft">
    </a>
    <div class="field_name">
        
        Изменение мероприятия
        <div class="decor"></div>
    </div>
    
    <div class="input-form">
        <form method="post">
            <p>
                <label for="event_name">
                    Название мероприятия:
                    <input name="event_name" type="text"  id="event_name" minlength="2" value="<?=$meetup['title'];?>" required>
                </label>
            </p>
            <p>
                <label for="date">
                    Дата проведения:
                    <input name="date" type="date" id="date" value="<?=$meetup['date'];?>" required>
                </label>
            </p>
            <p>
                <label for="number_lecture">
                    Необходимое количество лекций
                    <input name="number_lecture" type="number" id="number_lecture" value="<?=$meetup['lim_lectures'];?>" required>
                </label>
                <label for="number_stand">
                    , стендов
                    <input name="number_stand" type="number" id="number_stand" value="<?=$meetup['lim_stands'];?>" required>
                    для получения сертификата
                </label>
            </p>
            <form method="post">    
                <p>
                    <input name="change_meetup", value="Изменить мероприятие" , type="submit">
                </p>
            </form>

        </form>
    </div>
</div>
</body>
</html>
<?}
else{
    print('Нет прав доступа!');
}
?>
<?php
if(isset($_POST["change_meetup"]))
{
    if(!empty($_POST['event_name']) && !empty($_POST['date']) && !empty($_POST['number_lecture']) && !empty($_POST['number_stand'])) 
    {
        require_once("../php/dbconnect.php");
        if (!$connect) {#проверяем все ли ок с соединением к бд
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        
        $name=mysqli_real_escape_string($connect,$_POST['event_name']);
        $date=mysqli_real_escape_string($connect,$_POST['date']);
        $num_lectures=mysqli_real_escape_string($connect,$_POST['number_lecture']);
        $num_stands=mysqli_real_escape_string($connect,$_POST['number_stand']);
        
        $upd="UPDATE meetups SET title='{$name}', date='{$date}', lim_lectures='{$num_lectures}', lim_stands='{$num_stands}' WHERE id_meetup='{$meetup['id_meetup']}'";
        $res=mysqli_query($connect, $upd);
        if ($res)
        {?>
            <div class="notification">
                <?echo "Мероприятие успешно обновлено";?>
            </div>
            <?header('Location: http://'.$_SERVER['HTTP_HOST'].'/');?>
        <?}
        else {?>
                    <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                   Не удалось изменить мероприятие
                               </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                    <script src="../modal_window.js"></script>
        <?}
    }
    else 
    {?>
        <!--<div class="notification">-->
            <!--echo "Все поля обязательны для заполнения!"-->
        <!--</div>-->
    <?}
}?>