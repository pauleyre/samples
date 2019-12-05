<?php

		header('Content-Type: application/vnd.adobe.xdp+xml', true);

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime>".$_GET['ime']."</ime>
				

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='http://localhost/wizard/pristupnica.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";

?>