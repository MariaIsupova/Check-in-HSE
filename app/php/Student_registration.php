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
    if(!empty($_POST['FIO']) && !empty($_POST['university']) && !empty($_POST['student_group'])  && !empty($_POST['email'])  && !empty($_POST['password']))
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
        $name=mysqli_real_escape_string($connect,$_POST['FIO']);
        $university=mysqli_real_escape_string($connect,$_POST['university']);
        $group=mysqli_real_escape_string($connect,$_POST['student_group']);
        $email=mysqli_real_escape_string($connect,$_POST['email']);
        $pass=mysqli_real_escape_string($connect,md5($_POST['password']));
        $query=mysqli_query($connect,"SELECT * FROM `students` WHERE email='{$email}'");
        $numr=mysqli_num_rows($query);
        if($numr==0)
        {
            $sql_q1="INSERT INTO `students` (FIO, university, student_group, email, password) VALUES ('{$name}','{$university}', '{$group}', '{$email}', '{$pass}')";
            $res1=mysqli_query($connect, $sql_q1);
            
            $type=1;
            $sql_q2="INSERT INTO `authorization` (login, password, type) VALUES ('{$email}', '{$pass}', '{$type}')";
            $res2=mysqli_query($connect, $sql_q2);
            
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
        <input class="st" value="Студент" type="button">
        <div class="in_company" onclick="location.href='./Company_registration.php'">Компания</div> 
      </div>
    </div>
    
    
    <form action="Student_registration.php" method="post" name="registerform">
      <div class="registration">Регистрация</div>
      <div class="sing_in" style="font-size: 18px"> или <a href="../index.php" style="font-size: 28px"> Вход </a></div>

      <label for="name">
        <input name="FIO" type="text" id="name" required placeholder="ФИО" minlength="2" required>
      </label>

      <label for="university">
        <input name="university" type="text" id="university" required placeholder="Вуз" required>
      </label>

      <label for="group">
        <input 	name="student_group" type="text" id="group" required placeholder="Группа" required>
      </label>

      <label for="email">
        <input name= "email" type="email" id="email" required placeholder="Почта" required>
      </label>

      <label for="password">
        <input name="password" type="password" id="password" required placeholder="Пароль" maxlength="100" minlength="5" required>
      </label>

      <input name="register" value="Зарегистрироваться" , type="submit">
      <h1></h1>
    </form>
  </div>
</body>

</html>