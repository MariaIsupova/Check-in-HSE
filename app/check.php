<?
// Скрипт проверки
session_start();
// Соединямся с БД
require_once("php/dbconnect.php");

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $query = mysqli_query($connect, "SELECT *,INET_NTOA(ip) AS ip FROM authorization WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);
    echo $userdata['ip'];
    if(($userdata['hash'] !== $_COOKIE['hash']) or ($userdata['id'] !== $_COOKIE['id'])
 or (($userdata['ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['ip'] !== NULL)))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
        echo "Хм, что-то не получилось";
    }
    else
    {
        $_SESSION['type'] = $userdata['type'];
        if ($userdata['type'] == 1){
            $q = mysqli_query($connect, "SELECT * FROM students WHERE email = '{$userdata['login']}' ");
            $student = mysqli_fetch_assoc($q);
            $_SESSION['name'] = $student['FIO'];
            $_SESSION['login'] = $student['email'];
        }
        else{
            if ($userdata['type'] == 2){
                $q = mysqli_query($connect, "SELECT * FROM company WHERE email_company = '{$userdata['login']}'");
                $company = mysqli_fetch_assoc($q);
                $_SESSION['name'] = $company['name_company'];
                $_SESSION['login'] = $company['email_company'];
            }
            else {
                if ($userdata['type'] == 3){
                    $_SESSION['login']=$userdata['login'];
                    $_SESSION['name']="Администратор";
                }
            }
        }
        if(isset($_SESSION['input'])){
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/'); 
        }
    }
}
else
{
    echo "Вы не вошли";
}

?>