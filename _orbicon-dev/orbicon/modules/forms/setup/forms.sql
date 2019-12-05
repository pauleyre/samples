CREATE TABLE IF NOT EXISTS `orbicon_forms` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `permalink` text,
  `template` text,
  `adrbks` text,
  `language` char(3) NOT NULL default 'hr',
  `linked_text` text NOT NULL,
  `msg_type` tinyint(1) NOT NULL default '0',
  `redirect` text NOT NULL,
  PRIMARY KEY  (`id`)
) ;