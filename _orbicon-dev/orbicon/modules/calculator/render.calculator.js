/**
 * javascript functions of calculator module
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-04
 */

function show_simple_calc()
{
	$('show_calc').style.display = 'block';
	YAHOO.showcalc.container.show_calc.show();
}

		YAHOO.namespace("showcalc.container");

		function init_showcalc() {

			// Define various event handlers for Dialog
			var handleSubmit = function() {
			};
			var handleCancel = function() {
				this.cancel();
			};
			var handleResults = function() {
			};
			var handleSuccess = function(o) {
			};
			var handleFailure = function(o) {
				window.alert("Failure: " + o.status);
			};


			var ln_calculate;
			var ln_cancel;
			switch(__orbicon_ln) {
				case 'hr':
					ln_calculate = 'Izračunaj';
					ln_cancel = 'Zatvori';
				break;
				case 'en':
					ln_calculate = 'Calculate';
					ln_cancel = 'Cancel';
				break;
			}


			// Instantiate the Dialog
			YAHOO.showcalc.container.show_calc = new YAHOO.widget.Dialog("show_calc",
																		{
																		  fixedcenter : true,
																		  visible : false,
																		  constraintoviewport : true
																		 } );

			// Validate the entries in the form to require that both first and last name are entered
			YAHOO.showcalc.container.show_calc.validate = function() {
				var data = this.getData();
				if (data.name == "") {
					return false;
				} else {
					return true;
				}
			};

			// Wire up the success and failure handlers
			YAHOO.showcalc.container.show_calc.callback = { success: handleSuccess,
														 failure: handleFailure };

			// Render the Dialog
			YAHOO.showcalc.container.show_calc.render();
		}


		YAHOO.util.Event.addListener(window, "load", init_showcalc);
