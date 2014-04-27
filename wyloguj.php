<!--
Kamil Zieliński 215521 
ABD projekt II 
21.04.2014
-->
<?php
session_start();
session_destroy();
header('Location:index.php');
//header('Location:'.$_SERVER['HTTP_REFERER']);
echo "Zostales wylogowany" ;
?>

