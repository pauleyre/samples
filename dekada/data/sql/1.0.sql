CREATE TABLE IF NOT EXISTS `answer` (
  `id` bigint(20) NOT NULL auto_increment,
  `question_id` bigint(20) NOT NULL,
  `answer` longtext NOT NULL,
  `member_id` bigint(20) NOT NULL default '0',
  `guestname` varchar(255) NOT NULL default '',
  `submited` int(11) NOT NULL default '0',
  `flags` bigint(20) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `score` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `question_id` (`question_id`)
)  ;



CREATE TABLE IF NOT EXISTS `main` (
  `total_qs` bigint(20) NOT NULL default '0'
) ;

INSERT INTO `main` (`total_qs`) VALUES (0);


CREATE TABLE IF NOT EXISTS `member` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '0',
  `password` longtext NOT NULL,
  `email` varchar(255) NOT NULL default '0',
  `flags` bigint(20) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `joined` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;



CREATE TABLE IF NOT EXISTS `question` (
  `id` bigint(20) NOT NULL auto_increment,
  `category` varchar(255) NOT NULL default '',
  `member_id` bigint(20) NOT NULL default '0',
  `title` text NOT NULL,
  `submited` int(11) NOT NULL default '0',
  `live_time` int(11) NOT NULL default '0',
  `live` tinyint(1) NOT NULL default '0',
  `permalink` text NOT NULL,
  `flags` bigint(20) NOT NULL default '0',
  `reads` bigint(20) NOT NULL default '0',
  `total_as` bigint(20) NOT NULL default '0',
  `has_pic` bigint(20) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `guestname` varchar(255) NOT NULL default '',
  `has_video` bigint(20) NOT NULL default '0',
  `subject` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `category` (`category`),
  KEY `live` (`live`),
  KEY `flags` (`flags`)
)  ;


CREATE TABLE IF NOT EXISTS `sql_error_log` (
  `id` int(11) NOT NULL auto_increment,
  `query` text,
  `error` text,
  `errno` int(11) NOT NULL default '0',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `vote` (
  `id` bigint(20) NOT NULL auto_increment,
  `answer_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `vote` varchar(3) NOT NULL default '',
  `submited` bigint(20) NOT NULL,
  `ip` varchar(255) NOT NULL default '',
  `flags` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `answer_id` (`answer_id`)
) ;






