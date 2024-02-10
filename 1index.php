<?php 
if($_SERVER["HTTP_HOST"] == "newcaferio.com"){
    header("Location: http://newcaferio.com/pos/public"); 
    exit();
}
else{
    echo $_SERVER["HTTP_HOST"];
}
?>