﻿-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 27 Kwi 2014, 09:49
-- Server version: 5.6.16
-- PHP Version: 5.5.11



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: kzielinski
--
--DROP DATABASE kzielinski;

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

INSERT INTO artykuly (id_artykulu, tytul, tresc, data_utworzenia) VALUES
(1, 'Powstaje gra o polskiej armii. Ju? w ni? grali?my!', 'Mimo ?e nasz kraj ma jedn? z najwi?kszych tradycji wojennych i nasze wojsko nale?y do cenionych na ?wiecie, powstaje niewiele tytu?ów po?wi?conych naszym ?o?nierzom. Niebawem jednak mo?e si? to zmieni? dzi?ki tworzonej przez OBRUM grze, która b?dzie polskim odpowiednikiem America’s Army.', '2014-04-19 16:43:46'),
(2, 'Czym jest Lorem Ipsum?', 'AAAALorem Ipsum jest tekstem stosowanym jako przyk?adowy wype?niacz w przemy?le poligraficznym. Zosta? po raz pierwszy u?yty w XV w. przez nieznanego drukarza do wype?nienia tekstem prï¿½bnej ksi??ki. Pi?? wiekï¿½w pï¿½?niej zacz?? by? u?ywany przemy?le elektronicznym, pozostaj?c praktycznie niezmienionym. Spopularyzowa? si? w latach 60. XX w. wraz z publikacj? arkuszy Letrasetu, zawieraj?cych fragmenty Lorem Ipsum, a ostatnio z zawieraj?cym rï¿½?ne wersje Lorem Ipsum oprogramowaniem przeznaczonym do realizacji drukï¿½w na komputerach osobistych, jak Aldus PageMaker', '2014-04-19 16:53:40'),
(3, 'Do czego tego użyć?', 'Ogólnie znana teza głosi, iż użytkownika może rozpraszać zrozumiała zawartość strony, kiedy ten chce zobaczyć sam jej wygląd. Jedną z mocnych stron używania Lorem Ipsum jest to, że ma wiele różnych „kombinacji” zdań, słów i akapitów, w przeciwieństwie do zwykłego: „tekst, tekst, tekst”, sprawiającego, że wygląda to „zbyt czytelnie” po polsku. Wielu webmasterów i designerów używa Lorem Ipsum jako domyślnego modelu tekstu i wpisanie w internetowej wyszukiwarce ‘lorem ipsum’ spowoduje znalezienie bardzo wielu stron, które wciąż są w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie (humorystyczne wstawki itd).', '2014-04-19 16:54:11'),
(4, 'testArtykulu', 'test tresc', '2014-04-21 05:58:32'),
(5, '22222213', 'sadasfasf', '2014-04-21 06:03:01'),
(6, '1231242', '12312312412', '2014-04-21 12:52:12');

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

INSERT INTO kategoria (id_kategoria, nazwa, id_nadkategoria, id_artykulu) VALUES
(1, 'Informatyka', NULL, 1),
(2, 'Algrotytmy', 1, 2),
(3, 'Bazy Danych', 1, NULL),
(4, 'Struktury danych', 1, NULL),
(5, 'Fizyka', NULL, NULL),
(6, 'Geografia', NULL, NULL),
(7, 'Azja', 6, NULL),
(8, 'Europa', 6, NULL),
(9, 'Biologia', NULL, NULL),
(10, 'WF', NULL, NULL),
(11, 'AR', 1, NULL),
(12, 'QuickSort', 2, 3),
(13, 'NOWY test', NULL, 1),
(14, 'tesst', NULL, 3),
(15, 'testklasy', 14, 3),
(16, 'aaa', 14, 4);

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

INSERT INTO komentarze (id_komentarze, id_artykulu, login, tresc, data_dodania) VALUES
(1, 1, 'kamil', 'komentarz1', '2014-04-19 17:45:35'),
(2, 1, 'kamil', 'komentarz2', '2014-04-19 17:45:55'),
(3, 1, 'kamil', 'test komentarza', '2014-04-19 18:11:07'),
(4, 3, 'kamil', 'ten quick sort jest quick ;)', '2014-04-19 18:11:37'),
(5, 2, 'kamil', 'jupi ale fajnie', '2014-04-20 14:17:37'),
(6, 3, 'kamil', 'test1234123', '2014-04-21 11:58:49');

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

INSERT INTO users (id_users, login, password, email) VALUES
(5, 'kamil', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'kamil@wp.pl'),
(6, 'edek', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'edek@wp.pl'),
(7, 'kacper', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'kacper@wp.pl');

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli kategoria
--
ALTER TABLE kategoria
  ADD CONSTRAINT foreignkeykat_arty FOREIGN KEY (id_artykulu) REFERENCES artykuly (id_artykulu) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ograniczenia dla tabeli komentarze
--
ALTER TABLE komentarze
  ADD CONSTRAINT komentarze_ibfk_1 FOREIGN KEY (id_artykulu) REFERENCES artykuly (id_artykulu);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
