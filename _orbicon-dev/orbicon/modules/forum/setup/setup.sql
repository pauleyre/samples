CREATE TABLE IF NOT EXISTS `orbicon_forum` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `thread_id` int(10) unsigned NOT NULL default '0',
  `title` text NOT NULL,
  `permalink` text NOT NULL,
  `content` text NOT NULL,
  `mail` text NOT NULL,
  `time` int(11) NOT NULL default '0',
  `thread_time` int(11) NOT NULL default '0',
  `ip` text NOT NULL,
  `user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `wid` (`thread_id`)
) ;