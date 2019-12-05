CREATE TABLE IF NOT EXISTS `orbx_mod_ic_answer` (
  `id` bigint(20) NOT NULL auto_increment,
  `content` text NOT NULL,
  `author` int(11) NOT NULL default '0',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00',
  `editor` int(11) NOT NULL default '0',
  `question` int(11) NOT NULL default '0',
  `state` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `question` (`question`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_mod_ic_category` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `author` varchar(255) NOT NULL default '',
  `state` tinyint(1) NOT NULL default '0',
  `lang` varchar(3) NOT NULL default 'en',
  `sortnum` int(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `lang` (`lang`)
) ;

CREATE TABLE `orbx_mod_ic_question` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `author` varchar(255) NOT NULL default '0',
  `created` timestamp NOT NULL default NOW(),
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00',
  `live` timestamp NOT NULL default '0000-00-00 00:00:00',
  `editor` int(11) NOT NULL default '0',
  `category` int(11) NOT NULL default '0',
  `state` tinyint(1) NOT NULL default '0',
  `mail` varchar(255) NOT NULL default '',
  `notify` tinyint(1) NOT NULL default '0',
  `permalink` text NOT NULL,
  `tags` text,
  `lang` varchar(3) NOT NULL default 'en',
  `mail_answer` tinyint(1) NOT NULL default '0',
  `total_rating` decimal(4,2) NOT NULL default '0.00',
  `notify_sent` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`),
  KEY `lang` (`lang`)
);

CREATE TABLE `orbx_mod_ic_ratings` (
  `id` bigint(20) NOT NULL auto_increment,
  `aid` bigint(20) NOT NULL COMMENT 'answer_id',
  `vote` int(4) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `orbx_mod_ic_settings` (
  `id` int(11) NOT NULL auto_increment,
  `alt_author` varchar(255) NOT NULL default '',
  `admin_per_page` int(4) NOT NULL default '10',
  `public_per_page` int(4) NOT NULL default '10',
  `apply_author_info` tinyint(1) NOT NULL default '0',
  `mail_required` tinyint(1) NOT NULL default '0',
  `question_notif` tinyint(1) NOT NULL default '0',
  `question_notif_mail` text NOT NULL,
  `author` tinyint(1) NOT NULL default '1',
  `category` tinyint(1) NOT NULL default '1',
  `date_show` tinyint(1) NOT NULL default '1',
  `depart` tinyint(1) NOT NULL default '1',
  `listing_titles` tinyint(1) NOT NULL default '3',
  `intro` tinyint(1) NOT NULL default '0',
  `answer_privileges` text NOT NULL,
  `intro_text` text NOT NULL,
  `tag_cloud` tinyint(1) NOT NULL default '0',
  `apply_title_info` tinyint(4) NOT NULL default '1',
  `append_polls` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;



INSERT INTO 
	`orbx_mod_ic_settings` 
		(`id`, `alt_author`, `admin_per_page`, `public_per_page`, `apply_author_info`, `mail_required`, `question_notif`, `question_notif_mail`, `author`, `category`, `date_show`, `depart`, `listing_titles`, `intro`, `answer_privileges`, `intro_text`, `tag_cloud`, `apply_title_info`, `append_polls`) VALUES
(1, '', 10, 10, 1, 0, 0, '', 1, 1, 1, 1, 3, 1, 'a', '', 1, 2, 0);

-- --------------------------------------------------------



CREATE TABLE `orbx_mod_ic_stat` (
  `id` bigint(20) NOT NULL auto_increment,
  `qid` bigint(20) NOT NULL default '0',
  `clicked_time` timestamp NOT NULL default NOW(),
  `clicker` varchar(255) NOT NULL default '',
  `clicker_name` text NOT NULL,
  `vid` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
);





CREATE TABLE `orbx_mod_ic_tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag_title` varchar(255) NOT NULL,
  `lang` varchar(3) NOT NULL,
  PRIMARY KEY  (`id`)
);




CREATE TABLE `orbx_mod_ic_tag_handler` (
  `id` bigint(20) NOT NULL auto_increment,
  `tagid` bigint(20) NOT NULL,
  `qid` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
);




CREATE TABLE `orbx_mod_ic_watch` (
`id` BIGINT( 20 ) NOT NULL auto_increment,
`uid` BIGINT( 20 ) NOT NULL ,
`qid` BIGINT( 20 ) NOT NULL,
  PRIMARY KEY  (`id`)
);