-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Ned 14. pro 2014, 17:43
-- Verze serveru: 5.5.40-0+wheezy1-log
-- Verze PHP: 5.4.4-14+deb7u14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `bundle_offi`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_articles`
--

CREATE TABLE IF NOT EXISTS `bundle_articles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(1000) COLLATE utf8_czech_ci NOT NULL,
  `Content` mediumtext COLLATE utf8_czech_ci NOT NULL,
  `Datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Author` int(11) NOT NULL,
  `ShowDatetime` int(11) NOT NULL DEFAULT '0',
  `AllowComments` int(11) NOT NULL DEFAULT '1',
  `ShowInView` int(11) NOT NULL DEFAULT '1',
  `Status` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_articles`
--

INSERT INTO `bundle_articles` (`ID`, `Title`, `Content`, `Datetime`, `Author`, `ShowDatetime`, `AllowComments`, `ShowInView`, `Status`) VALUES(1, 'Vítejte na vašem novém webu', '<p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>\r\n\r\n<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>\r\n\r\n<!-- pagebreak -->\r\n\r\n<hr />\r\n<h2>Header Level 2</h2>\r\n	       \r\n<ol>\r\n   <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>\r\n   <li>Aliquam tincidunt mauris eu risus.</li>\r\n</ol>\r\n\r\n<blockquote><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p></blockquote>\r\n\r\n<h3>Header Level 3</h3>\r\n\r\n<ul>\r\n   <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>\r\n   <li>Aliquam tincidunt mauris eu risus.</li>\r\n</ul>\r\n\r\n<pre><code class="language-css">#header h1 a { \r\n	display: block; \r\n	width: 300px; \r\n	height: 80px; \r\n}</code></pre>\r\n\r\n<hr />\r\n\r\n<table class="data">\r\n	<thead>\r\n		<tr>\r\n			<th>Item</th>\r\n			<th>Item</th>\r\n			<th>Item</th>\r\n			<th>Item</th>\r\n		</tr>\r\n	</thead>\r\n	<tbody>\r\n		<tr>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n			<td>Item</td>\r\n		</tr>\r\n	</tbody>\r\n</table>    ', '2014-08-31 11:04:47', 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_article_categories`
--

CREATE TABLE IF NOT EXISTS `bundle_article_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Article` int(11) NOT NULL,
  `Category` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_article_categories`
--

INSERT INTO `bundle_article_categories` (`ID`, `Article`, `Category`) VALUES(1, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_categories`
--

CREATE TABLE IF NOT EXISTS `bundle_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(500) COLLATE utf8_czech_ci NOT NULL,
  `Parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_categories`
--

INSERT INTO `bundle_categories` (`ID`, `Title`, `Parent`) VALUES(1, 'Novinky', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_comments`
--

CREATE TABLE IF NOT EXISTS `bundle_comments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Author` int(11) NOT NULL,
  `IP` int(11) NOT NULL,
  `Datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Text` varchar(2000) COLLATE utf8_czech_ci NOT NULL,
  `Page` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=9 ;

--
-- Vypisuji data pro tabulku `bundle_comments`
--

INSERT INTO `bundle_comments` (`ID`, `Author`, `IP`, `Text`, `Page`) VALUES(1, 1, 0, 'Testovací komentář', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_config`
--

CREATE TABLE IF NOT EXISTS `bundle_config` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(300) COLLATE utf8_czech_ci NOT NULL,
  `Value` varchar(21000) COLLATE utf8_czech_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_config`
--

INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(1, 'HomeMenuTitle', 'Úvod');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(2, 'Author', 'František Novák');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(3, 'Name', 'Bundle');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(4, 'CommentText', 'Obsah příspěvku byl odstraněn, protože porušoval pravidla stránek.');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(5, 'Icon', 'images/home.png');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(6, 'AllowRegister', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(7, 'Theme', 'default');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(8, 'Homepage', '0');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(9, 'AllowUnregistredComments', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(10, 'AllowComments', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(11, 'AllowUserPhoto', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(12, 'UserPhotoMaxSize', '400');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(13, 'Footer', '<p>Web vytvořen systémem Bundle.</p>\r\n');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(14, 'PagerMax', '5');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(15, 'HomeMenu', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(16, 'CategoriesMenu', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(17, 'PagesMenu', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(18, 'PackagesMenu', '1');
INSERT INTO `bundle_config` (`ID`, `Name`, `Value`) VALUES(19, 'ArticlesMenu', '1');

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_content`
--

CREATE TABLE IF NOT EXISTS `bundle_content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `Data` int(11) NOT NULL,
  `HomeOnly` int(11) NOT NULL,
  `ContentOrder` int(11) NOT NULL,
  `Place` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_content`
--

INSERT INTO `bundle_content` (`ID`, `Type`, `Data`, `HomeOnly`, `ContentOrder`, `Place`) VALUES(1, 'main', 0, 0, 0, 'content');
INSERT INTO `bundle_content` (`ID`, `Type`, `Data`, `HomeOnly`, `ContentOrder`, `Place`) VALUES(2, 'main', 0, 0, 0, 'footer');

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_menu`
--

CREATE TABLE IF NOT EXISTS `bundle_menu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MenuOrder` int(11) NOT NULL,
  `Url` int(11) NOT NULL,
  `Parent` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_menu`
--

INSERT INTO `bundle_menu` (`ID`, `MenuOrder`, `Url`, `Parent`) VALUES(1, 0, 2, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_packages`
--

CREATE TABLE IF NOT EXISTS `bundle_packages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(500) COLLATE utf8_czech_ci NOT NULL,
  `Title` varchar(200) COLLATE utf8_czech_ci DEFAULT NULL,
  `IsActive` int(11) DEFAULT '0',
  `AdminMenu` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_pages`
--

CREATE TABLE IF NOT EXISTS `bundle_pages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(2000) COLLATE utf8_czech_ci NOT NULL,
  `Author` int(11) NOT NULL,
  `Datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Content` mediumtext COLLATE utf8_czech_ci NOT NULL,
  `Parent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_pages`
--

INSERT INTO `bundle_pages` (`ID`, `Title`, `Author`, `Content`) VALUES(1, 'Nová stránka', 1, '<p>Instalátorem vytvořená stránka</p>');
-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_urls`
--

CREATE TABLE IF NOT EXISTS `bundle_urls` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Url` varchar(500) COLLATE utf8_czech_ci NOT NULL,
  `Type` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `Data` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_urls`
--

INSERT INTO `bundle_urls` (`ID`, `Url`, `Type`, `Data`) VALUES(1, 'vitejte-na-vasem-novem-webu', 'article', 1);
INSERT INTO `bundle_urls` (`ID`, `Url`, `Type`, `Data`) VALUES(2, 'nova-stranka', 'page', 1);
INSERT INTO `bundle_urls` (`ID`, `Url`, `Type`, `Data`) VALUES(3, 'novinky', 'category', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `bundle_users`
--

CREATE TABLE IF NOT EXISTS `bundle_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(500) COLLATE utf8_czech_ci NOT NULL,
  `Password` varchar(160) COLLATE utf8_czech_ci NOT NULL,
  `Role` int(11) NOT NULL,
  `Email` varchar(500) COLLATE utf8_czech_ci NOT NULL,
  `Photo` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

--
-- Vypisuji data pro tabulku `bundle_users`
--
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
