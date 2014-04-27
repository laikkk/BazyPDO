<?php

//Kamil Zieliński 215521 
//ABD projekt II 
//21.04.2014

class OrganizerKategorii {

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

    public function drukujMenuPoziome($argument) {
        echo '<ul id="navigation" >';
        $this->drukujMenu($argument);
        echo '</ul>';
    }

    public function drukujMenuPionowe($argument) {
        echo '<ul id="navigationPion" >';
        $this->drukujMenu($argument);
        echo '</ul>';
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

    private function drukujMenu($id_nadkat) {
        try {
            $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (is_null($id_nadkat)) {
                $stmt = $pdo->query('SELECT id_kategoria,nazwa,id_artykulu FROM kategoria WHERE id_nadkategoria IS NULL ORDER BY id_kategoria');
            } else { //pobierz nazwy oraz id_kategorii
                $stmt = $pdo->query('SELECT id_kategoria,nazwa,id_artykulu FROM kategoria WHERE id_nadkategoria= ' . $id_nadkat . ' ORDER BY id_kategoria');
            }
            $row_count = $stmt->rowCount();
            if (!$row_count) {
                //echo '      Wychodze bo pusty wynik z bazy dostalem<br>';
                return array(); //jezeli nie ma zadnych wierszy to return pusta tablice
            }

            $j = 0;
            $i = 0;

            foreach ($stmt as $row) {
                if (IS_NULL($row['id_artykulu']))
                    echo '<li><a href="index.php" >' . $row['nazwa'] . '</a>';
                else
                    echo '<li><a href="index.php?id=' . $row['id_artykulu'] . '" >' . $row['nazwa'] . '</a>';
                $kategorie[$i][$j] = $row['nazwa'];
                $j++;
                echo '<ul>';
                $tabkat = $this->drukujMenu($row['id_kategoria']);  //dla kazdej id_kategori poszukaj czy nie ma pod kategorii
//print_r($tabkat);
                echo '</ul>';
                if (is_array($tabkat)) {
                    if (!empty($tabkat)) { //jezeli nie jest pusta to dodaj ja do glownej tablicy
                        // echo 'Dodaje do Tablicy Kategorii<br>';
                        $kategorie[$i][$j] = $tabkat;
                        $j = 0;
                    }
                    $i++;
                } else {

                    $i++;
                    $kategorie[$i] = $tabkat;
                }
                echo '</li>';
            }


            $stmt->closeCursor();
            return $kategorie;
        } catch (PDOException $e) {
            echo 'Błąd podczas tworzenia Menu: ' . $e->getMessage();
        }
    }

    public function obslugaDodawaniaUsuwaniaEdycjiKategorii() {
        //dodanie do tabeli
        if (isset($_POST['dodano_menu'])) {
            $id_kategorii = trim($_POST['id_kategorii']);
            $nazwa = trim($_POST['nazwa']);
            $id_nadkategoria = trim($_POST['id_nadkategoria']);
            $id_artykulu = trim($_POST['id_artykulu']);
            $errors = NULL;
            try {
                if (strlen($id_artykulu) == 0)
                    $id_artykulu = NULL;
                else

                //sprawdzanie czy dany id_artykulu istnieje w bazie
                    try {
                        $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        //Sprawdzamy czy użytkownik o takim samym loginie już nie istnieje
                        $stmt = $pdo->prepare("SELECT COUNT(id_artykulu) FROM artykuly WHERE id_artykulu=:id_artykulu");
                        $stmt->bindValue(":id_artykulu", $id_artykulu, PDO::PARAM_STR);
                        $stmt->execute();
                        $row = $stmt->fetch();
                        $stmt->closeCursor();
                        if ($row[0] == 0)
                            $errors .= '<p class="error">Id artykulu o tej wartosci nie istnieje</p>'; //moze return 'Id artykulu o tej wartosci nie istnieje'
                    } catch (PDOException $e) {
                        echo 'Wystąpił bład podczas sprawdzania id_artyulow: ' . $e->getMessage();
                    }





                if (strlen($nazwa) < 3)
                    $errors .= '<p class="error">Nazwa musi zawierać co najmniej 3 znaki</p>';
                if (is_null($errors) || empty($errors)) {
                    $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->prepare("INSERT INTO kategoria(id_kategoria,nazwa,id_nadkategoria,id_artykulu)  VALUES(:id_kategoria,:nazwa,:id_nadkategoria,:id_artykulu)");
                    $stmt->bindValue(":id_kategoria", $id_kategorii, PDO::PARAM_STR);
                    $stmt->bindValue(":nazwa", $nazwa, PDO::PARAM_STR);
                    $stmt->bindValue(":id_nadkategoria", ($id_nadkategoria) ? $id_nadkategoria : NULL, PDO::PARAM_STR);
                    $stmt->bindValue(":id_artykulu", ($id_artykulu) ? $id_artykulu : NULL, PDO::PARAM_STR);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<p class="msg">Pozycja w menu dodana ;)</p>';
                } else
                    echo $errors;
                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo 'Wystąpił bład podczas dodawania pozycji menu: ' . $e->getMessage();
            }
        }
        //zmiana pozycji menu
        if (isset($_POST['zmien_menu']) && isset($_SESSION['logged'])) {
            $id_kategorii = trim($_POST['id_kategoria']);
            $nazwa = trim($_POST['nazwa']);
            $id_nadkategoria = trim($_POST['id_nadkategoria']);
            $id_artykulu = trim($_POST['id_artykulu']);
            try {

                $errors;
                $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
                //sprawdzamy czy dany id_artykulu istnieje w bazie

                if (strlen($nazwa) < 3)
                    $errors .= '<p class="error">Nazwa musi zawierać co najmniej 3 znaki</p>';
                if (strlen($id_kategorii) == 0)
                    $id_kategorii = NULL;
                if (strlen($id_artykulu) == 0)
                    $id_artykulu = NULL;

                if (empty($errors)) {
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->prepare("UPDATE kategoria SET nazwa=:nazwa , id_nadkategoria=:id_nadkategoria , id_artykulu=:id_artykulu WHERE id_kategoria=:id_kategoria");
                    $stmt->bindValue(":id_kategoria", $id_kategorii, PDO::PARAM_STR);
                    $stmt->bindValue(":nazwa", $nazwa, PDO::PARAM_STR);
                    $stmt->bindValue(":id_nadkategoria", ($id_nadkategoria) ? $id_nadkategoria : NULL, PDO::PARAM_STR);
                    $stmt->bindValue(":id_artykulu", ($id_artykulu) ? $id_artykulu : NULL, PDO::PARAM_INT);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<p class="msg">Menu zostało zmienione ;)</p>';
                } else
                    echo $errors;
                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo 'Wystąpił bład podczas zmiany pozycji menu: ' . $e->getMessage();
            }
        }

