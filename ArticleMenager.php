<?php

//Kamil Zieliński 215521 
//ABD projekt II 
//21.04.2014
class ArticleMenager {

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

    public function wyswietlArtykuly() {
        try {
            $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //wypisanie tabeli            
            $stmt = $pdo->query('SELECT id_artykulu,tytul,tresc,data_utworzenia FROM artykuly AS k ');
            $row_count = $stmt->rowCount();
            if (!$row_count) {
                return; //jezeli nie ma zadnych wierszy to return pusta tablice
            }
            echo '<div id="Art" class="table-prices" style="display:none;">           
                <table>
                    <thead>
                        <tr>
                            <th>id_artykulu</th>
                            <th>tytul</th>
                            <th>tresc</th>
                            <th>data_utworzenia</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach ($stmt as $row) {
                echo '<tr>
			<td>' . $row['id_artykulu'] . '</td>
			<td><a href="index.php?id=' . $row['id_artykulu'] . '" >' . $row['tytul'] . '</a></td>
			<td>' . $row['tresc'] . '</td>
                        <td>' . $row['data_utworzenia'] . '</td>
                    </tr>';
            }
            echo '</tbody> </table>
            </div>';
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo 'Wystąpił błąd podczas wyświetlania tabeli artykułów: ' . $e->getMessage();
        }
    }

    public function obslugaDodawaniaArtykulow() {
        if (isset($_POST['dodaj_artykul']) && isset($_SESSION['logged'])) {
            try {
                $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                $tytul = trim($_POST['tytul']);
                $tresc = trim($_POST['tresc']);
                if (strlen($tresc) < 6)
                    $errors = '<p class="error">Treść artykulu musi zawierać co najmniej 6 znaków</p>';
                if (strlen($tytul) < 6)
                    $errors = '<p class="error">Tytul artykulu musi zawierać co najmniej 6 znaków</p>';
                if (empty($errors)) {
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->prepare("INSERT INTO artykuly(tytul,tresc)  VALUES(:tytul,:tresc)");
                    $stmt->bindValue(":tytul", $tytul, PDO::PARAM_STR);
                    $stmt->bindValue(":tresc", $tresc, PDO::PARAM_STR);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<p class="msg">Artykul został dodany ;)</p>';
                } else
                    echo $errors;
                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo 'Wystąpił bład podczas dodawania artykulu: ' . $e->getMessage();
            }
        }
    }

    public function getCurrURL() {
        $pageURL = 'http';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function Edit_and_Show_Article() {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) { //Sprawdzmy czy id wystepuje w urlu , jesli tak to czy jest cyfra
            //obsluga edycji artykulu
            if (isset($_POST['edytuj_artykul']) && isset($_SESSION['logged'])) {
                try {
                    $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                    $tytul = trim($_POST['tytul']);
                    $tresc = trim($_POST['tresc']);
                    $id_artykulu = trim($_POST['id_artykulu']);
                    if (strlen($tresc) < 6)
                        $errors = '<p class="error">Treść komentarza musi zawierać co najmniej 6 znaków</p>';
                    if (empty($errors)) {
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt = $pdo->prepare("UPDATE artykuly SET tytul=:tytul , tresc=:tresc WHERE id_artykulu=:id_artykulu ");
                        $stmt->bindValue(":tytul", $tytul, PDO::PARAM_STR);
                        $stmt->bindValue(":tresc", $tresc, PDO::PARAM_STR);
                        $stmt->bindValue(":id_artykulu", $id_artykulu, PDO::PARAM_INT);
                        $stmt->execute();
                        $stmt->closeCursor();
                        echo '<p class="msg">Artykul został zedytowany ;)</p>';
                    } else
                        echo $errors;
                    $stmt->closeCursor();
                } catch (PDOException $e) {
                    echo 'Wystąpił bład podczas edytownia komentarza artykulu: ' . $e->getMessage();
                }
            }

            //wyswietlenie artkulu + edycji
            echo'<article>';
            if (isset($_SESSION['logged']))
                echo '<div class="tool"><a id="edit_art" class="toolbtn">Edytuj</a><a id="del_art" class="toolbtn">Usuń</a></div>';
            try {
                $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->query('SELECT tytul,tresc,id_artykulu,data_utworzenia FROM artykuly  WHERE id_artykulu=' . $_GET['id'] . ' ');


                foreach ($stmt as $row) {
                    echo '<h1>' . $row['tytul'] . '</h1>';
                    echo '<h2>' . $row['data_utworzenia'] . '</h2>';
                    echo '<h4>' . $row['tresc'] . '</h4>';
                    $id_artykulu = $row['id_artykulu'];
                    echo'<article id="edit_art_" class="edit_article" style="display:none;">';
                    echo
                    '<form action="" method="post">
                        <input type="text" name="tytul" value="' . $row['tytul'] . '" />
                        <textarea rows="4" cols="50" name="tresc" required >' . $row['tresc'] . ' </textarea>
                        <input type="text" name="id_artykulu" value="' . $row['id_artykulu'] . '" style="display:none;" />
                        <input type="submit" name="edytuj_artykul" value="Edytuj Artykul"/>
                     </form>';
                    echo'</article>';
                }

                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo 'Wystąpił bład podczas wyświetlania artykulu: ' . $e->getMessage();
            }
            if (isset($_POST['wyslano_komentarz']) && isset($_SESSION['logged'])) {
                try {
                    $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                    $tresc = trim($_POST['tresc']);
                    if (strlen($tresc) < 6)
                        $errors = '<p class="error">Treść komentarza musi zawierać co najmniej 6 znaków</p>';
                    if (empty($errors)) {
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt = $pdo->prepare("INSERT INTO komentarze(\"id_artykulu\",\"login\",\"tresc\")  VALUES(:id_artykulu,:login,:tresc)");
                        $stmt->bindValue(":id_artykulu", $id_artykulu, PDO::PARAM_INT);
                        $stmt->bindValue(":login", $_SESSION['login'], PDO::PARAM_STR);
                        $stmt->bindValue(":tresc", $tresc, PDO::PARAM_STR);
                        $stmt->execute();
                        $stmt->closeCursor();
                        echo '<p class="msg">Komentarz został dodany ;)</p>';
                    } else
                        echo $errors;
                    $stmt->closeCursor();
                } catch (PDOException $e) {
                    echo 'Wystąpił bład podczas dodawania komentarza artykulu: ' . $e->getMessage();
                }
            }
            if (isset($_SESSION['logged']) && isset($_GET['id'])) {
                echo
                '<form action="' . $this->getCurrURL() . '" method="post">
                        <label>Dodaj komentarz</label><br>
                        <textarea rows="4" cols="50" name="tresc" required></textarea>
                        <input type="submit" name="wyslano_komentarz" value="Dodaj komentarz"/>
                     </form>';
            }
            try {
                $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->query('SELECT * FROM komentarze  WHERE id_artykulu=' . $_GET['id'] . ' ');

                foreach ($stmt as $row) {
                    echo '<article class = "koment">';
                    echo '<h1>' . $row['login'] . ' pisze:</h1>';
                    echo '<h2>' . $row['data_dodania'] . '</h2>';
                    echo '<h4>' . $row['tresc'] . '</h4>';
                    echo '</article>';
                }

                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo 'Wystąpił bład podczas wyświetlania komentarzy: ' . $e->getMessage();
            }
            echo ' </article>';
        }
    }

}
