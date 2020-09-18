<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sung up</title>

  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/style_registration.css">
  <link rel="stylesheet" href="../css/modal_window_style.css">

</head>

<?php

if(isset($_POST["register"]))
{
    if(!empty($_POST['name_company']) && !empty($_POST['email_company']) && !empty($_POST['password_company'])) 
    {
        require_once("dbconnect.php");
        if (!$connect) {#проверяем все ли ок с соединением к бд
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }/*
        else{
            echo "Соединение с MySQL установлено!" . PHP_EOL;
            echo "Информация о сервере: " . mysqli_get_host_info($link) . PHP_EOL;
        }*/
        $name=mysqli_real_escape_string($connect,$_POST['name_company']);
        $email=mysqli_real_escape_string($connect,$_POST['email_company']);
        $pass=mysqli_real_escape_string($connect,md5($_POST['password_company']));
        $query=mysqli_query($connect,"SELECT * FROM `company` WHERE email_company='{$email}'");
        $numr=mysqli_num_rows($query);
        if($numr==0)
        {
            $sql_q1="INSERT INTO `company` (name_company, email_company, password_company) VALUES ('{$name}', '{$email}', '{$pass}')";
            $res1=mysqli_query($connect, $sql_q1);
            
            $type=2;
            $sql_q2="INSERT INTO `authorization` (login, password, type) VALUES ('{$email}', '{$pass}', '{$type}')";
            $res2=mysqli_query($connect, $sql_q2);
            echo $res2;
            if($res1 && $res2){
                echo '<script type="text/javascript">
                window.location = "../index.php"
                </script>';
                echo "Аккаунт успешно создан";
            }
            else {
                echo "Не удалось добавить информацию";
            }
        }
        else 
        {
            echo "Этот E-mail занят. Попробуйте другой!";
        }
    }
    else 
    {
        //$info = "Все поля обязательны для заполнения!";
        // echo "Все поля обязательны для заполнения!";
    }
}
?>
<body>
  <div class="form-wrap">
    <div class="switch">
      <div class="pole">
        <div class="in_student" onclick="location.href='./Student_registration.php'">Студент</div> 
        <input class="comp" value="Компания" type="button" >
      </div>
    </div>
    <form action="Company_registration.php" method="post" name="registerform">
      <div class="registration">Регистрация</div>
      <div class="sing_in" style="font-size: 18px"> или <a href="../index.php" style="font-size: 28px">Вход </a></div>

      <label for="name">
        <input name="name_company" type="text" id="name" required placeholder="Название компании" minlength="2" required>
      </label>

      <label for="email">
        <input name="email_company" type="email" id="email" required placeholder="Почта" required>
      </label>

      <label for="password">
        <input name="password_company" type="password" id="password" required placeholder="Пароль" maxlength="100" minlength="5" required>
      </label>

      <input name="register" value="Зарегистрироваться" , type="submit">
      <h1></h1>
    </form>
  </div>
</body>

</html>