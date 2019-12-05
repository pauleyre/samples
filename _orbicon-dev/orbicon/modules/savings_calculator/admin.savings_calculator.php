<?php
/**
 * back end file of savings calculator module
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-27
 */

    // defining file with functions
	require DOC_ROOT . '/orbicon/modules/savings_calculator/inc.savings_calculator.php';

	// defining css file
	echo '<link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/modules/savings_calculator/style.css">';

	// defining variables that store correct translation variables and functions
	$kuna_ili_deviza = _L('kuna_ili_deviza');
	$kuna_ili_deviza_lista = kuna_ili_deviza_lista();
	$valuta = _L('valuta');
	$valutna_lista = valutna_lista();
	$valutna_klauzula = _L('valutna_klauzula');
	$valutna_klauzula_lista = valutna_klauzula_lista();
	$rok_orocenja = _L('rok_orocenja');
	$rok_orocenja_lista = rok_orocenja_lista();
	$vrsta_stednje = _L('vrsta_stednje');
	$administracija_vrsta_stednji = _L('administracija_vrsta_stednji');
	$administracija_stavki_u_stednji = _L('administracija_stavki_u_štednji');

	//echo '<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/savings_calculator&amp;link=type_of_savings">'.$administracija_vrsta_stednji.'</a> | <a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/savings_calculator&amp;link=items_of_savings">'.$administracija_stavki_u_stednji.'</a></p>';

	// functions for deleting types of savings and current item. Exectued based on $_POST condition in functions
	delete_type_of_savings();
	delete_item_of_current_savings();


    // if condition is true, function for upadating name of type of savings will be executed
	if(isset($_GET['edit'])) {
        edit_stednje($_GET['edit']);
        if(isset($_POST['update_type_of_savings'])&&(!empty($_POST['vrsta_stednje']))) {
	    //old --> save_cred();
		update_type_of_savings();
	   }
	}

	// if condition is true, function for displaying details (items) of saving will be executed
	if(isset($_GET['edit_item'])) {
        //edit_stednje($_GET['edit']);
        //if(isset($_POST['update_type_of_savings'])&&(!empty($_POST['vrsta_stednje']))) {
	    //old --> save_cred();
		//update_type_of_savings();
	   //}
	   	ispis_detalja_stednje();
	}

	// if condition is true, form for editing item values will be displayed.
	// If item is edited and saved, function upadate_savings_item_values is executed
	if(isset($_GET['edit_item_values'])) {
        form_edit_item_values($_GET['edit_item_values']);
        if(isset($_POST['update_savings_items_values'])) {
		  update_savings_item_values();
	   }
	}

	// if condition is true, form for editing item values will be displayed.
	// If item is clicked to delete, alert will apear with option to delete item
	if(isset($_GET['delete_item_values'])) {
        form_edit_item_values($_GET['edit_item_values']);
        if(isset($_POST['update_savings_items_values'])) {
		  update_savings_item_values();
	   }
	}

	// if none variables for editing are set, inicial form is displayed
	if((!isset($_GET['edit'])) && (!isset($_GET['edit_item'])) && (!isset($_GET['edit_item_values']))) {


            // type of savings
            echo'
                <form method="post" action="" id="form_1">
            	<p>
            		<label for="vrsta_stednje_1">'._L('vrsta_stednje').'</label><br />
            		<input id="vrsta_stednje_1" name="vrsta_stednje_1" value="'.$my_credit['vrsta_stednje'].'"  />
            	</p>

            	<input type="submit" id="save_type_of_savings" name="save_type_of_savings" value="'._L('save').'"/>
            </form><br />
            ';
                // if variables are set, type of savings is saved
            	if(isset($_POST['save_type_of_savings'])&&(!empty($_POST['vrsta_stednje_1']))) {
            	    //old --> save_cred();
            		save_type_of_savings();
            	}

            	// if variables are not set, type of savings is not saved and message is displayed
            	if(isset($_POST['save_type_of_savings'])&&(empty($_POST['vrsta_stednje_1']))) {
            		echo _L('vrsta_stednje_poruka');
            	}

                $vrsta_stednje_lista = vrsta_stednje_lista();

            	echo'
                <form method="post" action="" id="form_2">
            	<p>
            	      <label for="vrsta_stednje">'.$vrsta_stednje.'</label><br />
            		  <select id="vrsta_stednje" name="vrsta_stednje"><option></option>'.$vrsta_stednje_lista.'</select></td>
            	</p>
            	<p>
            		<label for="kamatna_stopa">'._L('kamatna_stopa').' (format: 0.00)</label><br />
            		<input id="kamatna_stopa" name="kamatna_stopa" value="" />
            	</p>

            	<p>
            	      <label for="kuna_ili_deviza">'.$kuna_ili_deviza.'</label><br />
            		  <select id="kuna_ili_deviza" name="kuna_ili_deviza">'.$kuna_ili_deviza_lista.'</select></td>
            	</p>

            	<p>
            	      <label for="valuta">'.$valuta.'</label><br />
            		  <select id="valuta" name="valuta"><option></option>'.$valutna_lista.'</select></td>
            	</p>

            	<p>
            	      <label for="valutna_klauzula">'.$valutna_klauzula.'</label><br />
            		  <select id="valutna_klauzula" name="valutna_klauzula">'.$valutna_klauzula_lista.'</select></td>
            	</p>

            	<p>
            	      <label for="rok_orocenja">'.$rok_orocenja.'</label><br />
            		  <select id="rok_orocenja" name="rok_orocenja">'.$rok_orocenja_lista.'</select></td>
            	</p>

            	<input type="submit" id="save_new_value" name="save_new_value" value="'._L('save').'"/>
            </form><br />';

            	// if variables are set, new items for chosen saving type are saved
            	if(isset($_POST['save_new_value'])&&(!empty($_POST['kamatna_stopa']))) {
            		save_new_value();
            	}

            	// if variables are not set, item of savings is not saved and message is displayed
            	if(isset($_POST['save_new_value'])&&(empty($_POST['kamatna_stopa']))) {
            		echo '<br />'._L('kamatna_stopa_poruka');
            	}

	}



?>
