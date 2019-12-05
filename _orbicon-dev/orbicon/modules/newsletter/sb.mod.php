<div class="sidebar_subprop" id="res_nwsltr_content" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/database-browser.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_nwsltr_content_container');"><?php echo _L('db_content'); ?></a></div>

<div id="res_nwsltr_content_container">

<div id="mini_browser_container"></div>

</div>


<div class="sidebar_subprop" id="res_properties" style="background: url(<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/mini_browser_gui/properties.gif) no-repeat; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_properties_container');"><?php echo _L('properties'); ?></a></div>

<div id="res_properties_container" style="display:none;">

<p>
		<label for="newsletter_server_pause"><?php echo _L('mailing_pause'); ?></label><br>
		<select name="newsletter_server_pause" id="newsletter_server_pause" onblur="javascript: orbx_carrier(this, document.nwsltr_form.newsletter_server_pause);" onchange="javascript: orbx_carrier(this, document.nwsltr_form.newsletter_server_pause);">
			<option></option>
			<option value="0"><?php echo _L('no_pause'); ?></option>
			<option value="500000">0,5s</option>
			<option value="1000000">1s</option>
			<option value="2000000">2s</option>
		</select>
		</p>

</div>