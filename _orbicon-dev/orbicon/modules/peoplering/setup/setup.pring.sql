CREATE TABLE IF NOT EXISTS `pring_contact` (
  `id` int(11) NOT NULL auto_increment,
  `registered` int(11) NOT NULL default '0',
  `degree_id` int(11) NOT NULL default '0',
  `contact_name` varchar(255) NOT NULL default '',
  `contact_surname` varchar(255) NOT NULL default '',
  `contact_phone` varchar(30) NOT NULL default '',
  `contact_email` varchar(50) NOT NULL default '',
  `contact_address` varchar(255) NOT NULL default '',
  `contact_city` varchar(30) NOT NULL default '',
  `contact_zip` varchar(10) NOT NULL default '',
  `contact_url` varchar(255) default NULL,
  `contact_sex` tinyint(1) default '0',
  `contact_dob` int(11) NOT NULL default '0',
  `contact_subscription` tinyint(4) default '0',
  `contact_office` varchar(255) default NULL,
  `contact_position` varchar(255) default NULL,
  `contact_expertise` varchar(255) default NULL,
  `contact_fax` varchar(255) default NULL,
  `contact_gsm` varchar(255) default NULL,
  `picture` varchar(255) default NULL,
  `company_id` mediumint(9) NOT NULL default '0',
  `state_id` smallint(6) NOT NULL default '0',
  `private` int(1) NOT NULL default '0',
  `points` bigint(20) NOT NULL default '0',
  `estate_agency_status` int(1) NOT NULL default '0',
  `contact_phone_a` varchar(30) NOT NULL default '',
  `contact_phone_b` varchar(30) NOT NULL default '',
  `credits` decimal(20,2) NOT NULL,
  `contact_region` int(11) NOT NULL default '0',
  `contact_country` int(11) NOT NULL default '0',
  `contact_town_text` text NOT NULL,
  `mbg` varchar(255) default NULL,
  `estate_agency_level` int(1) NOT NULL default '0',
  `bank_status` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
);


CREATE TABLE IF NOT EXISTS `pring_counties` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `phone_code` tinyint(3) NOT NULL default '0',
  `lang` varchar(3) NOT NULL,
  `state` tinyint(1) NOT NULL default '0',
  `country` varchar(2) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `lang` (`lang`),
  KEY `country` (`country`)
) ;

