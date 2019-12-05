<?php
/**
 * Advanced settings display
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package SystemFE
 * @subpackage Settings
 * @version 1.70
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2006-07-01
 */
	$adv = new Settings();
	$adv->save_site_adv_settings();
	$adv->build_site_settings();

	list($restricted_range_f, $restricted_range_f_mask) = explode('/', $_POST['restricted_range_from']);
	$restricted_range_f = ($restricted_range_f == '*') ? '*.*.*.*'  : $restricted_range_f;
	$restricted_range_f = explode('.', $restricted_range_f);

	list($restricted_range_t, $restricted_range_t_mask) = explode('/', $_POST['restricted_range_to']);
	$restricted_range_t = ($restricted_range_t == '*') ? '*.*.*.*'  : $restricted_range_t;
	$restricted_range_t = explode('.', $restricted_range_t);

	unset($adv);

?>
<form method="post" action="" id="advanced_settings">

	<div class="control_btn">
		<p>
			<button name="save_settings" type="submit"><?php echo _L('save'); ?></button>
			<input type="reset" value="<?php echo _L('reset'); ?>" />
		</p>
	</div>

	<fieldset id="general_advanced"><legend><?php echo _L('advanced_general_settings'); ?></legend>
		<div class="column">
			<p>
				<input name="maintenance_mode" id="maintenance_mode" type="checkbox" value="1" <?php if(ORBX_MAINTENANCE_MODE == 1) { echo 'checked="checked"'; }; ?> />
				<label for="maintenance_mode"><?php echo _L('maintenance_mode'); ?></label>
			</p>

			<p>
				<input name="ssl_orbx" id="ssl_orbx" type="checkbox" value="1" <?php if($_POST['ssl_orbx']) echo 'checked="checked"'; ?> />
				<label for="ssl_orbx"><?php echo _L('ssl_orbx'); ?></label>
			</p>

			<p>
				<label for="smtp_server"><?php echo _L('smtp_server'); ?></label><br />
				<input name="smtp_server" id="smtp_server" type="text" value="<?php echo $_POST['smtp_server']; ?>" class="text_field" />
			</p>
			<p>
				<label for="smtp_port"><?php echo _L('smtp_port'); ?></label><br />
				<input name="smtp_port" id="smtp_port" type="text" maxlength="5" value="<?php echo intval($_POST['smtp_port']); ?>" class="text_field" />
			</p>

			<p>
				<label for="v_menu_def_display"><?php echo _L('v_menu_def_display'); ?></label><br />
				<select id="v_menu_def_display" name="v_menu_def_display">
					<option value="0" <?php if(!$_POST['v_menu_def_display']) echo 'selected="selected"'; ?>><?php echo _L('hide'); ?></option>
					<option value="1" <?php if($_POST['v_menu_def_display']) echo 'selected="selected"'; ?>><?php echo _L('show'); ?></option>
				</select>
			</p>

			<p>
				<label for="v_menu_def_display_third"><?php echo _L('v_menu_def_display_third'); ?></label><br />
				<select id="v_menu_def_display_third" name="v_menu_def_display_third">
					<option value="0" <?php if(!$_POST['v_menu_def_display_third']) echo 'selected="selected"'; ?>><?php echo _L('hide'); ?></option>
					<option value="1" <?php if($_POST['v_menu_def_display_third']) echo 'selected="selected"'; ?>><?php echo _L('show'); ?></option>
				</select>
			</p>

			<p>
				<label for="restricted_range_f0"><?php echo _L('restricted_range_from'); ?></label><br />
				<input name="restricted_range_f0" size="3" id="restricted_range_f0" type="text" maxlength="3" value="<?php echo $restricted_range_f[0]; ?>" />.
				<input name="restricted_range_f1" size="3" id="restricted_range_f1" type="text" maxlength="3" value="<?php echo $restricted_range_f[1]; ?>" />.
				<input name="restricted_range_f2" size="3" id="restricted_range_f2" type="text" maxlength="3" value="<?php echo $restricted_range_f[2]; ?>" />.
				<input name="restricted_range_f3" size="3" id="restricted_range_f3" type="text" maxlength="3" value="<?php echo $restricted_range_f[3]; ?>" /> /
				<input name="restricted_range_f_mask" size="3" id="restricted_range_f_mask" type="text" maxlength="3" value="<?php echo $restricted_range_f_mask; ?>" />
			</p>

			<p>
				<label for="restricted_range_t0"><?php echo _L('restricted_range_to'); ?></label><br />
				<input name="restricted_range_t0" size="3" id="restricted_range_t0" type="text" maxlength="3" value="<?php echo $restricted_range_t[0]; ?>" />.
				<input name="restricted_range_t1" size="3" id="restricted_range_t1" type="text" maxlength="3" value="<?php echo $restricted_range_t[1]; ?>" />.
				<input name="restricted_range_t2" size="3" id="restricted_range_t2" type="text" maxlength="3" value="<?php echo $restricted_range_t[2]; ?>" />.
				<input name="restricted_range_t3" size="3" id="restricted_range_t3" type="text" maxlength="3" value="<?php echo $restricted_range_t[3]; ?>" /> /
				<input name="restricted_range_t_mask" size="3" id="restricted_range_t_mask" type="text" maxlength="3" value="<?php echo $restricted_range_t_mask; ?>" />
			</p>

			<p>
				<input name="inword_search" id="inword_search" type="checkbox" value="1" <?php if($_POST['inword_search']) echo 'checked="checked"'; ?> />
				<label for="inword_search"><?php echo _L('inword_search'); ?></label>
			</p>

		</div>

		<div class="column">
			<p>
				<input name="site_restricted_access" id="site_restricted_access" type="checkbox" value="1" <?php if($_POST['site_restricted_access']) echo 'checked="checked"'; ?> />
				<label for="site_restricted_access"><?php echo _L('site_restricted_access'); ?></label>
			</p>

			<p>
				<label for="homepage_redirect"><?php echo _L('homepage_redirect'); ?></label><br />


				<div style="width:100%; overflow:auto; height: 38px;">
			    	<select id="homepage_redirect" name="homepage_redirect">
			    		<option value="">&mdash;</option>
			    	<?php
			    		$a_ = build_zones(array(), false, $_POST['homepage_redirect']);
			    		echo $a_[0];
			    		unset($a_);
			    	?>
			    	</select>
			    </div>
			</p>

			<?php

				/**
				 * @todo these are some polls specific options. move them to polls backend
				 *
				 */
				if($orbx_mod->validate_module('polls')) {

			?>
			<p>
				<label for="max_poll_options"><?php echo _L('max_poll_options'); ?></label><br />
				<input name="max_poll_options" id="max_poll_options" type="text" value="<?php echo intval($_POST['max_poll_options']); ?>" class="text_field" maxlength="5" />
			</p>
			<p>
				<label for="poll_votes_display"><?php echo _L('poll_votes_display'); ?></label><br />
				<select id="poll_votes_display" name="poll_votes_display" class="text_field">
					<optgroup label="<?php echo _L('pick_votes_display_type'); ?>">
						<option value="percent" <?php if($_POST['poll_votes_display'] == 'percent') echo 'selected="selected"'; ?>><?php echo _L('percent'); ?></option>
						<option value="num" <?php if($_POST['poll_votes_display'] == 'num') echo 'selected="selected"'; ?>><?php echo _L('number'); ?></option>
					</optgroup>
				</select>
			</p>
			<p>
				<label for="poll_after_vote"><?php echo _L('poll_after_vote'); ?></label><br />
				<select id="poll_after_vote" name="poll_after_vote" class="text_field">
					<optgroup label="<?php echo _L('pick_poll_after_vote'); ?>">
						<option value="results" <?php if($_POST['poll_after_vote'] == 'results') echo 'selected="selected"'; ?>><?php echo _L('results'); ?></option>
						<option value="options" <?php if($_POST['poll_after_vote'] == 'options') echo 'selected="selected"'; ?>><?php echo _L('poll_choices'); ?></option>
					</optgroup>
				</select>
			</p>

			<?php
				}
			?>

		</div>

		<div class="column">
			<p>
				<input value="1" name="language_subdomains" id="language_subdomains" type="checkbox" <?php if($_POST['language_subdomains']) echo 'checked="checked"'; ?> />
				<label for="language_subdomains"><?php echo _L('language_subdomains'); ?></label>
			</p>

			<p>
				<label for="ftp_type"><?php echo _L('ftp_type'); ?></label><br />
				<select id="ftp_type" name="ftp_type">
					<optgroup label="<?php echo _L('pick_ftp_type'); ?>">
						<option value="ftp" <?php if(FTP_TYPE == 'ftp') echo 'selected="selected"'; ?>>FTP</option>
						<!-- <option value="ssh2" <?php if(FTP_TYPE == 'ssh2') echo 'selected="selected"'; ?>>SSH2 (SFTP)</option> -->
					</optgroup>
				</select>
			</p>

			<p>
				<input value="1" name="minify_html" id="minify_html" type="checkbox" <?php if($_POST['minify_html']) echo 'checked="checked"'; ?> />
				<label for="minify_html"><?php echo _L('minify_html'); ?></label>
			</p>

			<p>
				<input value="1" name="log_slow_sql" id="log_slow_sql" type="checkbox" <?php if($_POST['log_slow_sql']) echo 'checked="checked"'; ?> />
				<label for="log_slow_sql"><?php echo _L('log_slow_sql'); ?></label>
			</p>

			<p>
				<input value="1" name="us_ascii_uris" id="us_ascii_uris" type="checkbox" <?php if($_POST['us_ascii_uris']) echo 'checked="checked"'; ?> />
				<label for="us_ascii_uris"><?php echo _L('us_ascii_uris'); ?></label>
			</p>

			<p>
				<input value="1" name="antispam_check" id="antispam_check" type="checkbox" <?php if($_POST['antispam_check']) echo 'checked="checked"'; ?> />
				<label for="antispam_check"><?php echo _L('antispam_check'); ?></label>
			</p>

			<p>
				<input value="1" name="use_cache" id="use_cache" type="checkbox" <?php if($_POST['use_cache']) echo 'checked="checked"'; ?> />
				<label for="use_cache"><?php echo _L('use_cache'); ?></label>
			</p>

			<p>
				<input value="1" name="searcheng_filter" id="searcheng_filter" type="checkbox" <?php if($_POST['searcheng_filter']) echo 'checked="checked"'; ?> />
				<label for="searcheng_filter"><?php echo _L('searcheng_filter'); ?></label>
			</p>

			<p>
				<input value="1" name="flood_guard" id="flood_guard" type="checkbox" <?php if($_POST['flood_guard']) echo 'checked="checked"'; ?> />
				<label for="flood_guard"><?php echo _L('flood_guard'); ?></label>
			</p>

			<p>
				<label for="sync_dirs"><?php echo _L('sync_dirs'); ?></label>
				<textarea cols="50" rows="10" name="sync_dirs" id="sync_dirs"><?php echo $_POST['sync_dirs']; ?></textarea>
			</p>


		</div>
	</fieldset>

	<fieldset id="media_advanced"><legend><?php echo _L('advanced_media_settings'); ?></legend>
		<div class="column">
			<p>
				<input name="flv_player_autoplay" id="flv_player_autoplay" type="checkbox" value="1" <?php if($_POST['flv_player_autoplay']) echo 'checked="checked"'; ?> />
				<label for="flv_player_autoplay"><?php echo _L('flv_player_autoplay'); ?></label>
			</p>
			<p>
				<label for="flv_player_def_w"><?php echo _L('flv_player_def_w'); ?></label><br />
				<input name="flv_player_def_w" id="flv_player_def_w" type="text" value="<?php echo intval($_POST['flv_player_def_w']); ?>" class="text_field" maxlength="5" />
			</p>
			<p>
				<label for="flv_player_def_h"><?php echo _L('flv_player_def_h'); ?></label><br />
				<input name="flv_player_def_h" id="flv_player_def_h" type="text" value="<?php echo intval($_POST['flv_player_def_h']); ?>" class="text_field" maxlength="5" />
			</p>
			<p>
				<input name="video_gallery_show_date" id="video_gallery_show_date" type="checkbox" value="1" <?php if($_POST['video_gallery_show_date']) echo 'checked="checked"'; ?> />
				<label for="video_gallery_show_date"><?php echo _L('video_gallery_show_date'); ?></label>
				</p>

			<p>
				<input value="1" name="main_site_permalinks" id="main_site_permalinks" type="checkbox" <?php if($_POST['main_site_permalinks']) echo 'checked="checked"'; ?> />
				<label for="main_site_permalinks"><?php echo _L('use_permalinks'); ?></label>
			</p>
			<p>
				<label for="rss_type"><?php echo _L('rss_format'); ?></label><br />
				<select id="rss_type" name="rss_type">
					<optgroup label="<?php echo _L('pick_rss_format'); ?>">
						<option value="rss2" <?php if($_POST['rss_type'] == 'rss2') echo 'selected="selected"'; ?>>RSS 2.0</option>
						<option value="rdf" <?php if($_POST['rss_type'] == 'rdf') echo 'selected="selected"'; ?>>RDF</option>
					</optgroup>
				</select>
			</p>
			<p>
				<label for="max_rss_items"><?php echo _L('max_rss_items'); ?></label><br />
				<input name="max_rss_items" id="max_rss_items" type="text" value="<?php echo intval($_POST['max_rss_items']); ?>" class="text_field" maxlength="2" />
			</p>

		</div>

		<div class="column">
			<p>
				<input name="text_zoom" id="text_zoom" type="checkbox" value="1" <?php if($_POST['text_zoom']) echo 'checked="checked"'; ?> />
				<label for="text_zoom"><?php echo _L('text_zoom'); ?></label>
			</p>
			<p>
				<label for="news_archive_summary_items"><?php echo _L('news_archive_summary_items'); ?></label><br />
				<input name="news_archive_summary_items" id="news_archive_summary_items" type="text" value="<?php echo intval($_POST['news_archive_summary_items']); ?>" class="text_field" maxlength="2" />
			</p>
			<p>
				<label for="news_img_default_xy"><?php echo _L('news_img_default_xy'); ?></label><br />
				<input name="news_img_default_xy" id="news_img_default_xy" type="text" value="<?php echo intval($_POST['news_img_default_xy']); ?>" class="text_field" maxlength="5" />
			</p>
			<p>
				<label for="date_format"><?php echo _L('date_format'); ?></label> (<a href="http://www.php.net/date" title="Date">www.php.net/date</a>)<br />
				<input name="date_format" id="date_format" type="text" value="<?php echo $_POST['date_format']; ?>" class="text_field" />
			</p>
			<?php

				/**
				 * @todo these are some forms specific options. move them to backend
				 *
				 */
				if($orbx_mod->validate_module('forms')) {

			?>
			<p>
				<input value="1" name="use_captcha" id="use_captcha" type="checkbox" <?php if($_POST['use_captcha']) echo 'checked="checked"'; ?> />
				<label for="use_captcha"><?php echo _L('use_captcha'); ?></label>
			</p>
			<?php
				}
			?>

			<p>
				<label for="override_module"><?php echo _L('override_module'); ?></label><br />

				<select name="override_module" id="override_module">
					<option value="">&mdash;</option>
					<?php echo print_select_menu(array_map(array($orbx_mod, '_trim_mod_name'), $orbx_mod->all_modules), $_POST['override_module']); ?>
				</select>
			</p>

			<p>
				<label for="form_feedback_position"><?php echo _L('form_feedback_position'); ?></label><br />
				<select id="form_feedback_position" name="form_feedback_position">
					<option value="top" <?php if($_POST['form_feedback_position'] == 'top') echo 'selected="selected"'; ?>><?php echo _L('form_position_top'); ?></option>
					<option value="inside" <?php if($_POST['form_feedback_position'] == 'inside') echo 'selected="selected"'; ?>><?php echo _L('form_position_inside'); ?></option>
				</select>

			</p>

		</div>

	</fieldset>

	<fieldset id="mysql_advanced"><legend><?php echo _L('advanced_mysql_settings'); ?></legend>
		<div class="column">
			<p>
				<label for="mysql_host"><?php echo _L('mysql_host'); ?></label><br />
				<input name="mysql_host" id="mysql_host" type="text" value="<?php echo DB_HOST; ?>" class="text_field" />
			</p>
			<p>
				<label for="mysql_db"><?php echo _L('mysql_db'); ?></label><br />
				<input name="mysql_db" id="mysql_db" type="text" value="<?php echo DB_NAME; ?>" class="text_field" />
			</p>

			<p>
				<input name="mysql_perma" id="mysql_perma" type="checkbox" value="1" <?php if($_POST['mysql_perma']) echo 'checked="checked"'; ?> />
				<label for="mysql_perma"><?php echo _L('mysql_perma'); ?></label>
			</p>

		</div>

		<div class="column">
			<p>
				<label for="mysql_username"><?php echo _L('mysql_username'); ?></label><br />
				<input name="mysql_username" id="mysql_username" type="text" value="<?php echo DB_USER; ?>" class="text_field"  />
			</p>
			<p>
				<label for="mysql_pass"><?php echo _L('mysql_pass'); ?></label><br />
				<input name="mysql_pass" id="mysql_pass" type="password" value="<?php echo str_repeat(' ', 10); ?>" class="text_field" />
			</p>
			<p>
				<label for="mysql_pass_v"><?php echo _L('mysql_pass_v'); ?></label><br />
				<input name="mysql_pass_v" id="mysql_pass_v" type="password" value="<?php echo str_repeat(' ', 10); ?>" class="text_field" />
			</p>
		</div>
	</fieldset>

	<fieldset id="ftp_advanced"><legend><?php echo _L('advanced_ftp_settings'); ?></legend>
		<div class="column">
			<p>
				<label for="ftp_host"><?php echo _L('ftp_host'); ?></label><br />
				<input name="ftp_host" id="ftp_host" type="text" value="<?php echo FTP_HOST; ?>" class="text_field" />
			</p>
			<p>
				<label for="ftp_rootdir"><?php echo _L('ftp_rootdir'); ?></label><br />
				<input name="ftp_rootdir" id="ftp_rootdir" type="text" value="<?php echo FTP_ROOTDIR; ?>" class="text_field" />
			</p>
		</div>

		<div class="column">
			<p>
				<label for="ftp_username"><?php echo _L('ftp_username'); ?></label><br />
				<input name="ftp_username" id="ftp_username" type="text" value="<?php echo FTP_USER; ?>" class="text_field"  />
			</p>
			<p>
				<label for="ftp_pass"><?php echo _L('ftp_pass'); ?></label><br />
				<input name="ftp_pass" id="ftp_pass" type="password" value="<?php echo str_repeat(' ', 10); ?>" class="text_field" />
			</p>
			<p>
				<label for="ftp_pass_v"><?php echo _L('ftp_pass_v'); ?></label><br />
				<input name="ftp_pass_v" id="ftp_pass_v" type="password" value="<?php echo str_repeat(' ', 10); ?>" class="text_field" />
			</p>
		</div>
	</fieldset>

	<div class="control_btn">
		<p>
			<button name="save_settings" type="submit"><?php echo _L('save'); ?></button>
			<input type="reset" value="<?php echo _L('reset'); ?>" />
		</p>
	</div>
	<div class="null_it">&nbsp;</div>
</form>