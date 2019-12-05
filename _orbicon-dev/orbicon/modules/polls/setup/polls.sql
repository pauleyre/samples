CREATE TABLE IF NOT EXISTS `orbicon_survey_questions` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `option_id` tinyint(4) default NULL,
  `poll_permalink` text,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_survey_questions_bck` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `option_id` tinyint(4) default NULL,
  `poll_permalink` text,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_survey_results` (
  `id` int(11) NOT NULL auto_increment,
  `question_id` text,
  `option_id` tinyint(4) default NULL,
  `votes` int(11) default '0',
  `poll_permalink` text,
  PRIMARY KEY  (`id`)
) ;


CREATE TABLE IF NOT EXISTS `orbicon_survey_results_bck` (
  `id` int(11) NOT NULL auto_increment,
  `question_id` text,
  `option_id` tinyint(4) default NULL,
  `votes` int(11) default '0',
  `poll_permalink` text,
  PRIMARY KEY  (`id`)
) ;


CREATE TABLE IF NOT EXISTS `orbicon_poll` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `start_date` tinytext,
  `end_date` tinytext,
  `permalink` text,
  `zone` text,
  `locked_view` int(1) default '0',
  `language` char(3) NOT NULL default 'hr',
  PRIMARY KEY  (`id`),
  KEY `language` (`language`),
  KEY `locked_view` (`locked_view`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_poll_ip` (
  `id` int(11) NOT NULL auto_increment,
  `ip` text,
  `nick` varchar(50) default NULL,
  `msg` text,
  `timestamp` text,
  PRIMARY KEY  (`id`)
) ;



CREATE TABLE IF NOT EXISTS `orbicon_poll_options` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `votes` int(11) default '0',
  `option_id` tinyint(4) default NULL,
  `poll_permalink` text,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_mod_polls_results` (
  `id` int(11) NOT NULL auto_increment,
  `parent_option_id` int(11) default NULL,
  `votes` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `parent_option_id` (`parent_option_id`)
) ;