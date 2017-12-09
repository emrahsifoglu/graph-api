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

INSERT INTO `tick` (`id`, `title`, `name`, `data`, `background_color`, `background_color_opacity`, `border_color`, `border_color_opacity`, `border_width`) VALUES
(1,	'votes',	'red',	12,	'#ff6384',	0.2,	'#ff6384',	1,	1),
(2,	'votes',	'blue',	19,	'#36a2eb',	0.2,	'#36a2eb',	1,	1),
(3,	'votes',	'yellow',	3,	'#ffce56',	0.2,	'#ffce56',	1,	1),
(4,	'votes',	'green',	5,	'#4bc0c0',	0.2,	'#4bc0c0',	1,	1),
(5,	'votes',	'purple',	2,	'#9966ff',	0.2,	'#9966ff',	1,	1),
(6,	'votes',	'orange',	3,	'#ff9f40',	0.2,	'#ff9f40',	1,	1),
(7,	'attendance',	'red',	16,	'#ffce56',	0.2,	'#ff6384',	1,	1),
(8,	'attendance',	'blue',	22,	'#4bc0c0',	0.2,	'#36a2eb',	1,	1),
(9,	'attendance',	'yellow',	5,	'#9966ff',	0.2,	'#ffce56',	1,	1),
(10,	'attendance',	'green',	10,	'#ff6384',	0.2,	'#4bc0c0',	1,	1),
(11,	'attendance',	'purple',	3,	'#36a2eb',	0.2,	'#9966ff',	1,	1),
(12,	'attendance',	'orange',	18,	'#fffaa4',	0.2,	'#ff9f40',	1,	1);
