<?php

	return '

<script type="text/javascript"><!-- // --><![CDATA[

	function subscribe_newsalerts()
	{
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				if(!empty(o.responseText)) {
					// * update container
					$(\'newsalerts_info\').innerHTML = o.responseText;
					$(\'subs_email\').value = \'\';
				}
			}
		}

		var callback =
		{
			success:handleSuccess,
			timeout: 15000
		};

		var email = $(\'subs_email\').value;
		if(empty(email)) {
			$(\'newsalerts_error\').innerHTML = (\''._L('validate_your_mail').'\');
			return false;
		}
		else {
			$(\'newsalerts_error\').innerHTML = \'\';
		}
		var data = new Array();
		data[0] = \'email=\' + email;

		data = data.join(\'&\');

		YAHOO.util.Connect.asyncRequest(\'POST\', orbx_site_url + \'/orbicon/modules/news-alerts/subs.php\', callback, data);
	}

// ]]></script>

	<div id="newsalerts_subs_box">
		<h3>Newsletter</h3>
		<label id="newsalerts_info">Ukoliko želite zaprimati novosti direktno na vašu elektroničku poštu molimo vas da upišete e-mail adresu</label>
		<input id="subs_email" name="subs_email" value="" type="text" /> <input id="btn_news_subscribe" value="'._L('subscribe').'" type="button" onclick="javascript:subscribe_newsalerts();" />
		<p id="newsalerts_error"></p>
	</div>';

?>