<?php include "functions.php";?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>

    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style_event.css">

</head>
<?php
require_once("php/dbconnect.php");
// spl_autoload_register(function (string $className) {
//     require_once __DIR__ . '/../src/' . $className . '.php';
// });

// $id = $_GET['id'] ?? '';

// $pattern = '~^meetup(.*)$~';
// preg_match($pattern, $route, $matches);

// if (!empty($matches)) {
    
//}
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
        <p>Мероприятия</p>
        
        <?php
        $page_content = render('meetups.php', array('items' => $que));
        print($page_content);
        if ($_SESSION['type'] == 3){?>
            <form method="post">
                <input type='submit' name="add_event" class="sendsubmit" value="Создать мероприятие">
            </form>
            <?if (isset($_POST["add_event"])){
                header("Location: /admin/admin.php"); 
            }
        }?>
    </div>
</body>
</html>