        //usuniecie pozycji menu
        if (isset($_POST['usun_menu']) && isset($_SESSION['logged'])) {
            $id_kategorii = trim($_POST['id_kategoria']);
            try {
                $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);

                if (!is_numeric($id_kategorii))
                    $errors = '<p class="error">id_kategorii MUSI być liczbą</p>';

                if (empty($errors)) {
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->prepare("DELETE FROM kategoria WHERE id_kategoria=:id_kategoria");
                    $stmt->bindValue(":id_kategoria", $id_kategorii, PDO::PARAM_STR);
                    $stmt->execute();
                    $stmt->closeCursor();
                    echo '<p class="msg">Pozycja została usunięta ;)</p>';
                } else
                    echo $errors;
                $stmt->closeCursor();
            } catch (PDOException $e) {
                echo 'Wystąpił bład podczas zmiany pozycji menu: ' . $e->getMessage();
            }
        }
    }

    public function printTable() {
        try {
            $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            //wypisanie tabeli            
            $stmt = $pdo->query('SELECT id_kategoria,nazwa,id_nadkategoria,id_artykulu FROM kategoria AS k  ORDER BY id_kategoria');
            $row_count = $stmt->rowCount();
            if (!$row_count) {
                return; //jezeli nie ma zadnych wierszy to return pusta tablice
            }
            echo '<div id="Tab" class="table-prices" style="display:none;">          
                <table>
                    <thead>
                        <tr>
                          <th>id_kategoria</th>
                        <th>nazwa</th>
                        <th>id_nadkategoria</th>
                        <th>id_artykulu</th>
                        <th>opcje</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach ($stmt as $row) {
                echo '<tr>
			<td>' . $row['id_kategoria'] . '</td>
			<td>' . $row['nazwa'] . '</td>
			<td>' . $row['id_nadkategoria'] . '</td>
                        <td>' . $row['id_artykulu'] . '</td>
			<td>
                            <a  id="edit_menu' . $row['id_kategoria'] . '"> Edytuj!</a> 
                            <form id="usun_menu' . $row['id_kategoria'] . '" action="" method="post">
                                    <input type="text" name="id_kategoria" value ="' . $row['id_kategoria'] . '"  style="display:none;"/>
                                    <input type="submit" onclick="return confirm(\'Czy napewno chcesz usunąć tą pozycję?\')"  name="usun_menu" value="USUŃ">
                            </form> 
                        </td>
                    </tr>';

                echo '<tr style="display:none;" id="' . $row['id_kategoria'] . '">
			<td></td>
			<td><input form="zmien_menu' . $row['id_kategoria'] . '" type="text" name="nazwa" value ="' . $row['nazwa'] . '" /></td>
			<td><input form="zmien_menu' . $row['id_kategoria'] . '" type="number" pattern="[0-9]*" name="id_nadkategoria" value ="' . $row['id_nadkategoria'] . '" /></td>
                        <td><input form="zmien_menu' . $row['id_kategoria'] . '" type="number" pattern="[0-9]*" name="id_artykulu" value ="' . $row['id_artykulu'] . '" /></td>
			<td> 
                            <form id="zmien_menu' . $row['id_kategoria'] . '" action="" method="post">
                                <input type="number" pattern="[0-9]*" name="id_kategoria" value ="' . $row['id_kategoria'] . '"  style="display:none;"/>
                                <input type="submit"  name="zmien_menu" value="ZMIEN">
                            </form>
                        </td>
		</tr>';
            }
            echo '<tr>
                        <td><input form="dodaj_menu" type="number" pattern="[0-9]*" name="id_kategorii" required/></td>
                        <td><input form="dodaj_menu" type="text" name="nazwa" required/></td>
                        <td><input form="dodaj_menu" type="number" pattern="[0-9]*" name="id_nadkategoria" /></td>
                        <td><input form="dodaj_menu" type="number" pattern="[0-9]*" name="id_artykulu" /></td>
                        <td><form id="dodaj_menu" action="' . $this->getCurrURL() . '" method="post">
                        <input type="submit" name="dodano_menu" value="Dodaj" >
                        
                      </form></td>
                     
                    </tr>';
            echo '</tbody> </table>
           
        </div>';
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo 'Błąd poczas drukowania tabeli kategorii: ' . $e->getMessage();
        }
    }

    private function Check_If_Art_idExist($id_artykulu) {
        try {
            $pdo = new PDO($this->_db_type . ':host=' . $this->_db_host . ';dbname=' . $this->_db_name . ';port=' . $this->_db_port, $this->_db_user, $this->_db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Sprawdzamy czy użytkownik o takim samym loginie już nie istnieje
            $stmt = $pdo->prepare("SELECT COUNT(id_artykulu) FROM artykuly WHERE id_artykulu=:id_artykulu");
            $stmt->bindValue(":id_artykulu", $id_artykulu, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            $stmt->closeCursor();
            $errors = NULL;
            if ($row[0] == 0)
                $errors = 'Id artykulu o tej wartosci nie istnieje'; //moze return 'Id artykulu o tej wartosci nie istnieje'
            return $errors;
        } catch (PDOException $e) {
            //  echo 'Wystąpił bład podczas sprawdzania id_artyulow: ' . $e->getMessage();
        }
    }

}
