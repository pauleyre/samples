<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:
	Modified.......:
*-----------------------------------------------------------------------*/
	$q_color_picker = explode('/', $_GET[$orbicon_x->ptr]);
?>

<style type="text/css">/*<![CDATA[*/
/*margin and padding on body element
  can introduce errors in determining
  element position and are not recommended;
  we turn them off as a foundation for YUI
  CSS treatments. */
body {
	margin:0;
	padding:0;
}
/*]]>*/</style>

<link rel="stylesheet" type="text/css" href="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/colorpicker/assets/skins/sam/colorpicker.css&amp;<?php echo ORBX_BUILD; ?>" />
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/dragdrop/dragdrop-min.js&amp;<?php echo ORBX_BUILD; ?>" ></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/slider/slider-min.js&amp;<?php echo ORBX_BUILD; ?>" ></script>
<script type="text/javascript" src="<?php echo ORBX_SITE_URL; ?>/orbicon/controler/gzip.server.php?file=/orbicon/3rdParty/yui/build/colorpicker/colorpicker-beta-min.js&amp;<?php echo ORBX_BUILD; ?>"></script>


<style type="text/css">/*<![CDATA[*/

	#container {
		position: relative;
		padding: 6px;
		background-color: #eeeeee;
		width: 420px;
		height:220px;
	}

  .yui-picker-bg {
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/colorpicker/assets/picker_mask.png', sizingMethod='scale') !important;
  }

  .yui-picker-hue-bg {
  	background:url(<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/colorpicker/assets/skins/sam/hue_bg.png) no-repeat;
  }

  .yui-picker-bg {
  	background-image:url(<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/colorpicker/assets/skins/sam/picker_mask.png);
  }

	#ddPicker {
  		position: absolute;
  		background-color: #eeeeee;
  		/* IE requires width */
  		width: 433px;
	}

	#pickerHandle {
		background-color: #bbbbbb;
		height: 10px;
		cursor: move;
	}

/*]]>*/</style>

<div id="ddPicker">
    <div id="pickerHandle" ondblclick="javascript: RichTextHideColorPicker();">&nbsp;</div>
	<div id="container"></div>

		<?php

			if($q_color_picker[1] == 'columns') {
		?>

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/border-color.png" alt="Border Color" title="Border Color" onClick="javascript: __box_change_color($('yui-picker-hex').value, 'border');" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/background-color-box.png" alt="Background Color" title="Background Color" onClick="javascript: __box_change_color($('yui-picker-hex').value, 'background');" />

		<?php
			}
			else {
		?>
		<div id="rich_text_editor_color_picker_buttons">
	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/forecol.gif" alt="Font Color" title="Font Color" onClick="javascript: RichTextModifyColor($('yui-picker-hex').value, 'ForeColor');" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/bgcol.gif" alt="Background Color" title="Background Color" onClick="javascript: RichTextModifyColor($('yui-picker-hex').value, 'HiliteColor');" />

	<img src="<?php echo ORBX_SITE_URL; ?>/orbicon/rte/rte_buttons/doccol.gif" alt="Document Background Color" title="Document Background Color" onClick="javascript: RichTextModifyColor($('yui-picker-hex').value, 'DocumentColor');" />
		</div>
		<?php
			}
		?>

<!--We'll use these to trigger interactions with the Color Picker
API -->
<button id="reset">Reset Color to White</button>


</div>

<script type="text/javascript">
(function() {
    var Event = YAHOO.util.Event,
        picker;

    Event.onDOMReady(function() {

            picker = new YAHOO.widget.ColorPicker("container", {
                    showhsvcontrols: true,
                    showhexcontrols: true,
					images: {
						PICKER_THUMB: "<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/colorpicker/assets/picker_thumb.png",
						HUE_THUMB: "<?php echo ORBX_SITE_URL; ?>/orbicon/3rdParty/yui/build/colorpicker/assets/hue_thumb.png"
    				}
                });

			//use setValue to reset the value to white:
			Event.on("reset", "click", function(e) {
				picker.setValue([255, 255, 255], false); //false here means that rgbChange
													     //wil fire; true would silence it
			});

			var panel = new YAHOO.util.DD("ddPicker");
            panel.setHandleElId("pickerHandle");

        });
})();
</script>