<?php
session_start();
if (isset($_SESSION['logged']))
    header('Refresh: 0; url=' . $_SERVER['HTTP_REFERER']);
?>
<!DOCTYPE html>
<!--
Kamil Zieliński 215521 
ABD projekt II 
21.04.2014
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?PHP
        require('./Config.php');
        require('./UserMenager.php');
        $UserMen = new UserMenager($_db_type, $_db_host, $_db_name, $_db_port, $_db_user, $_db_pass);
        $UserMen->login();
        ?>
    </body>
</html>
