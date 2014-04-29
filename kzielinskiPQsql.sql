-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 29 Kwi 2014, 15:34
-- Server version: 5.6.16
-- PHP Version: 5.5.11


--
-- Database: kzielinski
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli artykuly
--

CREATE TABLE IF NOT EXISTS artykuly (
  id_artykulu SERIAL NOT NULL ,
  tytul varchar(512)   NOT NULL,
  tresc varchar(4048)   NOT NULL,
  data_utworzenia timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_artykulu)
);

--
-- Zrzut danych tabeli artykuly
--

INSERT INTO artykuly (tytul, tresc, data_utworzenia) VALUES
( 'Powstaje gra o polskiej armii. Już w nią graliśmy!', 'Mimo że nasz kraj ma jedną z największych tradycji wojennych i nasze wojsko należy do cenionych na świecie, powstaje niewiele tytułów poświęconych naszym żołnierzom. Niebawem jednak może się to zmienić dzięki tworzonej przez OBRUM grze, która będzie polskim odpowiednikiem America’s Army.', '2014-04-19 16:43:46'),
( 'Czym jest Lorem Ipsum?', 'Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle poligraficznym. Został po raz pierwszy użyty w XV w. przez nieznanego drukarza do wypełnienia tekstem próbnej książki. Pięć wieków później zaczął być używany przemyśle elektronicznym, pozostając praktycznie niezmienionym. Spopularyzował się w latach 60. XX w. wraz z publikacją arkuszy Letrasetu, zawierających fragmenty Lorem Ipsum, a ostatnio z zawierającym różne wersje Lorem Ipsum oprogramowaniem przeznaczonym do realizacji druków na komputerach osobistych, jak Aldus PageMaker', '2014-04-19 16:53:40'),
( 'Do czego tego użyć?', 'Ogólnie znana teza głosi, iż użytkownika może rozpraszać zrozumiała zawartość strony, kiedy ten chce zobaczyć sam jej wygląd. Jedną z mocnych stron używania Lorem Ipsum jest to, że ma wiele różnych „kombinacji” zdań, słów i akapitów, w przeciwieństwie do zwykłego: „tekst, tekst, tekst”, sprawiającego, że wygląda to „zbyt czytelnie” po polsku. Wielu webmasterów i designerów używa Lorem Ipsum jako domyślnego modelu tekstu i wpisanie w internetowej wyszukiwarce ‘lorem ipsum’ spowoduje znalezienie bardzo wielu stron, które wciąż są w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie (humorystyczne wstawki itd).', '2014-04-19 16:54:11'),
( 'testArtykulu', 'test tresc', '2014-04-21 05:58:32');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli kategoria
--

CREATE TABLE IF NOT EXISTS kategoria (
  id_kategoria SERIAL NOT NULL,
  nazwa varchar(50) NOT NULL,
  id_nadkategoria int DEFAULT NULL REFERENCES kategoria(id_kategoria),
  id_artykulu int DEFAULT NULL,
  PRIMARY KEY (id_kategoria)
  --KEY foreignkeykat_arty (id_artykulu)
);

--
-- RELACJE TABELI kategoria:
--   id_artykulu
--       artykuly -> id_artykulu
--

--
-- Zrzut danych tabeli kategoria
--

INSERT INTO kategoria ( nazwa, id_nadkategoria, id_artykulu) VALUES
( 'Informatyka', NULL, 1),
( 'Algrotytmy', 1, 2),
( 'Bazy Danych', 1, NULL),
( 'Struktury danych', 1, NULL),
( 'Fizyka', NULL, NULL),
( 'Geografia', NULL, NULL),
( 'Azja', 6, NULL),
( 'Europa', 6, NULL),
( 'Biologia', NULL, NULL),
( 'WF', NULL, NULL),
( 'AR', 1, NULL),
( 'QuickSort', 2, 3),
( 'NOWY test', NULL, 1),
( 'tesst', NULL, 3),
( 'testklasy', 14, 3),
( 'aaa', 14, 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli komentarze
--

CREATE TABLE IF NOT EXISTS komentarze (
  id_komentarze SERIAL NOT NULL,
  id_artykulu int NOT NULL REFERENCES artykuly(id_artykulu),
  login varchar(128) NOT NULL,
  tresc varchar(4048) NOT NULL,
  data_dodania timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_komentarze)
);

--
-- RELACJE TABELI komentarze:
--   id_artykulu
--       artykuly -> id_artykulu
--

--
-- Zrzut danych tabeli komentarze
--

INSERT INTO komentarze ( id_artykulu, login, tresc, data_dodania) VALUES
( 1, 'kamil', 'komentarz1', '2014-04-19 17:45:35'),
( 1, 'kamil', 'komentarz2', '2014-04-19 17:45:55'),
( 1, 'kamil', 'test komentarza', '2014-04-19 18:11:07'),
( 3, 'kamil', 'ten quick sort jest quick ;)', '2014-04-19 18:11:37'),
( 2, 'kamil', 'jupi ale fajnie', '2014-04-20 14:17:37'),
( 3, 'kamil', 'test1234123', '2014-04-21 11:58:49');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli users
--

CREATE TABLE IF NOT EXISTS users (
  id_users SERIAL NOT NULL,
  login varchar(128) NOT NULL,
  password varchar(256) NOT NULL,
  email varchar(256) NOT NULL,
  PRIMARY KEY (id_users)
);

--
-- Zrzut danych tabeli users
--

INSERT INTO users ( login, password, email) VALUES
( 'kamil', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'kamil@wp.pl'),
( 'edek', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'edek@wp.pl'),
( 'kacper', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'kacper@wp.pl');

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli kategoria
--
ALTER TABLE kategoria
  ADD CONSTRAINT kategoria_ibfk_1 FOREIGN KEY (id_artykulu) REFERENCES artykuly (id_artykulu) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ograniczenia dla tabeli komentarze
--
ALTER TABLE komentarze
  ADD CONSTRAINT komentarze_ibfk_1 FOREIGN KEY (id_artykulu) REFERENCES artykuly (id_artykulu);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
