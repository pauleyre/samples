CREATE TABLE `orbx_mod_inpulls_we_search` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `person_looking_for` varchar(255) NOT NULL,
  `sex_group` varchar(255) NOT NULL default '0',
  `years_from` int(3) NOT NULL default '0',
  `years_to` int(3) NOT NULL default '0',
  `county` varchar(255) NOT NULL,
  `horoscope` int(11) NOT NULL default '0',
  `town` text NOT NULL,
  `pics_only` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;