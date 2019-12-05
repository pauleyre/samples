CREATE TABLE IF NOT EXISTS `orbx_mod_inpulls_groups` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `permalink` text NOT NULL,
  `owner_id` int(11) NOT NULL default '0',
  `live` int(1) NOT NULL default '1',
  `intro_gfx` text NOT NULL,
  `intro_txt` text NOT NULL,
  `live_from` int(11) NOT NULL default '0',
  `members_gfx` text NOT NULL,
  `activity` tinyint(3) NOT NULL default '0',
  `disable_new_users` int(1) NOT NULL default '0',
  `require_auth_new_users` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
);

CREATE TABLE IF NOT EXISTS `orbx_mod_inpulls_group_members` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `user_reg_id` int(11) NOT NULL default '0',
  `title` text NOT NULL,
  `status` int(1) NOT NULL default '1',
  `member_since` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
);