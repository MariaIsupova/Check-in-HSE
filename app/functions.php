<?php 
function render($template,$vars){
    extract($vars);
    include "$template";
}?>