SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `mycms`
--

-- --------------------------------------------------------

--
-- Structure de la table `cm_article`
--

CREATE TABLE IF NOT EXISTS `cm_article` (
  `article_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `article_type` varchar(10) NOT NULL,
  `article_title` varchar(400) NOT NULL,
  `article_author` varchar(400) NOT NULL,
  `article_extract` varchar(600) NOT NULL,
  `article_content` longtext NOT NULL,
  `article_name` longtext NOT NULL,
  `article_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `article_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `article_featured` tinyint(1) NOT NULL DEFAULT '0',
  `article_status` int(2) NOT NULL DEFAULT '0',
  `article_meta` longtext NOT NULL,
  PRIMARY KEY (`article_id`),
  KEY `article_author` (`article_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=912 ;

-- --------------------------------------------------------

--
-- Structure de la table `cm_comment`
--

CREATE TABLE IF NOT EXISTS `cm_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_post` int(11) NOT NULL,
  `comment_date` datetime NOT NULL,
  `comment_author` varchar(100) NOT NULL,
  `comment_email` varchar(100) NOT NULL,
  `comment_web` varchar(150) NOT NULL,
  `comment_picture` varchar(500) NOT NULL,
  `comment_ip` varchar(50) NOT NULL,
  `comment_reply` int(11) NOT NULL,
  `comment_content` longtext NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `cm_links`
--

CREATE TABLE IF NOT EXISTS `cm_links` (
  `link_id` int(5) NOT NULL AUTO_INCREMENT,
  `link_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `link_name` varchar(200) NOT NULL,
  `link_desc` text NOT NULL,
  `link_order` int(5) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- Structure de la table `cm_meta`
--

CREATE TABLE IF NOT EXISTS `cm_meta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `meta_name` varchar(32) NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Structure de la table `cm_meta_relationship`
--

CREATE TABLE IF NOT EXISTS `cm_meta_relationship` (
  `article_id` bigint(20) NOT NULL,
  `meta_id` bigint(20) NOT NULL,
  KEY `article_id` (`article_id`),
  KEY `meta_id` (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `cm_notes`
--

CREATE TABLE IF NOT EXISTS `cm_notes` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `note_content` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table `cm_user`
--

CREATE TABLE IF NOT EXISTS `cm_user` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_username` varchar(32) NOT NULL,
  `user_password` char(32) NOT NULL,
  `user_salt` char(20) NOT NULL,
  `user_email` varchar(80) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT '1',
  `user_picture` varchar(200) NOT NULL,
  `user_last_access` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `cm_user_status`
--

CREATE TABLE IF NOT EXISTS `cm_user_status` (
  `status_id` int(10) NOT NULL,
  `status_name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
