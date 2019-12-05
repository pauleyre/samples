--
-- Table structure for table `orbx_mod_invest_currency`
--

CREATE TABLE `orbx_mod_invest_currency` (
  `id` tinyint(4) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `state` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `orbx_mod_invest_fond`
--

CREATE TABLE `orbx_mod_invest_fond` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `currency` char(3) NOT NULL default '',
  `min_entry` decimal(12,2) NOT NULL default '0.00',
  `entry_fee` decimal(6,2) NOT NULL default '0.00',
  `state` tinyint(1) NOT NULL default '0',
  `frontpage` tinyint(1) NOT NULL default '0',
  `sortnum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `orbx_mod_invest_stock`
--

CREATE TABLE `orbx_mod_invest_stock` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` timestamp NOT NULL,
  `stock_value` decimal(9,4) NOT NULL default '0.0000',
  `fond` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);