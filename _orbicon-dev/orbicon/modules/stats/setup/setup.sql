CREATE TABLE IF NOT EXISTS `orbicon_stats` (
  `id` bigint(200) NOT NULL auto_increment,
  `entry` longtext,
  `date` int(11) default NULL,
  `type` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `date` (`date`)
) ;



CREATE TABLE IF NOT EXISTS `user_last_search` (
  `user_id` bigint(20) NOT NULL default '0',
  `query` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `user_id` (`user_id`)
) ;


CREATE TABLE IF NOT EXISTS `user_last_visit` (
  `user_id` bigint(20) NOT NULL default '0',
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `user_id` (`user_id`)
) ;


CREATE TABLE IF NOT EXISTS `user_stats` (
  `user_id` bigint(20) NOT NULL default '0',
  `date` date NOT NULL,
  `stats` longtext NOT NULL,
  KEY `user_id` (`user_id`)
) ;


CREATE TABLE IF NOT EXISTS `user_stats_perc` (
  `user_id` bigint(20) NOT NULL default '0',
  `date` date NOT NULL,
  `selling` bigint(20) NOT NULL,
  `info` bigint(20) NOT NULL,
  `misc` bigint(20) NOT NULL,
  KEY `user_id` (`user_id`)
) ;

CREATE TABLE IF NOT EXISTS `user_stats_pers` (
  `user_id` bigint(20) NOT NULL default '0',
  `selling` bigint(20) NOT NULL,
  `info` bigint(20) NOT NULL,
  `misc` bigint(20) NOT NULL,
  KEY `user_id` (`user_id`)
) ;