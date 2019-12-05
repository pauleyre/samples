CREATE TABLE IF NOT EXISTS `orbx_mod_credit_calc` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `interest` float NOT NULL default '0',
  `language` char(3) NOT NULL default 'en',
  `max_years` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `language` (`language`)
) ;