CREATE TABLE IF NOT EXISTS `orbx_mod_inpulls_horoscope` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `text` text NOT NULL,
  `last_update` int(11) NOT NULL default '0',
  `icon` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
);


INSERT INTO `orbx_mod_inpulls_horoscope` (`id`, `title`, `text`, `last_update`, `icon`) VALUES
('1', 'Ovan', '', '', 'Aries.gif'),
('2', 'Bik', '', '', 'Taurus.gif'),
('3', 'Blizanci', '', '', 'Gemini.gif'),
('4', 'Rak', '', '', 'Cancer.gif'),
('5', 'Lav', '', '', 'Leo.gif'),
('6', 'Djevica', '', '', 'Virgo.gif'),
('7', 'Vaga', '', '', 'Libra.gif'),
('8', 'Škorpion', '', '', 'Scorpio.gif'),
('9', 'Strijelac', '', '', 'Sagittarius.gif'),
('10', 'Jarac', '', '', 'Capricorn.gif'),
('11', 'Vodenjak', '', '', 'Aquarius.gif'),
('12', 'Ribe', '', '', 'Pisces.gif');