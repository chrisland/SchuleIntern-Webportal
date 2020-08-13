-- Create syntax for TABLE 'ganztags_gruppen'
CREATE TABLE `ganztags_gruppen` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sortOrder` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'ganztags_schueler'
CREATE TABLE `ganztags_schueler` (
  `asvid` varchar(200) NOT NULL DEFAULT '',
  `info` varchar(255) DEFAULT NULL,
  `gruppe` int(11) DEFAULT NULL,
  `tag_mo` tinyint(1) DEFAULT NULL,
  `tag_di` tinyint(1) DEFAULT NULL,
  `tag_mi` tinyint(1) DEFAULT NULL,
  `tag_do` tinyint(1) DEFAULT NULL,
  `tag_fr` tinyint(1) DEFAULT NULL,
  `tag_sa` tinyint(1) DEFAULT NULL,
  `tag_so` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`asvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `schueler`
ADD `schuelerGanztagBetreuung` int(11) NOT NULL DEFAULT '0';