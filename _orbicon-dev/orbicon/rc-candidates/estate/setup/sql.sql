CREATE TABLE `orbx_mod_estate` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `msquare` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `tag_title` text NOT NULL,
  `img_alt` text NOT NULL,
  `img_title` text NOT NULL,
  `img_css_id` text NOT NULL,
  `img_naziv` text NOT NULL,
  `img_naziv_big` text NOT NULL,
  `other_img` text NOT NULL,
  `other_img_big` text NOT NULL,
  `gallery` text NOT NULL,
  PRIMARY KEY  (`id`)
) ;