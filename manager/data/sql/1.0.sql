
CREATE TABLE IF NOT EXISTS `absence` (
  `id` bigint(20) NOT NULL auto_increment,
  `from` int(11) default NULL,
  `to` int(11) default NULL,
  `employee_id` bigint(20) default NULL,
  `reason` int(11) NOT NULL default '0',
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `client` (
  `id` bigint(20) NOT NULL auto_increment,
  `company_name` varchar(255) default NULL,
  `mb` int(11) NOT NULL default '0',
  `contact_person` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `zip` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `phone` varchar(255) default NULL,
  `fax` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `added_by` int(11) NOT NULL default '0',
  `last_edited_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(255) default NULL,
  `first_name` varchar(255) default NULL,
  `last_name` varchar(255) default NULL,
  `email` text,
  `occupation` varchar(255) default NULL,
  `comment` text,
  `pay` varchar(255) default NULL,
  `work_start` int(11) default NULL,
  `work_end` int(11) default NULL,
  `flags` bigint(20) NOT NULL default '0',
  `sector` int(11) NOT NULL default '0',
  `phone` varchar(255) default NULL,
  `fax` varchar(255) default NULL,
  `mobile` varchar(255) default NULL,
  `added_by` int(11) NOT NULL default '0',
  `last_edited_by` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `sector` (`sector`)
)  ;



CREATE TABLE IF NOT EXISTS `loko` (
  `id` int(11) NOT NULL auto_increment,
  `loko_date` int(11) default NULL,
  `loko_destination` text,
  `loko_purpose` text,
  `loko_vehicle` int(11) default NULL,
  `loko_kmh` varchar(255) default NULL,
  `employee_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `suggest` (
  `id` int(11) NOT NULL auto_increment,
  `input` varchar(255) default NULL,
  `suggest_words` text,
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `vehicle` (
  `id` int(11) NOT NULL auto_increment,
  `vehicle` text,
  PRIMARY KEY  (`id`)
)  ;

CREATE TABLE IF NOT EXISTS `sectors` (
  `id` int(11) NOT NULL auto_increment,
  `sector` text,
  PRIMARY KEY  (`id`)
)  ;

CREATE TABLE IF NOT EXISTS `work_order` (
  `id` bigint(20) NOT NULL auto_increment,
  `wo_log_id` varchar(255) default NULL,
  `client_id` bigint(20) default NULL,
  `project_name` text,
  `order_type` tinyint(4) default NULL,
  `target_date` int(11) default NULL,
  `description` longtext,
  `project_manager` bigint(20) default NULL,
  `status` int(11) default NULL,
  `version` int(11) default NULL,
  `type` int(11) default NULL,
  `added_by` int(11) NOT NULL default '0',
  `added_date` int(11) NOT NULL default '0',
  `last_edited_by` int(11) NOT NULL default '0',
  `last_edited_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `work_order_old` (
  `id` int(11) NOT NULL auto_increment,
  `wo_log_id` varchar(255) default NULL,
  `client_id` bigint(20) default NULL,
  `project_name` text,
  `order_type` tinyint(4) default NULL,
  `target_date` int(11) default NULL,
  `description` longblob,
  `project_manager` bigint(20) default NULL,
  `status` int(11) default NULL,
  `version` int(11) default NULL,
  `type` int(11) default NULL,
  `added_by` int(11) NOT NULL default '0',
  `added_date` int(11) NOT NULL default '0',
  `last_edited_by` int(11) NOT NULL default '0',
  `last_edited_date` int(11) NOT NULL default '0',
  `wo_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `work_order_todo` (
  `id` bigint(20) NOT NULL auto_increment,
  `wo_id` bigint(20) default NULL,
  `employee_id` bigint(20) default NULL,
  `description` longtext,
  `started` int(11) default NULL,
  `finished` int(11) default NULL,
  `total_hours` int(11) default NULL,
  `status` int(11) default NULL,
  `comment` longtext,
  `target_date_start` int(11) default NULL,
  `target_date_end` int(11) default NULL,
  PRIMARY KEY  (`id`)
)  ;



CREATE TABLE IF NOT EXISTS `work_order_todo_old` (
  `id` bigint(20) NOT NULL auto_increment,
  `wo_id` bigint(20) default NULL,
  `employee_id` bigint(20) default NULL,
  `description` longblob,
  `started` int(11) default NULL,
  `finished` int(11) default NULL,
  `total_hours` int(11) default NULL,
  `status` int(11) default NULL,
  `comment` longblob,
  `target_date_start` int(11) default NULL,
  `target_date_end` int(11) default NULL,
  `version` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

CREATE TABLE IF NOT EXISTS `sql_error_log` (
  `id` int(11) NOT NULL auto_increment,
  `query` text,
  `error` text,
  `errno` int(11) NOT NULL default '0',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `wo_docs` (
  `id` int(11) NOT NULL auto_increment,
  `content` longtext,
  `flags` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL auto_increment,
  `content` longtext,
  `flags` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;

INSERT INTO `employee` ( `password` , `first_name` , `flags` ) VALUES ('*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', 'Administrator', 7);
INSERT INTO `client` (`id`, `company_name`) VALUES (1, 'Moja tvrtka / obrt');
INSERT INTO `config` (`id`, `flags`, `content`) VALUES (1, 1, 'manager,Moja tvrtka / obrt');
