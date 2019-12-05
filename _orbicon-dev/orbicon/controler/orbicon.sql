CREATE TABLE IF NOT EXISTS `magister_answers` (
  `id` int(11) NOT NULL auto_increment,
  `question_permalink` text,
  `original_author` text,
  `original_author_permalink` text NOT NULL,
  `original_author_contact` text,
  `content` longtext,
  `uploader` text,
  `uploader_ip` text,
  `uploader_time` text,
  `live` tinyint(4) NOT NULL default '0',
  `live_time` text NOT NULL,
  `hidden` tinyint(4) NOT NULL default '0',
  `last_modified` text,
  `language` char(3) NOT NULL default 'hr',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `content` (`content`)
) ;

CREATE TABLE IF NOT EXISTS `magister_answers_bck` (
  `id` int(11) NOT NULL auto_increment,
  `question_permalink` text,
  `original_author` text,
  `original_author_permalink` text NOT NULL,
  `original_author_contact` text,
  `content` longtext,
  `uploader` text,
  `uploader_ip` text,
  `uploader_time` text,
  `live` tinyint(4) NOT NULL default '0',
  `live_time` text NOT NULL,
  `hidden` tinyint(4) NOT NULL default '0',
  `last_modified` text,
  `language` char(3) NOT NULL default 'en',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `content` (`content`)
) ;

CREATE TABLE IF NOT EXISTS `magister_articles` (
  `id` int(11) NOT NULL auto_increment,
  `category` text,
  `title` text,
  `content` text,
  `uploader` text,
  `uploader_ip` text,
  `uploader_time` text,
  `live` tinyint(4) NOT NULL default '0',
  `live_time` text NOT NULL,
  `permalink` text NOT NULL,
  `hidden` tinyint(4) NOT NULL default '0',
  `licence` varchar(200) NOT NULL default 'gnu',
  `last_modified` text,
  `language` char(3) NOT NULL default 'hr',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`)
) ;

CREATE TABLE IF NOT EXISTS `magister_articles_bck` (
  `id` int(11) NOT NULL auto_increment,
  `category` text,
  `title` text,
  `content` text,
  `uploader` text,
  `uploader_ip` text,
  `uploader_time` text,
  `live` tinyint(4) NOT NULL default '0',
  `live_time` text NOT NULL,
  `permalink` text NOT NULL,
  `hidden` tinyint(4) NOT NULL default '0',
  `licence` varchar(200) NOT NULL default 'gnu',
  `last_modified` text,
  `language` char(3) NOT NULL default 'en',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ID` (`id`),
  FULLTEXT KEY `title` (`title`)
) ;

