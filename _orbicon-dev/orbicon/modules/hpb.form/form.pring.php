<?php

include DOC_ROOT . '/orbicon/modules/hpb.form/h.hpbform.php';

$display_content = '
<table id="peopleRingTable">
  <colgroup>
    <col class="date" />
    <col class="name" align="left" />
  </colgroup>
  <tr>
    <th>Datum</th>
    <th>Naziv</th>
  </tr>
  <tbody>';

	$r = sql_res('SELECT * FROM hpb_forms WHERE owner_id = %s', $_SESSION['user.r']['id']);

	$a = $dbc->_db->fetch_assoc($r);
	$i = 0;

	while($a) {

		$bg = (($i % 2) == 0) ? '#fff' :'#eee';
		$name = get_hpbform_tpl($a['form'], true);

		$display_content .= '
		    <tr style="background-color:'.$bg.'">
      <td>'.date('d.m.Y.', $a['form_date']).'</td>
      <td style="text-align:left"><a target="_blank" href="./orbicon/modules/hpb.form/pdf.php?pdf='.$a['form'].'&amp;fid='.$a['form_id'].'">'.$name.'</a></td>
    </tr>
';

		/*

		      <td><a href="./orbicon/modules/hpb.form/pdf.php?pdf='.$a['form'].'&amp;fid='.$a['form_id'].'">Obrazac (No. '.$a['form_id'].')</a></td>
      <td>'.$name.'</td>

		*/
		$a = $dbc->_db->fetch_assoc($r);
		$i ++;
	}

  $display_content .= '</tbody></table>';

?>