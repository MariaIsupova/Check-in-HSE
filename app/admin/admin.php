<?  session_start();
    if ($_SESSION['type'] == 3){
?>
<!DOCTYPE html>

<html lang="en">

<? include "create_meetup.php";?>
<? include "create_lecture_or_stand.php";?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style_admin.css">
    <link rel="stylesheet" href="../css/modal_window_style.css">

</head>


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
            <?}?>
        </div>
        
        <a href="../events.php">
            <img class="picture" src="../img/lec_back.svg" alt="lkft">
        </a>
            
        <div class="field_name">
            Создание мероприятия
            <div class="decor"></div>
        </div>
        
        <div class="input-form">
            <form method="post">
                <p>
                    <label for="event_name">
                        Название мероприятия:
                        <input name="event_name" type="text" id="event_name" minlength="2" required>
                    </label>
                </p>
                <p>
                    <label for="date">
                        Дата проведения:
                        <input name="date" type="date" id="date" required>
                    </label>
                </p>
                <p>
                    <label for="number_lecture">
                        Необходимое количество лекций
                        <input name="number_lecture" type="number" id="number_lecture" required>
                    </label>
                    <label for="number_stand">
                        , стендов
                        <input name="number_stand" type="number" id="number_stand" required>
                        для получения сертификата
                    </label>
                </p>
                
                    <p>
                        <input name="create_meetup", value="Добавить мероприятие" , type="submit">
                    </p>
                

            </form>
        </div>



        <div class="field_name">
            Создание лекции/стенда
            <div class="decor"></div>
        </div>
        
        <div class="input-form">
            <form method="post">
                <p>
                    <label for="lecture_name">
                        Название лекции/стенда:
                        <input name="lecture_name" type="text" id="lecture_name" minlength="2" required>
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
                    while($data_company = mysqli_fetch_assoc($query_company)){?>
                        <option value=<?=$data_company['id_company']?>><?echo $data_company['name_company'];?></option>
                  <?}?>
                    </select>
                </p>
                 <p>
                     Мероприятие:
                    <select name="id_meetup" size="1">
                        <? 
                        $query_meetup = mysqli_query($connect,"SELECT * FROM `meetups`");
                        while($data_meetups = mysqli_fetch_assoc($query_meetup)){?>
                            <option value=<?=$data_meetups['id_meetup']?>><?echo $data_meetups['title'];?></option>
                      <?}?>
                    </select>
                </p>
                <p>

                    <input name="l_or_s" type="radio" value=1 id="lecture" required>
                    <label for="lecture"> лекция </label>
                    <input name="l_or_s" type="radio" value=2 id="stand" required>
                    <label for="stand"> стенд</label>
                </p>
                <p>
                    <label for="info">
                        <textarea rows="10" cols="80" name="text" id="info" placeholder="Введите информацию о лекции/стенде:" required></textarea>
                    </label>
                </p>
                <p>
                    <input name="create_lecture" value="Добавить лекцию/стенд" , type="submit">
                </p>
            </form>
        </div>
    </div>
    
</body>
</html>
<?}?>