CREATE TABLE IF NOT EXISTS `magister_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `permalink` text,
  `description` text,
  `language` char(3) NOT NULL default 'hr',
  PRIMARY KEY  (`id`),
  KEY `language` (`language`)
) ;

CREATE TABLE IF NOT EXISTS `magister_categories_bck` (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `permalink` text,
  `description` text,
  `language` char(3) NOT NULL default 'en',
  PRIMARY KEY  (`id`),
  KEY `language` (`language`)
) ;


CREATE TABLE IF NOT EXISTS `mercury_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `permalink` text,
  `description` text,
  `owner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `mercury_comments` (
  `id` int(11) NOT NULL auto_increment,
  `question_permalink` text,
  `content` mediumtext,
  `uploader` text,
  `uploader_ip` text,
  `uploader_time` text,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `content` (`content`)
) ;



CREATE TABLE IF NOT EXISTS `mercury_files` (
  `id` int(11) NOT NULL auto_increment,
  `category` text,
  `title` text,
  `content` text,
  `uploader` text,
  `uploader_ip` text,
  `uploader_time` text,
  `live` tinyint(4) NOT NULL default '1',
  `live_time` text NOT NULL,
  `permalink` text NOT NULL,
  `hidden` tinyint(4) NOT NULL default '0',
  `licence` varchar(200) NOT NULL default 'gnu',
  `last_modified` text,
  `size` bigint(20) NOT NULL default '0',
  `description` text,
  `custom_live_date` text,
  `search_index` longtext,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `search_index` (`search_index`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_column` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `content` text,
  `sort` int(11) default NULL,
  `keywords` text,
  `permalink` text,
  `parent` text,
  `redirect` text,
  `lastmod` varchar(255) default NULL,
  `menu_name` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default 'default',
  `box_style` text,
  `language` char(3) NOT NULL default 'hr',
  `box_zone` varchar(255) NOT NULL default '',
  `template` varchar(255) NOT NULL default 'column.html',
  `permalink_ascii` text,
  `parent_ascii` text,
  `group` text,
  `desc` text,
  PRIMARY KEY  (`id`),
  KEY `language` (`language`),
  KEY `menu_name` (`menu_name`),
  KEY `type` (`type`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_editors` (
  `id` int(11) NOT NULL auto_increment,
  `username` text,
  `pwd` text,
  `first_name` text,
  `last_name` text,
  `email` text,
  `occupation` text,
  `notes` text,
  `status` text,
  `mob` text,
  `tel` text,
  `first_login` int(1) NOT NULL default '0',
  `last_location` text NOT NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_reg_members` (
  `id` int(11) NOT NULL auto_increment,
  `username` text,
  `pwd` text,
  `pring_contact_id` int(11) NOT NULL default '0',
  `banned` tinyint(1) NOT NULL default '0',
  `email` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pring_contact_id` (`pring_contact_id`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_emails` (
  `id` int(11) NOT NULL auto_increment,
  `email` text,
  PRIMARY KEY  (`id`)
) ;


CREATE TABLE IF NOT EXISTS `orbicon_settings` (
  `id` int(11) NOT NULL auto_increment,
  `setting` varchar(255) default NULL,
  `value` text,
  `language` char(3) NOT NULL default 'hr',
  PRIMARY KEY  (`id`),
  KEY `setting` (`setting`),
  KEY `language` (`language`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_zone` (
  `id` int(11) NOT NULL auto_increment,
  `title` text,
  `permalink` text,
  `column_list` text,
  `locked` tinyint(1) NOT NULL default '0',
  `language` char(3) NOT NULL default 'hr',
  `under_ssl` tinyint(1) NOT NULL default '0',
  `column_list_ascii` text,
  PRIMARY KEY  (`id`),
  KEY `language` (`language`)
) ;

CREATE TABLE IF NOT EXISTS `venus_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `permalink` text,
  `description` text,
  `owner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `venus_images` (
  `id` int(11) NOT NULL auto_increment,
  `category` text,
  `title` text,
  `content` text,
  `uploader` int(11) default NULL,
  `uploader_ip` text,
  `uploader_time` text,
  `live` tinyint(4) NOT NULL default '1',
  `live_time` text NOT NULL,
  `permalink` varchar(255) NOT NULL default '',
  `hidden` tinyint(4) NOT NULL default '0',
  `licence` varchar(200) NOT NULL default 'gnu',
  `last_modified` text,
  `size` bigint(20) NOT NULL default '0',
  `views` bigint(20) NOT NULL default '0',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uploader` (`uploader`),
  KEY `live` (`live`),
  KEY `permalink` (`permalink`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_sys_iso_639_1_codes` (
  `id` int(11) NOT NULL auto_increment,
  `iso_code` char(2) default NULL,
  `en` text,
  `fr` text,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `orbicon_sys_iso_639_2_codes` (
  `id` int(11) NOT NULL auto_increment,
  `iso_code` char(3) default NULL,
  `en` text,
  `fr` text,
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `orbicon_privileges` (
  `id` int(11) NOT NULL auto_increment,
  `group_name` text ,
  `permalink` text NOT NULL,
  `tabs` bigint(20) default NULL,
  `content` bigint(20) default NULL,
  `db` bigint(20) default NULL,
  `dynamic` bigint(20) default NULL,
  `tools` bigint(20) default NULL,
  `crm` bigint(20) default NULL,
  `settings` bigint(20) default NULL,
  `system` bigint(20) default NULL,
  `modules` text,
  PRIMARY KEY  (`id`)
);


CREATE TABLE IF NOT EXISTS `orbx_desktop` (
  `id` int(11) NOT NULL auto_increment,
  `icon_id` varchar(255) default NULL,
  `x` varchar(255) default NULL,
  `y` varchar(255) default NULL,
  `owner_id` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`owner_id`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_desktop_rss` (
  `id` int(11) NOT NULL auto_increment,
  `rss_url` text,
  `owner_id` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`owner_id`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_desktop_wallpaper` (
  `id` int(11) NOT NULL auto_increment,
  `image` text,
  `owner_id` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`owner_id`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_mime_types` (
  `id` int(11) NOT NULL auto_increment,
  `ext` varchar(255) default NULL,
  `mime` text,
  PRIMARY KEY  (`id`),
  KEY `ext` (`ext`)
) ;

CREATE TABLE IF NOT EXISTS `orbx_error_sql` (
  `id` int(11) NOT NULL auto_increment,
  `query` text,
  `error` text,
  `errno` int(11) NOT NULL default '0',
  `time` text,
  PRIMARY KEY  (`id`)
) ;


CREATE TABLE IF NOT EXISTS `orbx_html_cache` (
  `id` bigint(200) NOT NULL auto_increment,
  `hash` varchar(255) default NULL,
  `html` longblob,
  `header` text,
  `time` int(11) default NULL,
  `rev` bigint(200) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `hash` (`hash`),
  KEY `time` (`time`)
) ;

INSERT INTO `orbicon_privileges` (`group_name`, `permalink`, `tabs`, `content`, `db`, `dynamic`, `tools`, `crm`, `settings`, `system`, `modules`) VALUES
('ex. User',				'1', 	0, 0, 0, 0, 0, 0, 0, 0, ''),
('User', 					'2', 	2, 0, 7, 0, 0, 0, 0, 0, ''),
('Administrator', 			'3', 	63, 127, 7, 3, 127, 15, 7, 0, 'address-book|banners|cphp|css|forms|html|infocentar|javascript|login-history|news|newsboard|news-category|newsletter|peoplering|polls|robots-txt|rss|servers|sql_db|stats|tg-editor|news-alerts|estate|column-inspector'),
('System administrator', 	'4', 	127, 127, 7, 3, 127, 15, 15, 127, '');

INSERT INTO `orbicon_settings` (`setting`, `value`) VALUES ('main_site_title', ''),
('main_site_owner', ''),
('main_site_email', ''),
('main_site_desc', ''),
('main_site_keywords', ''),
('news_grid_rows', '1'),
('news_grid_columns', '1'),
('news_category_grid_rows', '1'),
('news_category_grid_columns', '1'),
('max_rss_items', '5'),
('rss_feeds', ''),
('rss_type', 'rss2'),
('main_site_permalinks', '0'),
('installed_languages', 'en'),
('main_site_def_lng', 'en'),
('cache_engine', ''),
('enable_cache_engine', '0'),
('memcached_servers', ''),
('news_img_default_xy', '0'),
('flv_player_def_w', '0'),
('flv_player_def_h', '0'),
('video_gallery_show_date', '1'),
('smtp_server', ''),
('smtp_port', '25'),
('language_subdomains', '0'),
('v_menu_def_display', '1'),
('news_archive_summary_items', '1'),
('main_site_metatags', ''),
('custom_php_code', ''),
('site_restricted_access', ''),
('max_poll_options', '10'),
('flv_player_autoplay', '0'),
('text_zoom', '0'),
('poll_votes_display', 'num'),
('syncm_type', ''),
('syncm_server', ''),
('news_properties', '15'),
('float_horiz_menu', '0'),
('v_menu_def_display_third', '0'),
('homepage_redirect', ''),
('date_format', 'm.d.Y'),
('restricted_range_from', '*'),
('restricted_range_to', '*'),
('poll_after_vote', 'results'),
('tg_whitelist', ''),
('tg_blacklist', ''),
('tg_rules', '10:10,60:30,300:50,3600:200'),
('show_last_news_from', ''),
('use_captcha', '1'),
('minify_html', '1'),
('stats_sess', '1'),
('stats_ip', '1'),
('stats_content', '1'),
('stats_refer', '1'),
('stats_country', '1'),
('stats_keyword', '1'),
('stats_hourly', '1'),
('override_module', ''),
('stats_attila', '1'),
('inword_search', '1'),
('form_feedback_position', 'inside'),
('log_slow_sql', '0'),
('us_ascii_uris', '0'),
('antispam_check', '0'),
('use_cache', '1'),
('searcheng_filter', '1'),
('sync_dirs', '')
;