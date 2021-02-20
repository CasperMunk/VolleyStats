<?php
function get($name){
    if(isset($_GET[$name])){
        return htmlspecialchars($_GET[$name]);
    }else{
        return false;
    }
}

function post($name){
    if(isset($_POST[$name])){
        return htmlspecialchars($_POST[$name]);
    }else{
        return false;
    }
}

function is_local() {
    if($_SERVER['HTTP_HOST'] == 'localhost'
        || substr($_SERVER['HTTP_HOST'],0,3) == '10.'
        || substr($_SERVER['HTTP_HOST'],-6) == '.local'
        || substr($_SERVER['HTTP_HOST'],0,7) == '192.168') return true;
    return false;
}
?>