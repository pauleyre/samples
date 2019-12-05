<?php

	global $orbicon_x;

	$fond_list = new Fond();
	$tmp_res_fl = $fond_list->get_all_fonds(1);

	echo '
	<h2>'._L('invest-overview-fond').'</h2>
	<ul id="fond_list">';

	$i = 1;

	while($fl = $dbc->_db->fetch_array($tmp_res_fl)){

		$high = ($i%2 == 0) ? 'class="high"' : '';

		// * pointer
		$move = ($_GET['showPage'] == 'fond' && $_GET['do'] == 'newfond') ? ' style="cursor: move;"' : '';

		echo '
			<li '.$high.$move.' id="sort_'.$fl['id'].'">
				<a href="?'.$orbicon_x->ptr.'=orbicon/mod/invest&amp;fond='.$fl['id'].'" name="'.$fl['title'].'" title="'.$fl['title'].'">&raquo;
					'.$fl['title'].'</a>
			</li>
		';
		$i++;
	}

	echo '</ul>';

	if($_GET['showPage'] == 'fond'){

		echo '
		<!-- reordering part -->
		<script type="text/javascript"><!-- // --><![CDATA[


			Sortable.create("fond_list",
								{
									onUpdate: 	function()
												{
													new Ajax.Request("'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=orbicon/mod/invest&showPage=fond&do=newfond",
																		{
																			method: 	"post",
																			parameters: { reorderColumns: Sortable.serialize("fond_list") },
																			onComplete:	showResponse
																		}
																	);
												}
								}
							);

			function showResponse()
			{
				new Effect.Highlight("fond_list",{duration: 0.5});
				return true;
			}

			//YAHOO.util.Event.addListener(window,"load",__sortable_onload);

		// ]]></script>';
	}

?>