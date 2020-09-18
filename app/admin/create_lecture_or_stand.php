<?php
include "../phpqrcode/qrlib.php";



if(isset($_POST["create_lecture"]))
{
    if(!empty($_POST['lecture_name']) && !empty($_POST['id_company']) && !empty($_POST['id_meetup']) && !empty($_POST['l_or_s']) && !empty($_POST['text'])) 
    {
        require_once("../php/dbconnect.php");
        if (!$connect) 
        {#проверяем все ли ок с соединением к бд
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        $lecture_name=mysqli_real_escape_string($connect,$_POST['lecture_name']);
        
        $id_company=mysqli_real_escape_string($connect,$_POST['id_company']);
        $id_meetup=mysqli_real_escape_string($connect,$_POST['id_meetup']);
        
        $type=mysqli_real_escape_string($connect,$_POST['l_or_s']);
        $type-=1;
        $text=mysqli_real_escape_string($connect,$_POST['text']);
        
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < 6) 
        {
                $code .= $chars[mt_rand(0,$clen)];
        }
        
        $query_lectures=mysqli_query($connect,"SELECT * FROM `lectures` WHERE title='{$lecture_name}' AND type='{$type}' AND id_company='{$id_company}' AND id_meetup='{$id_meetup}' AND code='{$code}'");
        $numr=mysqli_num_rows($query_lectures);
        if($numr==0) {
            $sql_q="INSERT INTO `lectures` (title, id_company, id_meetup, type, code, info) VALUES ('{$lecture_name}', '{$id_company}', '{$id_meetup}', '{$type}', '{$code}', '{$text}')";
            $res=mysqli_query($connect, $sql_q);
            if($res)
            {
                if($type==0)
                {
                    ?>
                    <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                   Лекция добавлена
                               </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                    <script src="../modal_window.js"></script>
                <?}
                else
                {
                    if($type==1)
                    {
                         ?>
                        <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                   Стенд добавлен
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
                    
                         <?
                    }
                }
                $query_id=mysqli_query($connect,"SELECT id FROM `lectures` WHERE title='{$lecture_name}' AND type='{$type}' AND id_company='{$id_company}' AND id_meetup='{$id_meetup}' AND info='{$text}'");
                $id_lecture=mysqli_fetch_assoc($query_id);
                $id_lecture=$id_lecture['id'];
                $link = "http://eventshse.h1n.ru/check_lecture.php?id_meetup=";
                $link .= (string) $id_meetup;
                $link .= "&id_lecture=";
                $link .= (string) $id_lecture;
                
                $name_qr = "qr_codes/qr_code";
                $name_qr .= (string) $id_lecture;
                $name_qr .= ".png";
                QRcode::png($link, $name_qr, "L", 4, 4);
            }
            else {?>
                <?if($type==0){
                    ?>
                    
                    <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                 Не удалось добавить лекцию  
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
                    <?
                }
                else {
                    if($type==1){
                        ?>
                        
                        <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                 Не удалось добавить стенд  
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
                        <?
                    }
                }?>
            <?}
        }
        else {
            if($type==0){
                ?>
                
                <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                 Эта лекция уже существует  
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
                <?}
            else{
                if($type==1){
                    ?>
                    <div class="modal opened" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-header">
                               <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>
                            </div>
                            <div class="modal-body"> 
                               <div class="n">
                                 Этот стенд уже существует  
                               </div>
                            </div>
                        </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                        <script src="../modal_window.js"></script>
                    <?}
            }
        }
    }
    else {?>
        <?
        // echo "Все поля обязательны для заполнения!";
        ?>
    <?}
}
?>