CREATE TABLE IF NOT EXISTS `orbx_mod_newsalerts` (
  `id` int(11) NOT NULL auto_increment,
  `news_id` int(11) NOT NULL default '0',
  `last_sent` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;


CREATE TABLE IF NOT EXISTS `orbx_mod_newsalerts_subs` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;