CREATE TABLE IF NOT EXISTS `orbx_mod_exch_rates` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(255) NOT NULL default '',
  `unit` int(11) NOT NULL default '1',
  `buying_1` text,
  `buying_2` text,
  `middle_rate` text,
  `selling_1` text,
  `selling_2` text,
  `valid_date` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `valid_date` (`valid_date`),
  KEY `code` (`code`)
) ;