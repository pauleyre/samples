<?php

	require DOC_ROOT . '/orbicon/modules/orbitum-pickup-depot/inc.depot.php';

	$products = get_orbitum_products();
	$products = empty($products) ? '<tr><td colspan="6" style="text-align:center;">No Products Purchased</td></tr>' : $products;
	$name = (get_is_admin()) ? trim($_SESSION['user.a']['first_name'] . ' ' . $_SESSION['user.a']['last_name']) : trim($_SESSION['user.r']['contact_name'] . ' ' . $_SESSION['user.r']['contact_surname']);

	return <<<TXT
<h2>{$name}'s Pickup Depot</h2>

<p>
	<strong>Your Pickup Depot is a central location to download software and request product licenses.</strong>
</p><br />


<ul>
	<li>Click <b>Download</b> to download your software.</li>
	<li>Click <b>License</b> to download your software's license.</li>
	<li>You can also try out live demos.</li>
</ul><br />

<hr style="border-style: 1px solid #cccccc;" />

<p style="text-align:center"><a href="http://orbitum.net/en/support/">Get Support</a></p>


<table border="0" cellpadding="4" cellspacing="1" width="100%">
	<tr bgcolor="#dddddd">
		<th>Product</td>
		<th>Purchase Date</td>
		<th>Valid Until</td>
		<th>Order #</td>
		<th>Quantity</td>
		<th>Action</td>
	</tr>

	{$products}

	<tr>
		<td align="left"><strong>Orbicon Enterprise Live Demo</strong></td>
		<td align="center">N / A</td>
		<td align="center">Perpetual</td>
		<td align="center">N / A</td>
		<td align="center">N / A</td>
		<td align="center"><a href="http://enterprise-demo.orbitum.net/"><strong>Try</strong></a></td>
	</tr>
	<tr>
		<td align="left"><strong>Orbicon Xtreme Live Demo</strong></td>
		<td align="center">N / A</td>
		<td align="center">Perpetual</td>
		<td align="center">N / A</td>
		<td align="center">N / A</td>
		<td align="center"><a href="http://xtreme-demo.orbitum.net/"><strong>Try</strong></a></td>
	</tr>
	<tr>
		<td align="left"><strong>Orbicon Lite Live Demo</strong></td>
		<td align="center">N / A</td>
		<td align="center">Perpetual</td>
		<td align="center">N / A</td>
		<td align="center">N / A</td>
		<td align="center"><a href="http://lite-demo.orbitum.net/"><strong>Try</strong></a></td>
	</tr>
	<tr>
		<td colspan="6" bgcolor="#dddddd" height="4">&nbsp;</td>
	</tr>

</table>
TXT;

?>