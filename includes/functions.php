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

function reverseName($name){
    return strstr($name," ")." ".substr($name,0,strpos($name," "));
}
?>