INSERT INTO `pring_counties` VALUES (1, 'Cijela Hrvatska', 0, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (2, 'Grad Zagreb', 1, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (3, 'Dubrovačko-neretvanska', 20, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (4, 'Splitsko-dalmatinska', 21, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (5, 'Šibensko-kninska', 22, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (6, 'Zadarska', 23, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (7, 'Osječko-baranjska', 31, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (8, 'Vukovarsko-srijemska', 32, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (9, 'Virovitičko-podravska', 33, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (10, 'Požeško-slavonska', 34, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (11, 'Brodsko-posavska', 35, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (12, 'Međimurska', 40, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (13, 'Varaždinska', 42, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (14, 'Bjelovarsko-bilogorska', 43, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (15, 'Sisačko-moslavačka', 44, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (16, 'Karlovačka', 47, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (17, 'Koprivničko-križevačka', 48, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (18, 'Krapinsko-zagorska', 49, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (19, 'Primorsko-goranska', 51, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (20, 'Istarska', 52, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (21, 'Ličko-senjska', 53, 'hr', 0, 'HR');
INSERT INTO `pring_counties` VALUES (22, 'Zagrebačka', 1, 'hr', 0, 'HR');


INSERT INTO `pring_counties` VALUES (123, 'Grad Beograd', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (124, 'Regija Beograd', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (125, 'Grad Niš', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (126, 'Grad Novi Sad', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (127, 'Regija Bor', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (128, 'Regija Gnjilane', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (129, 'Regija Jagodina', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (130, 'Regija Kikinda', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (131, 'Regija Kosovska Mitrovica', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (132, 'Regija Kragujevac', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (133, 'Regija Kraljevo', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (134, 'Regija Kruševac', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (135, 'Regija Leskovac', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (136, 'Regija Niš', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (137, 'Regija Novi Pazar', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (138, 'Regija Novi Sad', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (139, 'Regija Pančevo', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (140, 'Regija Peć', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (141, 'Regija Pirot', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (142, 'Regija Požarevac', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (143, 'Regija Prijepolje', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (144, 'Regija Prizren', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (145, 'Regija Priština', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (146, 'Regija Prokupje', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (147, 'Regija Smederevo', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (148, 'Regija Sombor', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (149, 'Regija Sremska Mitrovica', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (150, 'Regija Subotica', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (151, 'Regija Uroševac', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (152, 'Regija Užice', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (153, 'Regija Valjevo', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (154, 'Regija Vranje', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (155, 'Regija Zaječar', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (156, 'Regija Zrenjanin', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (157, 'Regija Šabac', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (158, 'Regija Čačak', 0, 'sr', 0, 'RS');
INSERT INTO `pring_counties` VALUES (159, 'Regija Đakovica', 0, 'sr', 0, 'RS');

CREATE TABLE IF NOT EXISTS `pring_countries` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `domain_ext` varchar(3) NOT NULL,
  `lang` varchar(3) NOT NULL default 'hr',
  `state` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `domain_ext` (`domain_ext`),
  KEY `lang` (`lang`)
) ;

INSERT INTO `pring_countries` VALUES (1, 'ANDORRA', 'AD', 'hr', 0);
INSERT INTO `pring_countries` VALUES (2, 'UNITED ARAB EMIRATES', 'AE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (3, 'AFGHANISTAN', 'AF', 'hr', 0);
INSERT INTO `pring_countries` VALUES (4, 'ANTIGUA AND BARBUDA', 'AG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (5, 'ANGUILLA', 'AI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (6, 'ALBANIA', 'AL', 'hr', 0);
INSERT INTO `pring_countries` VALUES (7, 'ARMENIA', 'AM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (8, 'NETHERLANDS ANTILLES', 'AN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (9, 'ANGOLA', 'AO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (10, 'NON-SPEC ASIA PAS LOCATION', 'AP', 'hr', 0);
INSERT INTO `pring_countries` VALUES (11, 'ARGENTINA', 'AR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (12, 'AMERICAN SAMOA', 'AS', 'hr', 0);
INSERT INTO `pring_countries` VALUES (13, 'AUSTRIA', 'AT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (14, 'AUSTRALIA', 'AU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (15, 'ARUBA', 'AW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (16, 'AZERBAIJAN', 'AZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (17, 'BOSNIA AND HERZEGOWINA', 'BA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (18, 'BARBADOS', 'BB', 'hr', 0);
INSERT INTO `pring_countries` VALUES (19, 'BANGLADESH', 'BD', 'hr', 0);
INSERT INTO `pring_countries` VALUES (20, 'BELGIUM', 'BE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (21, 'BURKINA FASO', 'BF', 'hr', 0);
INSERT INTO `pring_countries` VALUES (22, 'BULGARIA', 'BG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (23, 'BAHRAIN', 'BH', 'hr', 0);
INSERT INTO `pring_countries` VALUES (24, 'BURUNDI', 'BI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (25, 'BENIN', 'BJ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (26, 'BERMUDA', 'BM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (27, 'BRUNEI DARUSSALAM', 'BN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (28, 'BOLIVIA', 'BO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (29, 'BRAZIL', 'BR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (30, 'BAHAMAS', 'BS', 'hr', 0);
INSERT INTO `pring_countries` VALUES (31, 'BHUTAN', 'BT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (32, 'BOTSWANA', 'BW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (33, 'BELARUS', 'BY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (34, 'BELIZE', 'BZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (35, 'CANADA', 'CA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (36, 'CONGO THE DEMOCRATIC REPUBLIC OF THE', 'CD', 'hr', 0);
INSERT INTO `pring_countries` VALUES (37, 'CENTRAL AFRICAN REPUBLIC', 'CF', 'hr', 0);
INSERT INTO `pring_countries` VALUES (38, 'SWITZERLAND', 'CH', 'hr', 0);
INSERT INTO `pring_countries` VALUES (39, 'COTE DIVOIRE', 'CI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (40, 'COOK ISLANDS', 'CK', 'hr', 0);
INSERT INTO `pring_countries` VALUES (41, 'CHILE', 'CL', 'hr', 0);
INSERT INTO `pring_countries` VALUES (42, 'CAMEROON', 'CM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (43, 'CHINA', 'CN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (44, 'COLOMBIA', 'CO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (45, 'COSTA RICA', 'CR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (46, 'CUBA', 'CU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (47, 'CYPRUS', 'CY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (48, 'CZECH REPUBLIC', 'CZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (49, 'GERMANY', 'DE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (50, 'DJIBOUTI', 'DJ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (51, 'DENMARK', 'DK', 'hr', 0);
INSERT INTO `pring_countries` VALUES (52, 'DOMINICAN REPUBLIC', 'DO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (53, 'ALGERIA', 'DZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (54, 'ECUADOR', 'EC', 'hr', 0);
INSERT INTO `pring_countries` VALUES (55, 'ESTONIA', 'EE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (56, 'EGYPT', 'EG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (57, 'ERITREA', 'ER', 'hr', 0);
INSERT INTO `pring_countries` VALUES (58, 'SPAIN', 'ES', 'hr', 0);
INSERT INTO `pring_countries` VALUES (59, 'ETHIOPIA', 'ET', 'hr', 0);
INSERT INTO `pring_countries` VALUES (60, 'EUROPEAN UNION', 'EU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (61, 'FINLAND', 'FI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (62, 'FIJI', 'FJ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (63, 'MICRONESIA FEDERATED STATES OF', 'FM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (64, 'FAROE ISLANDS', 'FO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (65, 'FRANCE', 'FR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (66, 'GABON', 'GA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (67, 'UNITED KINGDOM', 'GB', 'hr', 0);
INSERT INTO `pring_countries` VALUES (68, 'GRENADA', 'GD', 'hr', 0);
INSERT INTO `pring_countries` VALUES (69, 'GEORGIA', 'GE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (70, 'FRENCH GUIANA', 'GF', 'hr', 0);
INSERT INTO `pring_countries` VALUES (71, 'GHANA', 'GH', 'hr', 0);
INSERT INTO `pring_countries` VALUES (72, 'GIBRALTAR', 'GI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (73, 'GREENLAND', 'GL', 'hr', 0);
INSERT INTO `pring_countries` VALUES (74, 'GAMBIA', 'GM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (75, 'GREECE', 'GR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (76, 'GUATEMALA', 'GT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (77, 'GUAM', 'GU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (78, 'GUINEA-BISSAU', 'GW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (79, 'GUYANA', 'GY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (80, 'HONG KONG', 'HK', 'hr', 0);
INSERT INTO `pring_countries` VALUES (81, 'HONDURAS', 'HN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (82, 'CROATIA (local name: Hrvatska)', 'HR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (83, 'HAITI', 'HT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (84, 'HUNGARY', 'HU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (85, 'INDONESIA', 'ID', 'hr', 0);
INSERT INTO `pring_countries` VALUES (86, 'IRELAND', 'IE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (87, 'ISRAEL', 'IL', 'hr', 0);
INSERT INTO `pring_countries` VALUES (88, 'INDIA', 'IN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (89, 'BRITISH INDIAN OCEAN TERRITORY', 'IO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (90, 'IRAQ', 'IQ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (91, 'IRAN (ISLAMIC REPUBLIC OF)', 'IR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (92, 'ICELAND', 'IS', 'hr', 0);
INSERT INTO `pring_countries` VALUES (93, 'ITALY', 'IT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (94, 'JAMAICA', 'JM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (95, 'JORDAN', 'JO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (96, 'JAPAN', 'JP', 'hr', 0);
INSERT INTO `pring_countries` VALUES (97, 'KENYA', 'KE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (98, 'KYRGYZSTAN', 'KG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (99, 'CAMBODIA', 'KH', 'hr', 0);
INSERT INTO `pring_countries` VALUES (100, 'KIRIBATI', 'KI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (101, 'SAINT KITTS AND NEVIS', 'KN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (102, 'KOREA REPUBLIC OF', 'KR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (103, 'KUWAIT', 'KW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (104, 'CAYMAN ISLANDS', 'KY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (105, 'KAZAKHSTAN', 'KZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (106, 'LAO PEOPLES DEMOCRATIC REPUBLIC', 'LA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (107, 'LEBANON', 'LB', 'hr', 0);
INSERT INTO `pring_countries` VALUES (108, 'SAINT LUCIA', 'LC', 'hr', 0);
INSERT INTO `pring_countries` VALUES (109, 'LIECHTENSTEIN', 'LI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (110, 'SRI LANKA', 'LK', 'hr', 0);
INSERT INTO `pring_countries` VALUES (111, 'LIBERIA', 'LR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (112, 'LESOTHO', 'LS', 'hr', 0);
INSERT INTO `pring_countries` VALUES (113, 'LITHUANIA', 'LT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (114, 'LUXEMBOURG', 'LU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (115, 'LATVIA', 'LV', 'hr', 0);
INSERT INTO `pring_countries` VALUES (116, 'LIBYAN ARAB JAMAHIRIYA', 'LY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (117, 'MOROCCO', 'MA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (118, 'MONACO', 'MC', 'hr', 0);
INSERT INTO `pring_countries` VALUES (119, 'MOLDOVA REPUBLIC OF', 'MD', 'hr', 0);
INSERT INTO `pring_countries` VALUES (120, 'MADAGASCAR', 'MG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (121, 'MACEDONIA THE FORMER YUGOSLAV REPUBLIC OF', 'MK', 'hr', 0);
INSERT INTO `pring_countries` VALUES (122, 'MALI', 'ML', 'hr', 0);
INSERT INTO `pring_countries` VALUES (123, 'MYANMAR', 'MM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (124, 'MONGOLIA', 'MN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (125, 'MACAU', 'MO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (126, 'NORTHERN MARIANA ISLANDS', 'MP', 'hr', 0);
INSERT INTO `pring_countries` VALUES (127, 'MAURITANIA', 'MR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (128, 'MALTA', 'MT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (129, 'MAURITIUS', 'MU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (130, 'MALDIVES', 'MV', 'hr', 0);
INSERT INTO `pring_countries` VALUES (131, 'MALAWI', 'MW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (132, 'MEXICO', 'MX', 'hr', 0);
INSERT INTO `pring_countries` VALUES (133, 'MALAYSIA', 'MY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (134, 'MOZAMBIQUE', 'MZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (135, 'NAMIBIA', 'NA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (136, 'NEW CALEDONIA', 'NC', 'hr', 0);
INSERT INTO `pring_countries` VALUES (137, 'NIGER', 'NE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (138, 'NORFOLK ISLAND', 'NF', 'hr', 0);
INSERT INTO `pring_countries` VALUES (139, 'NIGERIA', 'NG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (140, 'NICARAGUA', 'NI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (141, 'NETHERLANDS', 'NL', 'hr', 0);
INSERT INTO `pring_countries` VALUES (142, 'NORWAY', 'NO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (143, 'NEPAL', 'NP', 'hr', 0);
INSERT INTO `pring_countries` VALUES (144, 'NAURU', 'NR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (145, 'NIUE', 'NU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (146, 'NEW ZEALAND', 'NZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (147, 'OMAN', 'OM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (148, 'PANAMA', 'PA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (149, 'PERU', 'PE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (150, 'FRENCH POLYNESIA', 'PF', 'hr', 0);
INSERT INTO `pring_countries` VALUES (151, 'PAPUA NEW GUINEA', 'PG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (152, 'PHILIPPINES', 'PH', 'hr', 0);
INSERT INTO `pring_countries` VALUES (153, 'PAKISTAN', 'PK', 'hr', 0);
INSERT INTO `pring_countries` VALUES (154, 'POLAND', 'PL', 'hr', 0);
INSERT INTO `pring_countries` VALUES (155, 'PUERTO RICO', 'PR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (156, 'PALESTINIAN TERRITORY OCCUPIED', 'PS', 'hr', 0);
INSERT INTO `pring_countries` VALUES (157, 'PORTUGAL', 'PT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (158, 'PALAU', 'PW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (159, 'PARAGUAY', 'PY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (160, 'QATAR', 'QA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (161, 'ROMANIA', 'RO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (162, 'RUSSIAN FEDERATION', 'RU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (163, 'RWANDA', 'RW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (164, 'SAUDI ARABIA', 'SA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (165, 'SOLOMON ISLANDS', 'SB', 'hr', 0);
INSERT INTO `pring_countries` VALUES (166, 'SEYCHELLES', 'SC', 'hr', 0);
INSERT INTO `pring_countries` VALUES (167, 'SUDAN', 'SD', 'hr', 0);
INSERT INTO `pring_countries` VALUES (168, 'SWEDEN', 'SE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (169, 'SINGAPORE', 'SG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (170, 'SLOVENIA', 'SI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (171, 'SLOVAKIA (Slovak Republic)', 'SK', 'hr', 0);
INSERT INTO `pring_countries` VALUES (172, 'SIERRA LEONE', 'SL', 'hr', 0);
INSERT INTO `pring_countries` VALUES (173, 'SAN MARINO', 'SM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (174, 'SENEGAL', 'SN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (175, 'SURINAME', 'SR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (176, 'EL SALVADOR', 'SV', 'hr', 0);
INSERT INTO `pring_countries` VALUES (177, 'SYRIAN ARAB REPUBLIC', 'SY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (178, 'SWAZILAND', 'SZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (179, 'TOGO', 'TG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (180, 'THAILAND', 'TH', 'hr', 0);
INSERT INTO `pring_countries` VALUES (181, 'TAJIKISTAN', 'TJ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (182, 'TURKMENISTAN', 'TM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (183, 'TUNISIA', 'TN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (184, 'TONGA', 'TO', 'hr', 0);
INSERT INTO `pring_countries` VALUES (185, 'TURKEY', 'TR', 'hr', 0);
INSERT INTO `pring_countries` VALUES (186, 'TRINIDAD AND TOBAGO', 'TT', 'hr', 0);
INSERT INTO `pring_countries` VALUES (187, 'TUVALU', 'TV', 'hr', 0);
INSERT INTO `pring_countries` VALUES (188, 'TAIWAN PROVINCE OF CHINA', 'TW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (189, 'TANZANIA UNITED REPUBLIC OF', 'TZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (190, 'UKRAINE', 'UA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (191, 'UGANDA', 'UG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (192, 'UNITED STATES', 'US', 'hr', 0);
INSERT INTO `pring_countries` VALUES (193, 'URUGUAY', 'UY', 'hr', 0);
INSERT INTO `pring_countries` VALUES (194, 'UZBEKISTAN', 'UZ', 'hr', 0);
INSERT INTO `pring_countries` VALUES (195, 'HOLY SEE (VATICAN CITY STATE)', 'VA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (196, 'VENEZUELA', 'VE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (197, 'VIRGIN ISLANDS (BRITISH)', 'VG', 'hr', 0);
INSERT INTO `pring_countries` VALUES (198, 'VIRGIN ISLANDS (U.S.)', 'VI', 'hr', 0);
INSERT INTO `pring_countries` VALUES (199, 'VIET NAM', 'VN', 'hr', 0);
INSERT INTO `pring_countries` VALUES (200, 'VANUATU', 'VU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (201, 'SAMOA', 'WS', 'hr', 0);
INSERT INTO `pring_countries` VALUES (202, 'YEMEN', 'YE', 'hr', 0);
INSERT INTO `pring_countries` VALUES (203, 'Serbia and Montenegro (Formally Yugoslavia)', 'YU', 'hr', 0);
INSERT INTO `pring_countries` VALUES (204, 'SOUTH AFRICA', 'ZA', 'hr', 0);
INSERT INTO `pring_countries` VALUES (205, 'ZAMBIA', 'ZM', 'hr', 0);
INSERT INTO `pring_countries` VALUES (206, 'ZIMBABWE', 'ZW', 'hr', 0);
INSERT INTO `pring_countries` VALUES (207, 'RESERVED', 'ZZ', 'hr', 0);

CREATE TABLE IF NOT EXISTS `pring_cvs` (
  `id` bigint(20) NOT NULL auto_increment,
  `cvcategory` varchar(20) default NULL,
  `contact_id` bigint(20) NOT NULL default '0',
  `cvname` varchar(255) default NULL,
  `county` varchar(255) default NULL,
  `placeofbirth` varchar(255) default NULL,
  `countryofbirth` tinytext,
  `country` tinytext,
  `contactfax` varchar(30) default NULL,
  `doe` text,
  `education` text,
  `pastjobs` text,
  `gotmanagerskills` tinyint(1) default NULL,
  `yoe` int(11) default NULL,
  `eng` tinyint(4) default NULL,
  `ger` tinyint(4) default NULL,
  `fre` tinyint(4) default NULL,
  `ita` tinyint(4) default NULL,
  `otheractive` text,
  `otherpassive` text,
  `dlic` int(11) default NULL,
  `dlicmore` text,
  `complementary` tinyint(1) default NULL,
  `capabilities` text,
  `achievements` text,
  `rest` text,
  `managerskills` text NOT NULL,
  PRIMARY KEY  (`id`)
) ;


CREATE TABLE IF NOT EXISTS `pring_doe` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `lang` varchar(3) NOT NULL,
  `state` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `lang` (`lang`)
) ;

INSERT INTO `pring_doe` VALUES (1, 'Nije bitno', 'hr', 0);
INSERT INTO `pring_doe` VALUES (2, 'NKV', 'hr', 0);
INSERT INTO `pring_doe` VALUES (3, 'PKV', 'hr', 0);
INSERT INTO `pring_doe` VALUES (4, 'KV', 'hr', 0);
INSERT INTO `pring_doe` VALUES (5, 'VKV', 'hr', 0);
INSERT INTO `pring_doe` VALUES (6, 'Srednja stručna sprema', 'hr', 0);
INSERT INTO `pring_doe` VALUES (7, 'Viša stručna sprema', 'hr', 0);
INSERT INTO `pring_doe` VALUES (8, 'Visoka stručna sprema', 'hr', 0);
INSERT INTO `pring_doe` VALUES (9, 'MBA', 'hr', 0);
INSERT INTO `pring_doe` VALUES (10, 'Magisterij', 'hr', 0);
INSERT INTO `pring_doe` VALUES (11, 'Doktorat', 'hr', 0);

INSERT INTO `pring_doe` VALUES (12, 'Irrelevant', 'en', 0);
INSERT INTO `pring_doe` VALUES (13, 'Under qualified', 'en', 0);
INSERT INTO `pring_doe` VALUES (14, 'Semi-qualified', 'en', 0);
INSERT INTO `pring_doe` VALUES (15, 'Qualified', 'en', 0);
INSERT INTO `pring_doe` VALUES (16, 'Highly qualified', 'en', 0);
INSERT INTO `pring_doe` VALUES (17, 'High school degree', 'en', 0);
INSERT INTO `pring_doe` VALUES (18, 'College degree', 'en', 0);
INSERT INTO `pring_doe` VALUES (19, 'University degree / Bachelor\'s degree (B.A. / B.Sc.)', 'en', 0);
INSERT INTO `pring_doe` VALUES (20, 'MBA', 'en', 0);
INSERT INTO `pring_doe` VALUES (21, 'Master\'s', 'en', 0);
INSERT INTO `pring_doe` VALUES (22, 'Ph.D.', 'en', 0);

CREATE TABLE IF NOT EXISTS `pring_industry` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `lang` varchar(3) NOT NULL,
  `state` tinyint(1) NOT NULL default '0',
  `intro_text` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `lang` (`lang`)
) ;

INSERT INTO `pring_industry` VALUES (1, 'Informatika i računarstvo', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (2, 'Strojarstvo i elektrotehnika', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (3, 'Trgovina, prodaja i marketing', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (4, 'Ekonomija', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (5, 'Administrativna zanimanja', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (6, 'Pravo', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (7, 'Dizajn i umjetnost', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (8, 'Obrazovanje i briga o djeci', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (9, 'Promet i transport', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (10, 'Građevina, opremanje i arhitektura', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (11, 'Zdravstvo i briga o ljepoti', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (12, 'Turizam, ugostiteljstvo, prehr.', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (13, 'Osobne usluge', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (14, 'Ostalo', 'hr', 0, '');

INSERT INTO `pring_industry` VALUES (15, 'ICT and Internet', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (16, 'Engineering, Electrotechnics', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (17, 'Sales and Marketing', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (18, 'Economy (general)', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (19, 'Administrative jobs', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (20, 'Law', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (21, 'Design and Arts', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (22, 'Education and Child care', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (23, 'Traffic and Transportation', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (24, 'Construction work and Architecture', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (25, 'Health and Beauty care', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (26, 'Tourism, Hospitality and Nutrition', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (27, 'Personal services', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (28, 'Other', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (29, 'Public services and non-profit organizations', 'en', 0, '');

INSERT INTO `pring_industry` VALUES (30, 'Javne usluge i neprofitne organizacije', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (31, 'Nekretnine', 'hr', 0, '');
INSERT INTO `pring_industry` VALUES (32, 'Investicije', 'hr', 0, '');

INSERT INTO `pring_industry` VALUES (33, 'Estates', 'en', 0, '');
INSERT INTO `pring_industry` VALUES (34, 'Investments', 'en', 0, '');


CREATE TABLE IF NOT EXISTS `pring_company` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `zip` varchar(100) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `phone` varchar(100) NOT NULL default '',
  `fax` varchar(100) NOT NULL default '',
  `mb` varchar(20) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `mail` varchar(100) NOT NULL default '',
  `contact` int(11) NOT NULL default '0',
  `lang` char(3) NOT NULL default '',
  `state` tinyint(1) NOT NULL default '0',
  `logo` text NOT NULL,
  `industry_a` text NOT NULL,
  `industry_b` text NOT NULL,
  `industry_c` text NOT NULL,
  `intro_text` text NOT NULL,
  `phone_a` varchar(30) NOT NULL default '',
  `phone_b` varchar(30) NOT NULL default '',
  `mb` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `contact` (`contact`)
) ;

CREATE TABLE IF NOT EXISTS `pring_mails` (
  `id` int(11) NOT NULL auto_increment,
  `mail_to` int(11) NOT NULL default '0',
  `mail_from` int(11) NOT NULL default '0',
  `attachment` varchar(250) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `body` longtext NOT NULL,
  `mailbox` varchar(255) NOT NULL default 'inbox',
  `mail_date` int(11) NOT NULL default '0',
  `mail_read` int(1) NOT NULL default '0',
  `sent_copy` int(1) NOT NULL default '0',
  `owner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `mailbox` (`mailbox`),
  KEY `mail_read` (`mail_read`),
  KEY `owner_id` (`owner_id`)
) ;

CREATE TABLE IF NOT EXISTS `pring_user_contacts` (
  `id` int(11) NOT NULL auto_increment,
  `contacts` longtext NOT NULL,
  `owner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `pring_promo` (
	`id` int( 11 ) NOT NULL AUTO_INCREMENT,
	`title` text NOT NULL ,
	`ad` text NOT NULL ,
	`textual` longtext NOT NULL ,
	`state` tinyint(1) NOT NULL default '0',
	`created` timestamp NOT NULL default NOW(),
	`contact_id` int(11) NOT NULL default '0',
	PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `pring_towns` (
  `id` int(11) NOT NULL auto_increment,
  `town` varchar(255) NOT NULL,
  `county` int(11) NOT NULL default '0',
  `lang` varchar(3) NOT NULL default 'hr',
  `country` varchar(2) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `town` (`town`),
  KEY `county` (`county`)
) ;

INSERT INTO `pring_towns` VALUES ('', 'Brezovica', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Črnomerec', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donji Grad', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dubrava Donja', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dubrava Gornja', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gornji Grad-Medvešćak', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Maksimir', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novi Zagreb-istok', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novi Zagreb-zapad', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pešćenica-Žitnjak', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podsljeme', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podsused-Vrapče', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sesvete', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stenjevec', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trešnjevka-jug', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trešnjevka-sjever', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trnje', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Centar', 2, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Bedenica', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bistra', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Brckovljani', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Brdovec', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dubrava', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dubravica', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dugo Selo', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Farkaševac', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gradec', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ivanić-Grad', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jakovlje', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jastrebarsko', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Klinča Sela', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kloštar Ivanić', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Krašić', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kravarsko', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Križ', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Luka', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Marija Gorica', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Orle', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pisarovina', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pokupsko', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Preseka', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pušća', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rakovec', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rugvica', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Samobor', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stupnik', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveta Nedjelja', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Ivan Zelina', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Velika Gorica', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrbovec', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zaprešić', 22, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Žumberak', 22, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Zagreb', 22, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Berek', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bjelovar', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čazma', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Daruvar', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dežanovac', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Đulovac', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Garešnica', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Grubišno Polje', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hercegovac', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ivanska', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kapela', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Končanica', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nova Rača', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rovišće', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Šandrovac', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Severin', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sirač', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Štefanje', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Velika Pisanica', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Velika Trnovitica', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Veliki Grđevac', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Veliko Trojstvo', 14, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zrinski Topolovac', 14, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Bebrina', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Brodski Stupnik', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bukovlje', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Cernik', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Davor', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donji Andrijevci', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dragalić', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Garčin', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gornja Vrba', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gornji Bogićevci', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gundinci', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Klakar', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nova Gradiška', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nova Kapela', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Okučani', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Oprisavci', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Oriovac', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podcrkavlje', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rešetari', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sibinj', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sikirevci', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Slavonski Brod', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Slavonski Šamac', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stara Gradiška', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Staro Petrovo Selo', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Velika Kopanica', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrbje', 11, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrpolje', 11, 'hr', 'HR');


INSERT INTO `pring_towns` VALUES ('', 'Blato', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dubrovačko primorje', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dubrovnik', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Janjina', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Konavle', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Korčula', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kula Norinska', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lastovo', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lumbarda', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Metković', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mljet', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Opuzen', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Orebić', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ploče', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pojezerje', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Slivno', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Smokvica', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ston', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trpanj', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vela Luka', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zažablje', 3, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Župa dubrovačka', 3, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Bale', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Barban', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Brtonigla', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Buje', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Buzet', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Cerovlje', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Fažana', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gračišće', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Grožnjan', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kanfanar', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Karojba', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kaštelir-Labinci', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kršan', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Labin', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lanišće', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ližnjan', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lupoglav', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Marčana', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Medulin', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Motovun', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novigrad', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Oprtalj', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pazin', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pićan', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Poreč', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pula', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Raša', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rovinj', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveta Nedelja', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Lovreč', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Petar u Šumi', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Svetvinčenat', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tinjan', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Umag', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Višnjan', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vižinada', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vodnjan', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrsar', 20, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Žminj', 20, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Barilovići', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bosiljevo', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Cetingrad', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Draganić', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Duga Resa', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Generalski Stol', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Josipdol', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Karlovac', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Krnjak', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lasinja', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Netretić', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ogulin', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ozalj', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Plaški', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rakovica', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ribnik', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Saborsko', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Slunj', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tounj', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vojnić', 16, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Žakanje', 16, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Đelekovec', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Đurđevac', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Drnje', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ferdinandovac', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gola', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gornja Rijeka', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hlebine', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kalinovac', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kalnik', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kloštar Podravski', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Koprivnica', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Koprivnički Bregi', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Koprivnički Ivanec', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Križevci', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Legrad', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Molve', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novigrad Podravski', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novo Virje', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Peteranec', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podravske Sesvete', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rasinja', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sokolovac', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Ivan Žabno', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Petar Orehovec', 17, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Virje', 17, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Bedekovčina', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Budinščina', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Desinić', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Đurmanec', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donja Stubica', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gornja Stubica', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hrašćina', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hum na Sutli', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jesenje', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Klanjec', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Konjščina', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kraljevec na Sutli', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Krapina', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Krapinske Toplice', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kumrovec', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lobor', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mače', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Marija Bistrica', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mihovljan', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novi Golubovec', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Oroslavje', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Petrovsko', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pregrada', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Radoboj', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stubičke Toplice', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Križ Začretje', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tuhelj', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Veliko Trgovišće', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zabok', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zagorska Sela', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zlatar', 18, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zlatar-Bistrica', 18, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Brinje', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donji Lapac', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gospić', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Karlobag', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lovinac', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novalja', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Otočac', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Perušić', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Plitvička Jezera', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Senj', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Udbina', 21, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrhovine', 21, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Belica', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čakovec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dekanovec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Domašinec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donja Dubrava', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donji Kraljevec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donji Vidovec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Goričan', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gornji Mihaljevec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kotoriba', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mala Subotica', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mursko Središće', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nedelišće', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Orehovica', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podturen', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Prelog', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Selnica', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Šenkovec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Strahoninec', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Štrigova', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveta Marija', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Juraj na Bregu', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Martin na Muri', 12, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vratišinec', 12, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Antunovac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Beli Manastir', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Belišće', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bilje', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bizovac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čeminac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čepin', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Darda', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Đakovo', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Đurđenovac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donja Motičina', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donji Miholjac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Draž', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Drenje', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Erdut', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ernestinovo', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Feričanci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gorjani', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jagodnjak', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kneževi Vinogradi', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Koška', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Levanjska Varoš', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Magadenovac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Marijanci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Našice', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Osijek', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Petlovac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Petrijevci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podgorač', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podravska Moslavina', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Popovac', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Punitovci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Satnica đakovačka', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Semeljci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Šodolovci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Strizivojna', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trnava', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Valpovo', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Viljevo', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Viškovci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vladislavci', 7, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vuka', 7, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Brestovac', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čaglin', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jakšić', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kaptol', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kutjevo', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lipik', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pakrac', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pleternica', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Požega', 10, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Velika', 10, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Bakar', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Baška', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Brod Moravice', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čabar', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čavle', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Cres', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Crikvenica', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Delnice', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dobrinj', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Fužine', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jelenje', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kastav', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Klana', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kostrena', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kraljevica', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Krk', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lokve', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lovran', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mali Lošinj', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Malinska-Dubašnica', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Matulji', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mošćenička Draga', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mrkopalj', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novi Vinodolski', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Omišalj', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Opatija', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Punat', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rab', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ravna Gora', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rijeka', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Skrad', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vinodolska općina', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Viškovo', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrbnik', 19, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrbovsko', 19, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Biskupija', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Civljane', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Drniš', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ervenik', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kijevo', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kistanje', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Knin', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Murter', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pirovac', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Primošten', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Promina', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rogoznica', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ružić', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Šibenik', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Skradin', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tisno', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Unešić', 5, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vodice', 5, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Donji Kukuruzari', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dvor', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Glina', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gvozd', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hrvatska Dubica', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hrvatska Kostajnica', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jasenovac', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kutina', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lekenik', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lipovljani', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Majur', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Martinska Ves', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novska', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Petrinja', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Popovača', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sisak', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sunja', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Topusko', 15, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Velika Ludina', 15, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Baška Voda', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bol', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Brela', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Cista Provo', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dicmo', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dugi Rat', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Dugopolje', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gradac', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hrvace', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Hvar', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Imotski', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jelsa', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kaštela', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Klis', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Komiža', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lećevica', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lokvičići', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lovreć', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Makarska', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Marina', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Milna', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Muć', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nerežišća', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Okrug', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Omiš', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Otok', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podbablje', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podgora', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Podstrana', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Postira', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Prgomet', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Primorski Dolac', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Proložac', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pučišća', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Runovići', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Seget', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Selca', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Šestanovac', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sinj', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Solin', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Šolta', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Split', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stari Grad', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sućuraj', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Supetar', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sutivan', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trilj', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trogir', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tučepi', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vis', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrgorac', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrlika', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zadvarje', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zagvozd', 4, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zmijavci', 4, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Bednja', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Beretinec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Breznica', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Breznički Hum', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Cestica', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donja Voća', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Donji Martijanec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gornji Kneginec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ivanec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jalžabet', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Klenovnik', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lepoglava', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ludbreg', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ljubešćica', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mali Bukovec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Maruševec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novi Marof', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Petrijanec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sračinec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti đurđ', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Ilija', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trnovec Bartolovečki', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Varaždin', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Varaždinske Toplice', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Veliki Bukovec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vidovec', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vinica', 13, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Visoko', 13, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Čačinci', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Čađavica', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Crnac', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gradina', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lukač', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Mikleuš', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nova Bukovica', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Orahovica', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pitomača', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Slatina', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sopje', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Špišić Bukovica', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Suhopolje', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Virovitica', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Voćin', 9, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zdenci', 9, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Andrijaševci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Babina Greda', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bogdanovci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Borovo', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bošnjaci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Cerna', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Drenovci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gradište', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gunja', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ilok', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ivankovo', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jarmina', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lovas', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Markušica', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Negoslavci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nijemci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nuštar', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Otok (Vinkovci)', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Privlaka', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stari Jankovci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stari Mikanovci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tompojevci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tordinci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tovarnik', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Trpinja', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vinkovci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vođinci', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrbanja', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vukovar', 8, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Županja', 8, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Benkovac', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Bibinje', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Biograd na Moru', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Galovac', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Gračac', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jasenice', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kali', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Kukljica', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Lišane Ostrovičke', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Nin', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Novigrad', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Obrovac', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pag', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pakoštane', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Pašman', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Polača', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Poličnik', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Posedarje', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Povljana', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Preko', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Privlaka', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Ražanac', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sali', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Škabrnje', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stankovci', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Starigrad', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sukošan', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Sveti Filip i Jakov', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Tkon', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vir', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zadar', 6, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Zemunik Donji', 6, 'hr', 'HR');

INSERT INTO `pring_towns` VALUES ('', 'Prečko', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Špansko', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Malešnica', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Stenjevec', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Srednjaci', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Jarun', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Knežija', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Rudeš', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Vrbani', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Žitnjak', 2, 'hr', 'HR');
INSERT INTO `pring_towns` VALUES ('', 'Voltino', 2, 'hr', 'HR');


INSERT INTO `pring_towns` VALUES ('', 'Autokomanda', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Banjica', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Banjički venac', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Banovo brdo', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Bele vode', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Beli dvor - RTV Pink', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Bežanija - blokovi', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Bežanija - stara', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Bežanijska kosa', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Bogoslovija', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Borča', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Braće Jerković', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Centar - Stari grad', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Cerak', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Cerak vinogradi', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Crveni krst', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Cvetkova pijaca', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Dedinje - 25. maj', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Denkova bašta', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Donji Dorćol', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Dušanovac', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Filmski grad', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Golf naselje', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Hotel Jugoslavija', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Jajinci', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Julino brdo', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Kalemegdan', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Kanarevo brdo', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Karaburma', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Klinički centar', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Kneževac', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Konjarnik', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Kotež', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Košutnjak', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Krnjača', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Kumodraž I', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Kumodraž II', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Labudovo brdo', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Ledine', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Lekino brdo', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Lion', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Luka Beograd - Viline vode', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Mali mokri lug', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Medaković 1', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Medaković 2', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Medaković 3', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Miljakovac', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Mirijevo 1', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Mirijevo 2', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - Arena', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - blokovi - Sava', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - Fontana', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - Geneks', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - opština', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - paviljoni', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - Sava centar', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'NBGD - Studentski grad', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Palata pravde', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Petlovo brdo', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Profesorska kolonija', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Rakovica', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Resnik', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Savski trg - Pristanište', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Senjak', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Slavija', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Staro Sajmište', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Tašmajdan', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Terazije', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Topčider', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Trošarina', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Učiteljsko naselje', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Veliki mokri lug', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Vidikovac', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Višnjička banja', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Voždovac', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Vračar - Hram', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Vračar - Kalenić pijaca', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Vukov spomenik', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zemun - centar', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zemun - gornji grad', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zemun - Kalvarija', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zemun - nova Galenika', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zemun - novi grad', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zemun - Save Kovačevića', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zemun polje - Altin', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zvezdara', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Zvezdara - Kluz', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Šumice', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Žarkovo', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Železnik', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Čukarička padina', 123, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Đeram pijaca', 123, 'sr', 'RS');


INSERT INTO `pring_towns` VALUES ('', 'Mladenovac', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Obrenovac', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Barajevo', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Barič', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Batajnica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Begaljica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Beli potok', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Boleč', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Boljevci', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Dobanovci', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Grocka', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Jakovo', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kaluđerica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kovilovo', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lazarevac', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Leštane', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lipovica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Mala Moštanica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ostružnica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ovča', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Padinska skela', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Pinosava', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ralja', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ripanj', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ritopek', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Rušanj', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Slanci', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sopot', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sremčica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Stepojevac', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Surčin', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ugrinovci', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Umka', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Velika Moštanica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Veliko selo', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vinča', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Višnjica', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vrčin', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Zaklopača', 124, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Zuce', 124, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', '9. maj - Novo Selo', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Apelovac', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Beograd Mala', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Beverli Hils', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Branko Bjegović', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Brzi Brod', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bubanj', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bulevar zona I', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bulevar zona II', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bulevar zona III', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Centar', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Crvena Zvezda', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Crveni Krst - Duvanska', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Crveni Pevac', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Delijski Vis', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Durlan', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Duvanište', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Elektronska', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Gabrovačka reka', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Jagodin mala', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kalač brdo', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kičevo', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Komren', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kovanluk', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ledena Stena', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Marger', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Mediana', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Medoševac', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Milka Protić - K.P. Dom', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Nikola Tesla - Broj 6', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Niška Banja', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Nova Železnička kolonija', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Palilula', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Pantelej', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Pasi Poljana', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ratko Jović', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Staro groblje', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Stevan Sinđelić - Tehnički fakulteti', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Stočni trg - Mašinska', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Suvi Do', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Trošarina', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vinik', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vrežina', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Šljaka', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ženeva - Devizno naselje', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ćele Kula', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čair', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čalije', 125, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čamurlija', 125, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Adamovićevo naselje', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Adice', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Avijatičarsko naselje', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Betanija', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Centar', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Detelinara', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Detelinara nova', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Futog', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Grbavica', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Industrijska zona jug', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Industrijska zona sever', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Jodna banja', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kamenjar', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Klisa - Slana bara', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Klisa - Vidovdansko naselje', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Liman I', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Liman II', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Liman III', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Liman IV', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lipov Gaj', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Novo Naselje - Bistrica', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Novo Naselje - Satelit', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Petrovaradin - Novi Majur', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Petrovaradin - Stari Majur', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Petrovaradin - Trandžament', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Podbara', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Radna zona - Sever', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Rotkvarija', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Rumenka', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sajam', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sajlovo', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Salajka', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sr. Kamenica - Bocke', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sr. Kamenica - Centar', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sr. Kamenica - Mišeluk', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sr. Kamenica - Paragovo', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sr. Kamenica - Popovica', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sr. Kamenica - Ribnjak', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sr. Kamenica - Tatarsko brdo', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Telep', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Veternik', 126, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Šangaj', 126, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Bor', 127, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' nas.Boljevac', 127, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' nas.Majdanpek', 127, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Gnjilane', 128, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kosovska Kamenica', 128, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vitina', 128, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Jagodina', 129, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Paraćin', 129, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ćuprija', 129, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Despotovac', 129, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Rekovac', 129, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Svilajnac', 129, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Kikinda', 130, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čoka', 130, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Novi Kneževac', 130, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Kosovska Mitrovica', 131, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Leposavić', 131, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Srbica', 131, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vučitrn', 131, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Kragujevac', 132, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Aranđelovac', 132, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lapovo', 132, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Batočina', 132, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Knić', 132, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Korman', 132, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Rača', 132, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Topola', 132, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Kraljevo', 133, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vrnjačka Banja', 133, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lađevci', 133, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ratina', 133, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Raška', 133, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Samaila', 133, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ušće', 133, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vitanovac', 133, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Kruševac', 134, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Aleksandrovac', 134, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Brus', 134, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ražanj', 134, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Trstenik', 134, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Varvarin', 134, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Veliki Šiljegovac', 134, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ćićevac', 134, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Regija Leskovac', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Leskovac', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bojnik', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Brestovac', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Crna Trava', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Grdelica', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lebane', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Medveđa', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vlasotince', 135, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vučje', 135, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Regija Niš', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Niš', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Aleksinac', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Bela Palanka', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Gadžin Han', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Soko Banja', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Svrljig', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Doljevac', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Jelašnica', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Kamenica', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Matejevac', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Merošina', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Popovac', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Tešica', 136, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', '  Toponica', 136, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Novi Pazar', 137, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sjenica', 137, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' nas.Tutin', 137, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Novi Sad', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bač', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bačka Palanka', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bački Petrovac', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Beočin', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bečej', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' gr.Bačko Gradište', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Irig', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Srbobran', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sremski Karlovci', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Temerin', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Titel', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vrbas', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Žabalj', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bački Jarak', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Begeč', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Beška', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Budisava', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bukovac', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Gložan', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kać', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kisač', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kovilj', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ledinci', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ledinci stari', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Rakovac', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sirig', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Stepanovićevo', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Zmajevo', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čelarevo', 138, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čenej', 138, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Pančevo', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Alibunar', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bela Crkva', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kačarevo', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kovačica', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kovin', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Opovo', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vršac', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ivanovo', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Jabuka', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Omoljica', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Plandište', 139, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Starčevo', 139, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Peć', 140, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Istok', 140, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Klina', 140, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Pirot', 141, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Babušnica', 141, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Dimitrovgrad', 141, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Požarevac', 142, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kostolac', 142, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kučevo', 142, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Petrovac', 142, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Veliko Gradište', 142, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Žabari', 142, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Žagubica', 142, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Prijepolje', 143, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Priboj', 143, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Nova Varoš', 143, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Prizren', 144, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Dragaš', 144, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Orahovac', 144, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Suva Reka', 144, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Priština', 145, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Gračanica', 145, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kosovo Polje', 145, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lipljan', 145, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Prokupje', 146, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Blace', 146, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kuršumlija', 146, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Tm. Mal. Plana', 146, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Žitorađa', 146, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Smederevo', 147, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Smederevska Palanka', 147, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Velika Plana', 147, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Sombor', 148, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Apatin', 148, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kula', 148, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Odžaci', 148, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Sremska Mitrovica', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Inđija', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ruma', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Stara Pazova', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Šid', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Krnješevci', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Nova Pazova', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Pećinci', 149, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vojka', 149, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Subotica', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ada', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bačka Topola', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kanjiža', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Palić', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Senta', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bajmok', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čantavir', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Feketić', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Horgoš', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Mali Iđoš', 150, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' St. Moravica', 150, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Uroševac', 151, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kačanik', 151, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Užice', 152, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Arilje', 152, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bajina Bašta', 152, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kosjerić', 152, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Požega', 152, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Rožanstvo', 152, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Zlatibor', 152, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Čajetina', 152, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Regija Valjevo', 153, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', 'Valjevo', 153, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Lajkovac', 153, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ljig', 153, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Mionica', 153, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Osečina', 153, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ub', 153, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Vranje', 154, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bosilegrad', 154, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bujanovac', 154, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Preševo', 154, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Surdulica', 154, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Trgovište', 154, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vladičin Han', 154, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Zaječar', 155, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Negotin', 155, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Kladovo', 155, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Knjaževac', 155, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Salaš', 155, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Zrenjanin', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Novi Bečej', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Nova Crnja', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Sečanj', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Žitište', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ečka', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Elemir', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Melenci', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Novo Miloševo', 156, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Srpska Crnja', 156, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Šabac', 157, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Krupanj', 157, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ljubovija', 157, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Loznica', 157, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Mali Zvornik', 157, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Bogatić', 157, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Koceljevo', 157, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Vladimirci', 157, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Čačak', 158, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Gorni Milanovac', 158, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Guča', 158, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Ivanjica', 158, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Mrčajevci', 158, 'sr', 'RS');

INSERT INTO `pring_towns` VALUES ('', 'Đakovica', 159, 'sr', 'RS');
INSERT INTO `pring_towns` VALUES ('', ' Dečani', 159, 'sr', 'RS');