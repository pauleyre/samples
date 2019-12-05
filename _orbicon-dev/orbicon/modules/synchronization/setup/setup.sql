CREATE TABLE IF NOT EXISTS `orbx_sync_servers` (
  `id` int(11) NOT NULL auto_increment,
  `server` text,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_sync_servers_props` (
  `id` int(11) NOT NULL auto_increment,
  `server_id` int(11) NOT NULL default '0',
  `setting` text,
  `value` text,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_sync_cache` (
  `hash` varchar(255) default NULL,
  `filename` varchar(255) default NULL,
  KEY `hash` (`hash`),
  KEY `filename` (`filename`)
) ;