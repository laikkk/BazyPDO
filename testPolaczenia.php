<?php

require('./ConfigPQ.php');


try {
    $pdo = new PDO($_db_type . ':host=' . $_db_host . ';dbname=' . $_db_name . ';port=' . $_db_port, $_db_user, $_db_pass);
    $pdo->query('SET NAMES utf8');
    $pdo->query('SET CHARACTER_SET utf8_unicode_ci');
    echo 'Połączenie nawiązane!';
} catch (PDOException $e) {
    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
}
?>