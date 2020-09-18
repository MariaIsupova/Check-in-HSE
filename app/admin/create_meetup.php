<?php

if(isset($_POST["create_meetup"]))
{
    if(!empty($_POST['event_name']) && !empty($_POST['date']) && !empty($_POST['number_lecture']) && !empty($_POST['number_stand'])) 
    {
        require_once("../php/dbconnect.php");
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
        $name=mysqli_real_escape_string($connect,$_POST['event_name']);
        $date=mysqli_real_escape_string($connect,$_POST['date']);
        $num_lectures=mysqli_real_escape_string($connect,$_POST['number_lecture']);
        $num_stands=mysqli_real_escape_string($connect,$_POST['number_stand']);
        $query=mysqli_query($connect,"SELECT id_meetup FROM meetups WHERE title='{$name}' AND date='{$date}'");
        $numr=mysqli_num_rows($query);
        if ($numr == 0)
        {
            $sql_q="INSERT INTO meetups (title, date, lim_lectures, lim_stands) VALUES ('{$name}','{$date}', '{$num_lectures}', '{$num_stands}')";
            $res=mysqli_query($connect, $sql_q);
            
            if($res){?>
                <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                  Мероприятие добавлено
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
                <!--<div class="notification">-->
                <!--Мероприятие добавлено-->
                <!--</div>-->
            <?}
            else {?>
            
                <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                  Не удалось добавить мероприятие
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
                <!--<div class="notification">-->
             
                <!--</div>-->
                
        <?  }
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
                                  Это мероприятие уже существует
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
            <!--<div class="notification">-->
           
            <!--</div>-->
                
        <?}
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
                                  Все поля обязательны для заполнения!
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
        
        <!--<div class="notification">-->
           
        <!--</div>-->
        
    <?}
}

?>