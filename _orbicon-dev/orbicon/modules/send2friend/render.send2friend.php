<?php

	$url = ORBX_SITE_URL;
	$orbx_build = ORBX_BUILD;

	$alert = _L('enter_name');
	$alert_mails = _L('enter_mails');
	$enter_your_info = _L('enter_your_info');
	$send_failed = _L('send_failed');
	$submit = _L('submit');
	$cancel = _L('cancel');
	$name = _L('name');
	$email = _L('email');
	$f_email = _L('friend_email');
	$refer = ORBX_SITE_URL . str_replace(ORBX_URI_PATH, '', $_SERVER['REQUEST_URI']);
	$f_emails_header = _L('friends_emails');

	// this is for confirmation
	$confirm_domain_sig = DOMAIN_NAME;

return <<<TXT
<style type="text/css" media="all">/*<![CDATA[*/
	#dialog2 label { display:block;float:left;width:45%;clear:left; }
	#dialog2 label, #dialog2 strong {font-size: 11px; }
	#show_emty_name_popup .hd, #show_emty_mail_popup .hd {background-color: #cc0000; }
	#show_emty_name_popup {height: auto !important; background: white;}
/*]]>*/</style>

<script type="text/javascript"><!-- // --><![CDATA[
		YAHOO.namespace("example.container");

		function init() {

			/** pop for emty name **/

			var handleCancelEmptyName = function() {
				this.cancel();
			};

			// Instantiate the Dialog
			var myDialogEmptyName = new YAHOO.widget.SimpleDialog("show_emty_name_popup",
														{ 	width : "300px",
															fixedcenter : true,
															draggable: false,
															visible : false,
															constraintoviewport : true,
															buttons : [{text:"Ok", handler:handleCancelEmptyName}]
														} );

			// Render the Dialog
			myDialogEmptyName.render();

			/** empty name  ends **/

			/** pop for empty mails **/

			var handleCancelEmptyMail = function() {
				this.cancel();
			};

			// Instantiate the Dialog
			var myDialogEmptyMail = new YAHOO.widget.SimpleDialog("show_emty_mail_popup",
														{ 	width : "300px",
															fixedcenter : true,
															draggable: false,
															visible : false,
															constraintoviewport : true,
															buttons : [{text:"Ok", handler:handleCancelEmptyMail}]
														} );

			// Render the Dialog
			myDialogEmptyMail.render();

			/** empty mails ends **/



			// Define various event handlers for Dialog
			var handleSubmit = function() {
				this.submit();
			};
			var handleCancel = function() {
				this.cancel();
			};
			var handleSuccess = function(o) {
				/*var response = o.responseText;
				window.alert(o.responseText);*/
			};
			var handleFailure = function(o) {
				window.alert("{$send_failed}: " + o.status);
			};

			// Instantiate the Dialog
			YAHOO.example.container.dialog2 = new YAHOO.widget.Dialog("dialog2",
																		{ width : "300px",
																		  fixedcenter : true,
																		  visible : false,
																		  constraintoviewport : true,
																		  buttons : [ { text:"{$submit}", handler:handleSubmit },
																					  { text:"{$cancel}", handler:handleCancel } ]
																		 } );

			// Validate the entries in the form to require that both first and last name are entered
			YAHOO.example.container.dialog2.validate = function() {
				var data = this.getData();
				if (empty(data.name)) {
					myDialogEmptyName.show();
					// alert("{$alert}.");
					return false;
				} else if ((empty(data.email1)== true) && (empty(data.email2)== true) && (empty(data.email3)== true) && (empty(data.email4)== true) && (empty(data.email5)== true)) {
					myDialogEmptyMail.show();
					// alert("{$alert_mails}.");
					return false;
				} else {
					return true;
				}
			};

			// Wire up the success and failure handlers
			YAHOO.example.container.dialog2.callback = { success: handleSuccess,
														 failure: handleFailure };

			// Render the Dialog
			YAHOO.example.container.dialog2.render();
		}

		YAHOO.util.Event.addListener(window, "load", init);

	function show_send2friend()
	{
		$('dialog2').style.display = 'block';
		YAHOO.example.container.dialog2.show();
	}

// ]]></script>

<div id="dialog2" style="display:none;">

	<div class="hd">{$enter_your_info}</div>
	<div class="bd">
		<form method="post" action="{$url}/orbicon/modules/send2friend/xhr.send2friend.php">
			<p>
				<input type="hidden" value="{$refer}" id="refer_url" name="refer_url" />
				<label for="name">{$name}:</label><input type="textbox" name="name" id="name" />
				<label for="email">{$email}:</label><input type="textbox" name="email" id="email" />
			</p>

			<p><strong>{$f_emails_header}</strong></p>

			<p>
				<label for="email1">Prva e-mail adresa</label> <input type="textbox" name="email1" id="email1" />
				<label for="email2">Druga e-mail adresa</label> <input type="textbox" name="email2" id="email2" />

			</p>

		</form>
	</div>
</div>
<div id="show_emty_name_popup" style="visibility: hidden;">
	<div class="hd">{$confirm_domain_sig}</div>
	<div class="bd">{$alert}</div>
</div>
<div id="show_emty_mail_popup" style="visibility: hidden;">
	<div class="hd">{$confirm_domain_sig}</div>
	<div class="bd">{$alert_mails}</div>
</div>
TXT;

/*<!-- <label for="email3">{$f_email} #3:</label><input type="textbox" name="email3" id="email3" />
				<label for="email4">{$f_email} #4:</label><input type="textbox" name="email4" id="email4" />
				<label for="email5">{$f_email} #5:</label><input type="textbox" name="email5" id="email5" /> -->
*/
?>