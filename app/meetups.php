<div class="event-list">
    <?php foreach ($items as $item):?>
        <?=render('item.php', ['item' => $item]);?>
    <?php endforeach;?>
</div>
