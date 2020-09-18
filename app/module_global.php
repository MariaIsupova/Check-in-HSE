<?php
function enter()
{ 
    $error = array(); //массив для ошибок  
    //echo $error;
    if ($_POST['login'] != "" && $_POST['password'] != "") //если поля заполнены    
    {       
        //echo $_POST['login'];
        
        $login = $_POST['login']; 
        $password = $_POST['password'];

        $rez = mysql_query($connect, "SELECT * FROM 'authorization' WHERE login='{$login}'"); //запрашивается строка из базы данных с логином, введённым пользователем
        echo $rez;
        if (mysql_num_rows($rez) == 1) //если нашлась одна строка, значит такой юзер существует в базе данных       
        {           
            $row = mysql_fetch_assoc($rez);             
            if (md5(md5($password).$row['salt']) == $row['password']) //сравнивается хэшированный пароль из базы данных с хэшированными паролем, введённым пользователем                        
            { 
                //пишутся логин и хэшированный пароль в cookie, также создаётся переменная сессии
                setcookie ("login", $row['login'], time() + 50000);                         
                setcookie ("password", md5($row['login'].$row['password']), time() + 50000);                    
                $_SESSION['time'] = date("H:i:s");;   //записываем в сессию id пользователя               
                $id = $_SESSION['time'];              
                lastAct($id);
                echo $error;
                return $error;          
            }           
            else //если пароли не совпали           
            {               
                $error[] = "Неверный пароль";                                       
                return $error;          
            }       
        }       
        else //если такого пользователя не найдено в базе данных        
        {           
            $error[] = "Неверный логин и пароль";           
            return $error;      
        }   
    }   
    else    
    {       
        $error[] = "Поля не должны быть пустыми!";              
        return $error;  
    } 
}

function lastAct($id)
{   
    $tm = time();   mysql_query("UPDATE authorization SET online='$tm', last_act='$tm' WHERE time='$id'"); 
}


function login () 
{
    ini_set ("session.use_trans_sid", true);   
    session_start(); 
    echo $_SESSION['time'];
    //$_SESSION['time']= date("H:i:s");
    if (isset($_SESSION['time']))//если сесcия есть   
    {   
        
        if(isset($_COOKIE['login']) && isset($_COOKIE['password'])) //если cookie есть, обновляется время их жизни и возвращается true      
        {           
            SetCookie("login", "", time() - 1, '/');            SetCookie("password","", time() - 1, '/');          
            setcookie ("login", $_COOKIE['login'], time() + 50000, '/');            
            setcookie ("password", $_COOKIE['password'], time() + 50000, '/');          
            $id = $_SESSION['time'];          
            lastAct($id);           
            return true;        
        }       
        else //иначе добавляются cookie с логином и паролем, чтобы после перезапуска браузера сессия не слетала         
        {           
            $rez = mysql_query("SELECT * FROM authorization WHERE id='{$_SESSION['time']}'"); //запрашивается строка с искомым id             
            if (mysql_num_rows($rez) == 1) //если получена одна строка          
            {       
                $row = mysql_fetch_assoc($rez); //она записывается в ассоциативный массив               
                setcookie ("login", $row['login'], time()+50000, '/');              
                setcookie ("password", md5($row['login'].$row['password']), time() + 50000, '/'); 
                $id = $_SESSION['time'];
                lastAct($id); 
                return true;            
            } 
            else return false;      
        }   
    }   
    else //если сессии нет, проверяется существование cookie. Если они существуют, проверяется их валидность по базе данных     
    {       
        if(isset($_COOKIE['login']) && isset($_COOKIE['password'])) //если куки существуют      
        {           
            $rez = mysql_query("SELECT * FROM 'authorization' WHERE login='{$_COOKIE['login']}'"); //запрашивается строка с искомым логином и паролем             
            @$row = mysql_fetch_assoc($rez);            
            if(@mysql_num_rows($rez) == 1 && md5($row['login'].$row['password']) == $_COOKIE['password']) //если логин и пароль нашлись в базе данных           
            {               
                $_SESSION['time'] = date("H:i:s"); //записываем в сесиию id              
                $id = $_SESSION['time'];              
                lastAct($id);               
                return true;            
            }           
            else //если данные из cookie не подошли, эти куки удаляются             
            {               
                SetCookie("login", "", time() - 360000, '/');               
                SetCookie("password", "", time() - 360000, '/');                    
                return false;           
            }       
        }       
        else //если куки не существуют      
        {           
            return false;       
        }   
    } 
}
?>