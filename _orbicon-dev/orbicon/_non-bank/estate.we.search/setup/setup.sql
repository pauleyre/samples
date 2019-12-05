CREATE TABLE `orbx_mod_estate_we_search` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `ad_type` varchar(255) NOT NULL,
  `price_from` decimal(12,2) NOT NULL,
  `price_to` decimal(12,2) NOT NULL,
  `county` varchar(255) NOT NULL,
  `msquare_from` decimal(12,2) NOT NULL,
  `msquare_to` decimal(12,2) NOT NULL,
  `town` text NOT NULL,
  `pics_only` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;