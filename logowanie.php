<?php
print_r($_POST);
if (isset($_POST['wyslano'])) { //Sprawdzamy, czy submit został wciśnięty
    //Usuwamy białe znaki z przesłanych danych
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    //Kodujemy hasło - przy rejestracji również je zakodowaliśmy, wiec przy porównywaniu musi być zakodowane
    $password = sha1($password);

    try {
        //Połączenie z bazą danych MySQL PDO
        $db = new PDO($this->_db_type.':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
$pdo->query('SET NAMES utf8');
            $pdo->query('SET CHARACTER_SET utf8_unicode_ci');        
//Sprawdzamy czy użytkownik o podanych danych istnieje
        $stmt = $db->prepare("SELECT * FROM users WHERE login=:login AND password=:password");
        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }
    if ($stmt->rowCount() != 0) {
        echo "Zalogowałeś się!";
        /*
         * Tworzymy sesję dla zalogowanego użytkownika z:
         * - informacją, że użytkownik jest zalogowany
         * - jego id
         */
        $_SESSION['logged'] = true;
        $_SESSION['user_id'] = $row['id_users'];
        $_SESSION['login'] = $row['login'];
        header('Location:' . $_SERVER['HTTP_REFERER']);
    } else {
        echo '<div style="color:red">Login i/lub hasło są nieprawidłowe</div>';
        form();
    }
}
?>