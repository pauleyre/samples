CREATE TABLE IF NOT EXISTS `orbicon_banners` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `client` text,
  `displays` text,
  `permalink` text,
  `zone` text,
  `language` char(3) NOT NULL default 'hr',
  `clicks` int(11) NOT NULL default '0',
  `start` int(11) NOT NULL default '0',
  `ips` text,
  `img_url` text,
  `banner_type` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;