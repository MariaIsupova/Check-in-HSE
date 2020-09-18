<?php 
include("php/dbconnect.php");
?>

<div class="event" onclick="location.href='/info_lecture.php?id_meetup=<?=$lecture['id_meetup'];?>&id_lecture=<?=$lecture['id'];?>'">
    <p class=" name"><?=$lecture['title'];?></p>
    <div class="dec"></div>
    
    <?
    $query = mysqli_query($connect, "SELECT * FROM company WHERE id_company = '{$lecture['id_company']}'");
    $company = mysqli_fetch_assoc( $query );
    ?>
    
    <div class="company_name">
        <p class="comp_name"> 
           <?print($company['name_company']);?>
        </p>
    </div>
</div>
    





