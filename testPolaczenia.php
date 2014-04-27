<?php

require('./ConfigPQ.php');


   try
   {
     $pdo = new PDO($_db_type.':host=' . $_db_host . ';dbname=' . $_db_name . ';port=' . $_db_port, $_db_user, $_db_pass);
      echo 'Połączenie nawiązane!';
   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }
?>