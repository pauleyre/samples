
DROP TABLE IF EXISTS `orbx_mod_orbitum_pickup_depot`;
CREATE TABLE `orbx_mod_orbitum_pickup_depot` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(255) NOT NULL default '',
  `products` text,
  `order_id` int(11) default NULL,
  `purchased` int(11) default NULL,
  `valid_until` int(11) default NULL,
  `finished` int(1) default '0',
  `domain` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ID` (`id`)
)  ;


DROP TABLE IF EXISTS `orbx_mod_orbitum_products`;
CREATE TABLE `orbx_mod_orbitum_products` (
  `id` int(11) NOT NULL auto_increment,
  `product` varchar(255) NOT NULL default '',
  `price` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ID` (`id`)
)  ;


INSERT INTO `orbx_mod_orbitum_products` (`id`, `product`, `price`) VALUES
(2, 'Orbicon Lite', '900.00'),
(3, 'Orbicon Xtreme', '4000.00'),
(4, 'Orbicon Enterprise', '15000.00');
