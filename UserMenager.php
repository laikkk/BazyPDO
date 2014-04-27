<?php

class UserMenager {

    private $_db_type;
    private $_db_host;
    private $_db_name;
    private $_db_port;
    private $_db_user;
    private $_db_pass;

    public function __construct($db_type, $db_host, $db_name, $db_port, $db_user, $db_pass) {
        $this->_db_type = $db_type;
        $this->_db_host = $db_host;
        $this->_db_name = $db_name;
        $this->_db_port = $db_port;
        $this->_db_user = $db_user;
        $this->_db_pass = $db_pass;
    }

    private function form() {
        echo '<form action="" method="post">
                <label for="login">Login</label>
                <input type="text" name="login" required>
                <br>
                <label for="password">Hasło</label>
                <input type="password" name="password" required>
                <br>
                <label for="password2">Powtórz hasło</label>
                <input type="password" name="password2" required>
                <br>
                <label for="email">E-mail</label>
                <input type="email" name="email" required>
                <br>
                <input type="submit" name="rejestracja" value="Załóż konto"/>
            </form>';
    }

    private function formLogin() {
        if (isset($_SESSION['logged'])) {
            echo "Jesteś zalogowany, twoje id to: " . $_SESSION['user_id'];
        } else {
            echo '<form action="login.php" method="post">
                    <label for="login">Login</label>
                    <input type="text" name="login" required>
                    <br>
                    <label for="password">Hasło</label>
                    <input type="password" name="password" required>
                    <br>
                    <input type="submit" name="logowanie" value="Zaloguj się"/>
                </form>';
        }
    }

    public function register() {
        if (isset($_POST['rejestracja'])) { //Sprawdzamy, czy submit został wciśnięty
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $password2 = trim($_POST['password2']);
            $email = trim($_POST['email']);

            $errors = NULL; //Tworzymy zmienną przechowująca ewentualne błędy
            //Sprawdzamy, czy przesłane dane mają odpowiednią ilość znaków
            if (strlen($login) < 3)
                $errors .= 'Login musi zawierać co najmniej 3 znaki<br>';
            if (strlen($password) < 6)
                $errors .= 'Hasło musi zawierać co najmniej 6 znaków<br>';
            if ($password !== $password2)
                $errors .= 'Hasła nie są takie same<br>';
            if (!preg_match('/\@/', $email) || strlen($email) < 5)
                $errors .= 'Podany adres e-mail jest nieprawidłowy<br>';
            if (empty($errors)) {
                try {
                    //Połączenie z bazą danych MySQL PDO                   
                    $db = new PDO($this->_db_type.':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                    try {
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        //Sprawdzamy czy użytkownik o takim samym loginie już nie istnieje
                        $stmt = $db->prepare("SELECT COUNT(id_users) FROM users WHERE login=:login");
                        $stmt->bindValue(":login", $login, PDO::PARAM_STR);
                        $stmt->execute();
                        $row = $stmt->fetch();
                        $stmt->closeCursor();
                        if ($row[0] > 0)
                            $errors .= 'Konto o takim loginie już istnieje<br>';
                    } catch (PDOException $e) {
                        echo 'Coś poszło nie tak podczas  sprawdzania czy login istnieje: ' . $e->getMessage();
                    }
                    try {
                        //Sprawdzamy czy użytkownik o takim samym adresie e-mail już nie istnieje
                        $stmt2 = $db->prepare("SELECT COUNT(id_users) FROM users WHERE email=:email");
                        $stmt2->bindValue(":email", $email, PDO::PARAM_STR);
                        $stmt2->execute();
                        $row2 = $stmt2->fetch();
                        if ($row2[0] > 0)
                            $errors .= 'Konto o takim adresie e-mail już istnieje<br>';
                        $stmt2->closeCursor();
                    } catch (PDOException $e) {
                        echo 'Coś poszło nie tak podczas  sprawdzania czy email juz istnieje w bazie: ' . $e->getMessage();
                    }
                    if (empty($errors)) { //Jeśli nie ma błędów, rejestrujemy użytkownika
                        try {
                            $password = sha1($password); //kodujemy hasło
                            $stmt3 = $db->prepare("INSERT INTO users(login,password,email) VALUES(:login, :password, :email)");
                            $stmt3->bindValue(":login", $login, PDO::PARAM_STR);
                            $stmt3->bindValue(":password", $password, PDO::PARAM_STR);
                            $stmt3->bindValue(":email", $email, PDO::PARAM_STR);
                            $stmt3->execute();
                            $stmt3->closeCursor();
                            echo "Zarejestrowałeś się. Możesz się teraz <a href=\"index.php\">Zaloguj się</a>";
                        } catch (PDOException $e) {
                            echo 'Coś poszło nie tak podczas dodawania usera do bazy: ' . $e->getMessage();
                        }
                    } else {
                        echo '<div style="color:red">' . $errors . '</div>';
                        $this->form(); //Wyświetlamy formularz
                    }
                } catch (PDOException $e) {
                    echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
                }
            } else {
                echo '<div style="color:red">' . $errors . '</div>';
                $this->form(); //Wyświetlamy formularz
            }
        } else
            $this->form();
    }

    public function login() {
        if (isset($_POST['logowanie'])) { //Sprawdzamy, czy submit został wciśnięty
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $password = sha1($password);
            try {
                $db = new PDO($this->_db_type.':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
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
                $_SESSION['logged'] = true;
                $_SESSION['user_id'] = $row['id_users'];
                $_SESSION['login'] = $row['login'];
                header('Location:' . $_SERVER['HTTP_REFERER']);
            } else {
                echo '<div style="color:red">Login i/lub hasło są nieprawidłowe</div>';
                $this->formLogin();
            }
        } else
            $this->formLogin();
    }

}
