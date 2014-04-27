<!--
Kamil Zieliński 215521 
ABD projekt II 
21.04.2014
-->
<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP - Aplikacje Bazodanowe</title>
        <link rel="stylesheet" href="style.css">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" />
        <!-- IE6-8 support of HTML5 elements --> <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!--SCRIPTS-->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

        <!--SLIDING PANEL HEIGHT ADJUST TO DOCUMENT HEIGHT-->
        <script type="text/javascript">
            $(document).ready(function() {
                $("#menu").height($(document).height());
                $("#ShowTable").click(function() {
                    $("#Tab").toggle("slow", function() {
                        // Animation complete.
                    });
                });
                $("#ShowArt").click(function() {
                    $("#Art").toggle("slow", function() {
                        // Animation complete.
                    });
                });
                $("#bgorder").click(function() {
                    var idkat = $(this).parent().get(0).text();
                    console.log(idkat);

                });
                $("a[id*='edit_menu']").click(function() {
                    var id = $(this).attr('id').replace(/edit_menu/, '');
                    // alert("id = "+id);
                    $("#" + id).toggle();
                    console.log("log test");
                });
                $("#edit_art").click(function() {
                    $("#edit_art_").toggle("slow");

                });
                $("#add_art").click(function() {
                    $("#add_art_form").toggle("slow");
                });
                $("#log").click(function() {
                    console.log("lohin");
                    $("body").append('');
                    $("#logpopup").show();
                    $(".close").click(function() {
                        $(".popup, .overlay").hide();
                    });
                });
                $("#reg").click(function(e) {

                    $("body").append('');
                    $("#regpopup").show();
                    $(".close").click(function(e) {
                        $(".popup, .overlay").hide();
                    });
                });


            });
        </script>
        <!--SLIDING PANEL DELAY AND HIDE-->
        <script type="text/javascript">
            $(document).ready(function() {
                setTimeout(function() {
                    $('#menu').css('left', '-130px');
                }, 1000);<!-- Change 'left' to 'right' for panel to appear to the right -->
            });
        </script>
    </head>
    <body> 

        <div class="ar login_popup">
            <div id="logpopup" class="popup">
                <a href="#" class="close">ZAMKNIJ</a>
                <form id="loginform" 
                      action="login.php" 
                      method="post" >
                    <P><span class="title">Login</span> <input name="login" type="text" /></P>
                    <P><span class="title">Hasło</span> <input name="password" type="password" /></P>
                    <P><input name="logowanie" type="submit" value="Login" /></P>
                </form>
            </div>
            <div id="regpopup" class="popup">
                <a href="#" class="close">ZAMKNIJ</a>
                <form action="register.php" method="post">
                    <P><span class="title">Login</span> <input type="text" name="login" required></P>
                    <P><span class="title">Hasło</span> 
                        <input type="password" name="password" required>
                    </P>
                    <P><span class="title">Hasło 2</span> 
                        <input type="password" name="password2" required>
                    </P>
                    <P><span class="title">E-mail</span> 
                        <input type="email" name="email" required>
                    </P>
                    <input type="submit" name="rejestracja" value="Załóż konto"/>
                </form>
            </div>
        </div>
        <!--SLIDING MENU PANEL-->
        <div id="menu">
            <div class="arrow">&lt;</div>
            <nav class="nav"><?php
                if (isset($_SESSION['logged'])) {
                    echo "Jesteś zalogowany id = " . $_SESSION['user_id'];
                    echo '<a  href="index.php">Strona Główna</a>';
                    echo '<a  id="ShowTable">Pokaż Tabele</a>';
                    echo '<a  id="ShowArt">Pokaż Artykuły</a>';
                    echo '<a  id="add_art">Dodaj Artykuł</a>';
                    echo '<a href="wyloguj.php">Wyloguj</a>';
                } else {
                    echo '<a  href="index.php">Strona Główna</a>';
                    echo '<a class="button" id="log">Logowanie</a>';
                    echo '<a class="button" id="reg">Rejestracja</a>';
                }
                ?>           
            </nav>
        </div>
        <!--END SLIDING MENU PANEL-->
        <?php
        require('./Config.php');
        //require('./ConfigPQ.php');
        require('./OrganizerKategorii.php');
        require('./ArticleMenager.php');
        $OrganizerKategorii = new OrganizerKategorii($_db_type, $_db_host, $_db_name, $_db_port, $_db_user, $_db_pass);
        ?>
        <nav id="nav" class="clearfix">
<!--<p class="msg">Pozycja w menu dodana ;)</p>-->
<!--<p class="error">Nazwa musi zawierać co najmniej 3 znaków</p>-->
            <?php
            $OrganizerKategorii->obslugaDodawaniaUsuwaniaEdycjiKategorii();
            $OrganizerKategorii->drukujMenuPoziome(NULL);
            ?>

        </nav>
        <!--        <nav id="nav" class="clearfix">
        <?php
        //$OrganizerKategorii->drukujMenuPionowe(NULL);
        ?>
                </nav>-->
        <!--        formularz dodawanie artykulow-->
        <article id="add_art_form" class="edit_article" style="display:none;" >
            <form action="" method="post">
                <input type="text" name="tytul" required/>
                <textarea rows="4" cols="50" name="tresc" required ></textarea>
                <input type="submit" name="dodaj_artykul" value="Dodaj Artykul"/>
            </form>
        </article>
        <?php
        $MenagerArtykulow = new ArticleMenager($_db_type, $_db_host, $_db_name, $_db_port, $_db_user, $_db_pass);
        $MenagerArtykulow->obslugaDodawaniaArtykulow();
        $OrganizerKategorii->printTable();
        $MenagerArtykulow->wyswietlArtykuly();
        ?>
    

        <?php
        $MenagerArtykulow->Edit_and_Show_Article();
        ?>

    </body>
</html>

