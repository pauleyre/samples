CREATE TABLE IF NOT EXISTS `orbx_mod_orbitum_pickup_depot`  (
	`id` int(11) NOT NULL auto_increment,
	`user_id` varchar(255) NOT NULL,
	`products` text,
	`order_id` int(11),
	`purchased` int(11),
	`valid_until` int(11),
	`license`  blob,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `ID` (`id`)
);

CREATE TABLE IF NOT EXISTS `orbx_mod_orbitum_products`  (
	`id` int(11) NOT NULL auto_increment,
	`product` varchar(255) NOT NULL,
	`package` varchar(255) NOT NULL,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `ID` (`id`)
);