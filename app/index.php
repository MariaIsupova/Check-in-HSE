<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>

    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style_i.css">
    <link rel="stylesheet" href="../css/modal_window_style.css">
</head>

<?
// Страница авторизации

// Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

// Соединямся с БД
require_once("php/dbconnect.php");
session_start();
$que = mysqli_query($connect,"SELECT * FROM authorization");
$data = mysqli_fetch_assoc($que);


if($_GET["action"]== "exit"){
  session_unset();
  session_destroy();
}
if(isset($_POST['submit']))
{
    //$error = array(); //массив для ошибок  
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $_SESSION['input'] = $_POST['submit'];
    $query = mysqli_query($connect,"SELECT * FROM authorization WHERE login='".mysqli_real_escape_string($connect,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    // Сравниваем пароли
    if($data['password'] == md5($_POST['password']))
    {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        // Записываем в БД новый хеш авторизации и IP
        mysqli_query($connect, "UPDATE authorization SET hash='".$hash."' ".$insip." WHERE id='".$data['id']."'");

        // Ставим куки
        setcookie("id", $data['id'], time()+60*60*24*30, "/");
        setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true); // httponly !!!

        // Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: check.php"); 
        exit();
    }
    else
    {?>
        <div class="modal opened" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-header">
                   <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                </div>
                <div class="modal-body"> 
                   <div class="n">
                       Вы ввели неправильный логин/пароль
                   </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="../modal_window.js"></script>
        <?
        // print "Вы ввели неправильный логин/пароль";

        //$error[] = "Вы ввели неправильный логин/пароль";                                       
        //return $error;
    }
}?>

<body>
    <div class="container">
        <form method="post" class="sing_in">
            <h1>Портал мероприятий НИУ ВШЭ</h1>
            <label for="username">
                <span>Почта</span>
                <input name="login" type="email" id="username" minlength="4" required>
            </label>
            <label for="password">
                <span>Пароль</span>
                <input name="password" type="password" id="password" maxlength="100" minlength="5" required>
            </label>
            <input name="submit" value="Войти" , type="submit">
            <input value="Зарегистрироваться" type="button" onclick="location.href='./php/Student_registration.php'">
            <br>
        </form>
    </div>
</body>
</html>
