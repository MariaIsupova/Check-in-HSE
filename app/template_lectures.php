<!--<div class="modal" aria-hidden="true">-->
<!--        <div class="modal-dialog">-->
<!--            <div class="modal-header">-->
<!--                <a href="#" class="btn-close closemodal" aria-hidden="true">&times;</a>-->
<!--                 инфрмация о лекции -->
<!--                Информация о лекции/стенде-->
<!--            </div>-->

<!--            <div class="modal-body">-->
<!--                <input type="text" name="code" placeholder="введите код">-->
<!--                <div class="modal-footer">-->
                    <!--<input class="btn" name="register" value="Подтвердить" , type="submit">-->
<!--                    <a href="#" class="btn">Подтвердить</a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

<p>
    Лекции</p>
<div class="event-list">
    <?php foreach ($lecture as $lect):
        if ($lect['type'] == 0) {?>
            <?=render('template_lecture_or_stand.php', ['lecture' => $lect]);
        }?>
    <?php endforeach;?>
    <? session_start();
    if ($_SESSION['type'] == 3){?>
        <div class="add" >
            <a href="/admin/admin.php" >
                <img class="_add" src="../img/add.svg" alt="Добавить" />
            </a>
        </div>
    <?}?>
</div>  
<p>Стенды</p>
<div class="event-list">
    
    <?php foreach ($lecture as $lect):
        if ($lect['type'] == 1) {?>
            <?=render('template_lecture_or_stand.php', ['lecture' => $lect]);
        }?>
    <?php endforeach;?>
    
    
    <? if ($_SESSION['type'] == 3){?>
        <div class="add" >
            <a href="/admin/admin.php" >
                <img class="_add" src="../img/add.svg" alt="Добавить" />
            </a>
        </div>
    <?}?>
</div>

    