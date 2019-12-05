CREATE TABLE IF NOT EXISTS `orbicon_news` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) default NULL,
  `date` varchar(40) default NULL,
  `editor` varchar(100) default NULL,
  `content` text,
  `image` text,
  `intro` text,
  `rss_push` tinyint(4) NOT NULL default '0',
  `keywords` text NOT NULL,
  `permalink` text NOT NULL,
  `category` varchar(200) NOT NULL default 'main',
  `forum_topic_id` int(11) NOT NULL default '0',
  `tema` text NOT NULL,
  `live` tinyint(4) NOT NULL default '0',
  `created` varchar(40) default NULL,
  `redirect` text NOT NULL,
  `language` char(3) NOT NULL default 'hr',
  `template` varchar(255) NOT NULL default 'column.html',
  `views` int(11) NOT NULL default '0',
  `permalink_ascii` text,
  `date_text` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `language` (`language`),
  KEY `category` (`category`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_news_category` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `permalink` text,
  `sort` int(11) NOT NULL default '0',
  `language` char(3) NOT NULL default 'hr',
  `scheme_rows` int(2) NOT NULL default '1',
  `scheme_columns` int(2) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `language` (`language`)
) ;