SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `tick`;
CREATE TABLE `tick` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Dataset label',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Data label',
  `data` tinyint(1) unsigned NOT NULL,
  `background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `background_color_opacity` float NOT NULL,
  `border_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `border_color_opacity` float NOT NULL,
  `border_width` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Graph data';
