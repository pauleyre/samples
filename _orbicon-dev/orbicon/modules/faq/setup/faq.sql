CREATE TABLE IF NOT EXISTS `orbx_mod_faq_question` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` text NOT NULL,
  `answer` longtext NOT NULL,
  `category` int(11) NOT NULL default '0',
  `poster` varchar(255) NOT NULL default '0',
  `poster_id` varchar(255) NOT NULL default '0',
  `submited` int(11) NOT NULL default '0',
  `live_date` int(11) NOT NULL default '0',
  `live` tinyint(1) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `email_notify` int(11) NOT NULL default '0',
  `permalink` text NOT NULL,
  `lang` varchar(3) NOT NULL default 'en',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`),
  KEY `lang` (`lang`),
  KEY `live` (`live`)
);

CREATE TABLE IF NOT EXISTS `orbx_mod_faq_category` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '0',
  `total_qs` longtext NOT NULL,
  `permalink` varchar(255) NOT NULL default '0',
  `lang` varchar(3) NOT NULL default 'en',
  PRIMARY KEY  (`id`),
  KEY `lang` (`lang`)
);