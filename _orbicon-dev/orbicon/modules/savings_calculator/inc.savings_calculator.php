<?php
/**
 * functions of savings calculator module
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-06-27
 */
	define('TABLE_MOD_SAVINGS_CALC', 'orbx_mod_sav_calc');
	define ('TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV', 'orbx_mod_sav_calc_type_of_sav');

	//Function for saving type of savings in the database (name of saving is stored). It's value is  proccessed on one field input on form when user submits a form
	function save_type_of_savings()
	{
		global $dbc, $orbicon_x;

		$q = sprintf('	INSERT
							INTO 		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.' (
										stednja, language
							) VALUES (	%s, %s
							)',
						$dbc->_db->quote($_POST['vrsta_stednje_1']), $dbc->_db->quote($orbicon_x->ptr));

			//$id = $dbc->_db->insert_id();
		$r = $dbc->_db->query($q);

		if(empty($r)) {
			return NULL;
		}
		else {
		  echo _L('success_save');
		}

	}

	//Function for generating form for proccessing saving type of savings (that will be stored in database later). Argument is needed when calling function; in function argumet defines a value of id in the table
	function edit_stednje($id)
	{

	global $dbc, $orbicon_x;
		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
						WHERE 		(id = %s) AND
									(language = %s)
						LIMIT 		1', $dbc->_db->quote($id), $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}


	$edit .= '<form method="post" action="">
	<p>
		<label for="vrsta_stednje">'._L('vrsta_stednje').'</label><br />
		<input id="vrsta_stednje" name="vrsta_stednje" value="'.$a['stednja'].'"  />
	</p>

	<input type="submit" id="update_type_of_savings" name="update_type_of_savings" value="'._L('edit').'"/>
    </form>';
	echo $edit;

	}

	// Function for updating type of savings in the database.
	function update_type_of_savings()
	{

		global $orbicon_x;

		sql_update('	UPDATE 		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
						SET			stednja = %s
						WHERE 		(id = %s) AND
									(language = %s)',
						array($_POST['vrsta_stednje'], $_GET['edit'], $orbicon_x->ptr));

		redirect(ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator&edit=' . $_GET['edit']);

	}

	// Function for saving new items of savings in the database. Values for saving items are post through form
	function save_new_value()
	{
		global $dbc, $orbicon_x;

		$kamatna_stopa = $_POST['kamatna_stopa'];
		$vrsta_stednje = $_POST['vrsta_stednje'];
		$kuna_ili_deviza = $_POST['kuna_ili_deviza'];
		$valuta = $_POST['valuta'];
		$valutna_klauzula = $_POST['valutna_klauzula'];
		$rok_orocenja = $_POST['rok_orocenja'];

			$q = sprintf('	INSERT
							INTO 		'.TABLE_MOD_SAVINGS_CALC.' (
										vrsta_stednje, kuna_ili_deviza, valuta, valutna_klauzula, rok_orocenja, kamatna_stopa, language
							) VALUES (	%s, %s, %s, %s, %s, %s, %s
							)',
						$dbc->_db->quote($vrsta_stednje), $dbc->_db->quote($kuna_ili_deviza),
						$dbc->_db->quote($valuta), $dbc->_db->quote($valutna_klauzula), $dbc->_db->quote($rok_orocenja),
						$dbc->_db->quote($kamatna_stopa), $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);

		if(!$r) {
			return NULL;
		}

		echo _L('success_save');
	}

		// Generates a form with values for updating items of savings
		function form_edit_item_values($id) {

	    	global $dbc, $orbicon_x;

	    	$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_SAVINGS_CALC.'
						WHERE 		(id = %s) AND
									(language = %s)
						LIMIT 		1', $dbc->_db->quote($id), $dbc->_db->quote($orbicon_x->ptr));

            		$r = $dbc->_db->query($q);
            		$ab = $dbc->_db->fetch_assoc($r);

	    		$kuna_ili_deviza_lista = kuna_ili_deviza_lista($ab['kuna_ili_deviza']);
            	$valutna_lista = valutna_lista($ab['valuta']);
            	$valutna_klauzula_lista = valutna_klauzula_lista($ab['valutna_klauzula']);
            	$rok_orocenja_lista = rok_orocenja_lista($ab['rok_orocenja']);
            	//$vrsta_stednje_lista = vrsta_stednje_lista();


            		if(empty($ab)) {
            			return NULL;
            		}


                	$edit_items .= '

                	    <form method="post" action="">
                    	<p>
                    		<label for="kamatna_stopa">'._L('kamatna_stopa').' (format: 0.00)</label><br />
                    		<input id="kamatna_stopa" name="kamatna_stopa" value="'.$ab['kamatna_stopa'].'" />
                    		<input type="hidden" id="vrsta_stednje" name="vrsta_stednje" value="'.$ab['vrsta_stednje'].'" />
                    	</p>

                    	<p>
                    	      <label for="kuna_ili_deviza">'._L('kuna_ili_deviza').'</label><br />
                    		  <select id="kuna_ili_deviza" name="kuna_ili_deviza">'.$kuna_ili_deviza_lista.'</select></td>
                    	</p>

                    	<p>
                    	      <label for="valuta">'._L('valuta').'</label><br />
                    		  <select id="valuta" name="valuta"><option></option>'.$valutna_lista.'</select></td>
                    	</p>

                    	<p>
                    	      <label for="valutna_klauzula">'._L('valutna_klauzula').'</label><br />
                    		  <select id="valutna_klauzula" name="valutna_klauzula">'.$valutna_klauzula_lista.'</select></td>
                    	</p>

                    	<p>
                    	      <label for="rok_orocenja">'._L('rok_orocenja').'</label><br />
                    		  <select id="rok_orocenja" name="rok_orocenja">'.$rok_orocenja_lista.'</select></td>
                    	</p>




                    	<input type="submit" id="update_savings_items_values" name="update_savings_items_values" value="'._L('save').'"/>
                        </form>';
                	echo $edit_items;
	}

	// Updates item values for current saving type
	function update_savings_item_values() {

   		global $dbc, $orbicon_x;
		$kamatna_stopa = $_POST['kamatna_stopa'];
		$kuna_ili_deviza = $_POST['kuna_ili_deviza'];
		$valuta = $_POST['valuta'];
		$valutna_klauzula = $_POST['valutna_klauzula'];
		$rok_orocenja = $_POST['rok_orocenja'];
		$naziv_stednje = $_POST['vrsta_stednje'];

		$id = $_GET['edit_item_values'];
		$q = sprintf('	UPDATE 		'.TABLE_MOD_SAVINGS_CALC.'
							SET			kuna_ili_deviza = %s, valuta = %s, valutna_klauzula = %s, rok_orocenja = %s, kamatna_stopa = %s
							WHERE 		(id = %s) AND
										(language = %s)', $dbc->_db->quote($kuna_ili_deviza), $dbc->_db->quote($valuta),
		                    $dbc->_db->quote($valutna_klauzula), $dbc->_db->quote($rok_orocenja), $dbc->_db->quote($kamatna_stopa),
							$dbc->_db->quote($id), $dbc->_db->quote($orbicon_x->ptr));

			//$id = $dbc->_db->insert_id();
		$dbc->_db->query($q);

		redirect(ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator&edit_item=' . $naziv_stednje);
    }

	// Deletes (form database) type of saving with all items in related table that are related to that type of saving
	function delete_type_of_savings()
	{
		if(isset($_GET['delete'])) {
			global $dbc, $orbicon_x;
			$q = sprintf('	DELETE
							FROM		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
							WHERE 		(id = %s) AND
										(language = %s)
							LIMIT 		1', $dbc->_db->quote($_GET['delete']), $dbc->_db->quote($orbicon_x->ptr));

			$dbc->_db->query($q);
			$q2 = sprintf('	DELETE
							FROM		'.TABLE_MOD_SAVINGS_CALC.'
							WHERE 		(vrsta_stednje = %s) AND
										(language = %s)
							', $dbc->_db->quote($_GET['delete']), $dbc->_db->quote($orbicon_x->ptr));

			$dbc->_db->query($q2);

			redirect(ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator');
		}
	}

	// Deletes (form database)  current item of saving
	function delete_item_of_current_savings()
	{
		if(isset($_GET['delete_item_values'])) {
		    //$naziv_stednje = $_POST['vrsta_stednje'];
		    $id = $_GET['delete_item_values'];
			global $dbc, $orbicon_x;
			$q = sprintf('	DELETE
							FROM		'.TABLE_MOD_SAVINGS_CALC.'
							WHERE 		(id = %s) AND
										(language = %s)
							', $dbc->_db->quote($id), $dbc->_db->quote($orbicon_x->ptr));

			$dbc->_db->query($q);

			redirect(ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator');
		}
	}

/*	function print_credit_list()
	{
		global $dbc, $orbicon_x;
		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_CREDIT_CALC.'
						WHERE 		(language = %s)
						ORDER BY 	title', $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}

		$table = '
		<table>
			<tr>
				<th><strong>'._L('credit_title').'</strong></th>
				<th><strong>'._L('interest_rate').'</strong></th>
				<th><strong>'._L('max_years').'</strong></th>
				<th><strong>'._L('edit').'</strong></th>
				<th><strong>'._L('delete').'</strong></th>
			</tr>';

		$i = 1;

		while($a) {

			$style = (($i % 2) == 0) ? ' style="background:#eeeeee;"' : '';
			$max_years = (empty($a['max_years'])) ? 'N/A' : $a['max_years'];

			$table .= "<tr $style>
			<td>{$a['title']}</td>
			<td>{$a['interest']}%</td>
			<td>".$max_years.'</td>
			<td><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/credit-calculator&edit=' . $a['id'] . '">'._L('edit').'</a></td>
			<td><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/credit-calculator&delete=' . $a['id'] . '">'._L('delete').'</a></td>
			</tr>';

			$a = $dbc->_db->fetch_array($r);
			$i ++;
		}

		$table .= '</table>';

		echo $table;
	}
	*/

	// Generates html dropdown list with types of saving
	function vrsta_stednje_lista()
	{
		global $dbc, $orbicon_x;

		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
						WHERE 		(language = %s)
						ORDER BY 	id', $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}

		while($a) {
			$menu .= "<option value=\"{$a['id']}\" label=\"{$a['kamatna_stopa']}\">{$a['stednja']}</option>";
			$a = $dbc->_db->fetch_array($r);
		}

		return $menu;
	}

	// Generates html dropdown list with option of national or foregin currency
	function kuna_ili_deviza_lista($default = '')
	{
		return print_select_menu(array(1=>_L('novac_1'), 2=>_L('novac_2')), $default, true);
	}

	// Generates html dropdown list with foregin currency names
	function valutna_lista($default = '')
	{
		return print_select_menu(array('EUR', 'CHF', 'USD'), $default);
	}

	// Generates html dropdown list based on currenxy condition
	function valutna_klauzula_lista($default = '')
	{
		return print_select_menu(array(_L('valutna_klauzula_stavka_1'), _L('valutna_klauzula_stavka_2'), _L('valutna_klauzula_stavka_3'), _L('valutna_klauzula_stavka_4')), $default, true);
	}

	function valutna_klauzula_lista_frontend()
	{
		return print_select_menu(array(_L('valutna_klauzula_stavka_1'), _L('valutna_klauzula_stavka_2'), _L('valutna_klauzula_stavka_3')), null, true);
	}

	// Generates html dropdown list for period of invest
	function rok_orocenja_lista($default = '')
	{
		return print_select_menu(array(0, 1, 3, 6, 12, 24, 36), $default);
	}

	// Generates html dropdown list for period of invest on frontend
	function rok_orocenja_lista_front()
	{
        $menu .= '  <option value="0"></option>
                    <option value="1">1</option>
    			    <option value="3">3</option>
            		<option value="6">6</option>
                    <option value="12">12</option>
    			    <option value="24">24</option>
            		<option value="36">36</option>';

		return $menu;
	}

	// Generates html dropdown list for period of invest  on frontend where type of savings only has 3 months of period of invest
	function rok_orocenja_lista_front2()
	{
        $menu .= '<option value="0"></option>
                  <option value="12">12</option>
			      <option value="24">24</option>
        		  <option value="36">36</option>';

		return $menu;
	}

	// Generates list of  stored saving types, with option links to edit types, items, and delete them. Name of saving type is readed from 0 to first 9 chars
	function ispis_stednji()
	{
		global $dbc, $orbicon_x;
    	$q = sprintf('	SELECT id, stednja
						FROM		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
						WHERE 		(language = %s)
						ORDER BY 	stednja', $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}

		$table = '
';

		$i = 1;

		while($a) {


		    /**
		     * @todo replace with truncate_text
		     */
			$table .= '
			<table border="0" width="100%"><tr style="border:1px solid black;"><td colspan="3"><strong>'.$a['stednja'].'</strong></td>
            </tr><tr>
			<td align="left"><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator&edit=' . $a['id'] . '">'._L('edit').'</a></td>
			<td align="left"><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator&edit_item=' . $a['id'] . '">'._L('edit_item').'</a></td>
			<td align="left"><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator&delete=' . $a['id'] . '" onclick="if( confirm(\''._L('click_delete_message').'\') == true ){ redirect(this.href);}else{return false;}">'._L('delete').'</a></td>
			</tr></table><hr />';

			$a = $dbc->_db->fetch_array($r);
			$i ++;
		}

		$table .= '';

		echo $table;
	}

	//  Generates table with details of items for current saving type.
	function ispis_detalja_stednje()
	{
        	    if(isset($_GET['edit_item'])) {
                		global $dbc, $orbicon_x;
                		$q2 = sprintf('	SELECT 		stednja
                						FROM		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
                						WHERE 		(language = %s) AND id = %s
                						ORDER BY 	id', $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($_GET['edit_item']));

                		$r2 = $dbc->_db->query($q2);
                		$a2 = $dbc->_db->fetch_array($r2);
                		if(empty($a2)) {
                        			return NULL;
                        }
                        $name_of_saving = $a2['stednja'];

                		$q = sprintf('	SELECT 		*
                						FROM		'.TABLE_MOD_SAVINGS_CALC.'
                						WHERE 		(language = %s) AND vrsta_stednje = %s
                						ORDER BY 	valutna_klauzula, rok_orocenja, valuta', $dbc->_db->quote($orbicon_x->ptr), $dbc->_db->quote($_GET['edit_item']));

                		$r = $dbc->_db->query($q);
                		$a = $dbc->_db->fetch_array($r);
                		$message = '<p>'._L('items_in_database_message').'</p> <a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator">'._L('insert_new_values').'</a>';

                        		if(empty($a)) {
                        			echo $message;
                        			return NULL;
                        		}

                        		$table = '
                        		<div><p><strong>'.$name_of_saving.'</strong></p></div>
                        		<table border="1" cellpadding="5">
                        			<tr>
                        				<th><strong>'._L('kuna_ili_deviza').'</strong></th>
                        				<th><strong>'._L('valuta').'</strong></th>
                        				<th><strong>'._L('valutna_klauzula').'</strong></th>
                        				<th><strong>'._L('rok_orocenja').'</strong></th>
                        				<th><strong>'._L('kamatna_stopa').'</strong></th>
                        				<th><strong>'._L('edit').'</strong></th>
                        				<th><strong>'._L('delete').'</strong></th>
                        			</tr>';

                        		$i = 1;

                        		while($a) {

                        		    if ($a['valutna_klauzula']==0) {
                                	    $a['valutna_klauzula'] = _L('valutna_klauzula_stavka_1');
                                	}
                                	if ($a['valutna_klauzula']==1) {
                                	   $a['valutna_klauzula'] = _L('valutna_klauzula_stavka_2');
                                	}
                                	if ($a['valutna_klauzula']==2) {
                                	   $a['valutna_klauzula'] = _L('valutna_klauzula_stavka_3');
                                	}
                                	if ($a['valutna_klauzula']==3) {
                                	   $a['valutna_klauzula'] = _L('valutna_klauzula_stavka_4');
                                	}
                                	if ($a['kuna_ili_deviza']==1) {
                                	   $a['kuna_ili_deviza'] = _L('novac_1');
                                	}
                                	if ($a['kuna_ili_deviza']==2) {
                                	   $a['kuna_ili_deviza'] = _L('novac_2');
                                    }

                    			$table .= '
                    			<td>'.$a['kuna_ili_deviza'].'</td>
                    			<td>'.$a['valuta'].'</td>
                    			<td>'.$a['valutna_klauzula'].'</td>
                    			<td>'.$a['rok_orocenja'].'</td>
                    			<td>'.$a['kamatna_stopa'].'</td>
                    			<td><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator&edit_item_values=' . $a['id'] . '">'._L('edit').'</a></td>
                    			<td><a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator&delete_item_values=' . $a['id'] . '" onclick="if( confirm(\''._L('click_delete_item_message').'\') == true ){ redirect(this.href);}else{return false;}">'._L('delete').'</a></td>
                    			</tr>';

                    			$a = $dbc->_db->fetch_array($r);
                    			$i ++;
                    		}

                		$table .= '</table>';

                		echo $table;
        	    }
	}

	// Generates form for editing name of savings type
    function edit_vrijednosti_stavki($id) {

	global $dbc, $orbicon_x;
		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
						WHERE 		(id = %s) AND
									(language = %s)
						LIMIT 		1', $dbc->_db->quote($id), $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}


	$edit .= '<form method="post" action="">
	<p>
		<label for="vrsta_stednje">'._L('vrsta_stednje').'</label><br />
		<input id="vrsta_stednje" name="vrsta_stednje" value="'.$a['stednja'].'"  />
	</p>

	<input type="submit" id="update_type_of_savings" name="update_type_of_savings" value="'._L('save').'"/><br />
    </form>
    <a href="' . ORBX_SITE_URL . '/?'. $orbicon_x->ptr . '=orbicon/mod/savings_calculator">'._L('insert_new_values').'</a>
    ';
	echo $edit;

	}

	// Counts number of savings types and displays it
	function broj_stednji()
	{
		global $dbc, $orbicon_x;

		$q = sprintf('	SELECT 		COUNT(stednja)
						FROM		'.TABLE_MOD_SAVINGS_CACL_TYPE_OF_SAV.'
						WHERE 		(language = %s)
						ORDER BY 	stednja', $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}

		$message = _L('count_of_savings');
		while($a) {
			$menu .= "<div>{$message} {$a['COUNT(stednja)']}</div>";
			$a = $dbc->_db->fetch_array($r);
		}

		echo $menu;
	}

	// Displays starting month to chose in drop down list
	function pocetni_mjesec_orocenja_lista() {

	        $list = '
	        <option value="1">1</option>
	        <option value="2">2</option>
	        <option value="3">3</option>
	        <option value="4">4</option>
	        <option value="5">5</option>
	        <option value="6">6</option>
	        <option value="7">7</option>
	        <option value="8">8</option>
	        <option value="9">9</option>
	        <option value="10">10</option>
	        <option value="11">11</option>
	        <option value="12">12</option>
	        ';

	    return $list;
	}
	// Displays interest rates in drop down
	/*function kamatna_stopa_lista($vrsta_stednje,$kuna_ili_deviza,$valuta,$valutna_klauzula) {

        $q = sprintf('	SELECT 		kamatna_stopa
						FROM		'.TABLE_MOD_SAVINGS_CALC.'
						WHERE 		naziv_stednje = %s AND kuna_ili_deviza = %s AND valuta = %s AND valutna_klauzula = %s AND
									(language = %s)
						LIMIT 		1', $dbc->_db->quote($vrsta_stednje), $dbc->_db->quote($kuna_ili_deviza),
        $dbc->_db->quote($valuta), $dbc->_db->quote($valutna_klauzula), $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}

		$menu .= '<option value="1">1</option>
			      <option value="3">3</option>
        		  <option value="6">6</option>
                  <option value="12">12</option>
			      <option value="24">24</option>
        		  <option value="36">36</option>';

		return $menu;


	}*/


	// Generates html dropdown list with types of saving
	function kamatna_stopa_lista($z)
	{
		global $dbc, $orbicon_x;

		$q = sprintf('	SELECT 		*
						FROM		'.TABLE_MOD_SAVINGS_CALC.'
						WHERE 		(language = %s) AND vrsta_stednje = "'.$z.'"
						ORDER BY 	id', $dbc->_db->quote($orbicon_x->ptr));

		$r = $dbc->_db->query($q);
		$a = $dbc->_db->fetch_array($r);

		if(empty($a)) {
			return NULL;
		}

		while($a) {
			$menu .= "<option value=\"{$a['rok_orocenja']}\" label=\"{$a['kamatna_stopa']}\">{$a['kamatna_stopa']}</option>";
			$a = $dbc->_db->fetch_array($r);
		}

		return $menu;
	}


	/*function savings_calculator_calculate()
	{
           $vrsta_s = $_POST['vrsta_stednje'];
           $oroc = $_POST['orocenje'];
           $kuna_ili_dev = $_POST['kuna_ili_deviza'];
           $val =  $_POST['valuta'];
           $valutna_kl =  $_POST['valutna_klauzula'];
           $rok_oroc = $_POST['rok_orocenja'];

           echo $oroc.'<div>test</div>';
	}*/
?>