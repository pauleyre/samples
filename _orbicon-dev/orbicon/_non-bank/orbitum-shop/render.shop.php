<?php

	require_once DOC_ROOT . '/orbicon/modules/orbitum-shop/inc.shop.php';

	if(isset($_GET['finish'])) {
		finish_order($_REQUEST['order_id'], ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.orbitum-pickup-depot');

	}

	if(isset($_GET['pid'])) {
		$_SESSION['shopping_cart'] = (int) $_GET['pid'];
	}

	if(empty($_SESSION['shopping_cart'])) {
		return 'Your shopping cart is empty';
	}

	$prod = get_product($_SESSION['shopping_cart']);

	$user = $_SESSION['user.r'];

	// not admin
	if(!get_is_admin()) {
		$user['first_name'] = $_SESSION['user.r']['contact_name'];
		$user['last_name'] = $_SESSION['user.r']['contact_surname'];
		$user['city'] = $_SESSION['user.r']['contact_city'];
		$user['zip'] = $_SESSION['user.r']['contact_zip'];
		$user['address'] = $_SESSION['user.r']['contact_address'];
		$user['email'] = $_SESSION['user.r']['contact_email'];
		$user['tel'] = $_SESSION['user.r']['contact_phone'];
	}

	if(!empty($user['id']) && !empty($prod)) {
		$order_id = submit_order($user['id']);
	}

	(int) $price = str_replace('.', '', $prod['price']);

	$mch_code = 'orbitumzg';
	$signature = md5($mch_code . $order_id . $price . 'EUR' . 'completion=0,retry_count=5' . 'kewertij63');
	$url = ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.orbitum-shop';
	$site_url = ORBX_SITE_URL;

return <<<TXT

<script type="text/javascript"><!-- // --><![CDATA[
function checkEmptyField()
{
	var el;

	el = $('description');
	if(empty(el.value)){
		alert('Please enter installation website');
		el.focus();
		return false;
	}

	el = $('customer_name');
	if(empty(el.value)){
		alert('Please enter your first name');
		el.focus();
		return false;
	}

	el = $('customer_surname');
	if(empty(el.value)){
		alert('Please enter your surname');
		el.focus();
		return false;
	}

	el = $('customer_email');
	if(empty(el.value)){
		alert('Please enter e-mail');
		el.focus();
		return false;
	}

	return true;
}

// ]]></script>

<form action="https://secure.ouroboros.hr/payment/auth.php" method="post" onsubmit="javascript: return checkEmptyField();">
<input type="hidden" name="mch_code" value="{$mch_code}" />
<input type="hidden" name="order_id" value="{$order_id}" />
<input type="hidden" name="amount" value="{$price}" />
<input type="hidden" name="currency" value="EUR" />
<input type="hidden" name="return_url" value="{$url}&finish" />
<input type="hidden" name="cancel_url" value="{$url}" />
<input type="hidden" name="disp_currencies" value="EUR+" />
<input type="hidden" name="payment_policy" value="completion=0,retry_count=5" />
<input type="hidden" name="lang" value="en" />
<input type="hidden" name="signature" value="{$signature}" />
<fieldset>
	<legend><strong>Your order</strong></legend>
	<strong>Order ID:</strong> {$order_id}<br />
	<strong>Product:</strong> {$prod['product']}<br />
	<strong>Price:</strong> EUR {$prod['price']}
</fieldset>
<table>
  <caption>Customer data</caption>
    <tr>
    <td><label for="description">Installation website (or IP address) <span class="red">*</span></label></td>
    <td><input type="text" id="description" name="description" value="" /></td>
  </tr>
  <tr>
    <td><label for="customer_name">First name <span class="red">*</span></label></td>
    <td><input type="text" id="customer_name" name="customer_name" value="{$user['first_name']}" /></td>
  </tr>
  <tr>
    <td><label for="customer_surname">Last name <span class="red">*</span></label></td>
    <td><input type="text" id="customer_surname" name="customer_surname" value="{$user['last_name']}" /></td>
  </tr>
  <tr>
    <td><label for="customer_address">Address</label></td>
    <td><input type="text" id="customer_address" name="customer_address" value="{$user['address']}" /></td>
  </tr>
  <tr>
    <td><label for="customer_zip">Zip code</label></td>
    <td><input type="text" id="customer_zip" name="customer_zip" value="{$user['zip']}" /></td>
  </tr>
  <tr>
    <td><label for="customer_city">City</label></td>
    <td><input type="text" id="customer_city" name="customer_city" value="{$user['city']}" /></td>
  </tr>
  <tr>
  	<td><label for="customer_country">Country</label></td>
  	<td><select id="customer_country" name="customer_country" style="width: 180px; overflow:hidden;">
<option label="" value=""></option>
<option label="U.S.A." value="us">U.S.A.</option>
<option label="United Kingdom" value="uk">United Kingdom</option>
<option label="Afghanistan" value="af">Afghanistan</option>

<option label="Albania" value="al">Albania</option>
<option label="Algeria" value="dz">Algeria</option>
<option label="American Samoa" value="as">American Samoa</option>
<option label="Andorra" value="ad">Andorra</option>
<option label="Angola" value="ao">Angola</option>
<option label="Anguilla" value="ai">Anguilla</option>
<option label="Antarctica" value="aq">Antarctica</option>
<option label="Antigua and Barbuda" value="ag">Antigua and Barbuda</option>
<option label="Argentina" value="ar">Argentina</option>

<option label="Armenia" value="am">Armenia</option>
<option label="Aruba" value="aw">Aruba</option>
<option label="Australia" value="au">Australia</option>
<option label="Austria" value="at">Austria</option>
<option label="Azerbaijan" value="az">Azerbaijan</option>
<option label="Bahamas" value="bs">Bahamas</option>
<option label="Bahrain" value="bh">Bahrain</option>
<option label="Bangladesh" value="bd">Bangladesh</option>
<option label="Barbados" value="bb">Barbados</option>

<option label="Belarus" value="by">Belarus</option>
<option label="Belgium" value="be">Belgium</option>
<option label="Belize" value="bz">Belize</option>
<option label="Benin" value="bj">Benin</option>
<option label="Bermuda" value="bm">Bermuda</option>
<option label="Bhutan" value="bt">Bhutan</option>
<option label="Bolivia" value="bo">Bolivia</option>
<option label="Bosnia and Herzegovina" value="ba">Bosnia and Herzegovina</option>
<option label="Botswana" value="bw">Botswana</option>

<option label="Brazil" value="br">Brazil</option>
<option label="British Indian Ocean Territory" value="io">British Indian Ocean Territory</option>
<option label="Brunei Darussalam" value="bn">Brunei Darussalam</option>
<option label="Bulgaria" value="bg">Bulgaria</option>
<option label="Burkina Faso" value="bf">Burkina Faso</option>
<option label="Burma (Myanmar)" value="mm">Burma (Myanmar)</option>
<option label="Burundi" value="bi">Burundi</option>
<option label="Cambodia" value="kh">Cambodia</option>
<option label="Cameroon" value="cm">Cameroon</option>

<option label="Canada" value="ca">Canada</option>
<option label="Cape Verde" value="cv">Cape Verde</option>
<option label="Cayman Islands" value="ky">Cayman Islands</option>
<option label="Central African Republic" value="cf">Central African Republic</option>
<option label="Chad" value="td">Chad</option>
<option label="Chile" value="cl">Chile</option>
<option label="China" value="cn">China</option>
<option label="Christmas Island" value="cx">Christmas Island</option>
<option label="Cocos (Keeling) Islands" value="cc">Cocos (Keeling) Islands</option>

<option label="Colombia" value="co">Colombia</option>
<option label="Comoros" value="km">Comoros</option>
<option label="Congo, Democratic Republic of the" value="cd">Congo, Democratic Republic of the</option>
<option label="Congo, Republic of the" value="cg">Congo, Republic of the</option>
<option label="Cook Islands" value="ck">Cook Islands</option>
<option label="Costa Rica" value="cr">Costa Rica</option>
<option label="Ivory Coast (Cote D'Ivoire)" value="ci">Ivory Coast (Cote D'Ivoire)</option>
<option label="Croatia" value="hr">Croatia</option>
<option label="Cuba" value="cu">Cuba</option>

<option label="Cyprus" value="cy">Cyprus</option>
<option label="Czech Republic" value="cz">Czech Republic</option>
<option label="Denmark" value="dk">Denmark</option>
<option label="Djibouti" value="dj">Djibouti</option>
<option label="Dominica" value="dm">Dominica</option>
<option label="Dominican Republic" value="do">Dominican Republic</option>
<option label="East Timor" value="tp">East Timor</option>
<option label="Ecuador" value="ec">Ecuador</option>
<option label="Egypt" value="eg">Egypt</option>

<option label="El Salvador" value="sv">El Salvador</option>
<option label="Eritrea" value="er">Eritrea</option>
<option label="Estonia" value="ee">Estonia</option>
<option label="Ethiopia" value="et">Ethiopia</option>
<option label="Falkland Islands" value="fk">Falkland Islands</option>
<option label="Faroe Islands" value="fo">Faroe Islands</option>
<option label="Fiji" value="fj">Fiji</option>
<option label="Finland" value="fi">Finland</option>
<option label="France" value="fr">France</option>

<option label="French Guiana" value="gf">French Guiana</option>
<option label="French Polynesia" value="pf">French Polynesia</option>
<option label="French Southern and Antarctic Lands" value="tf">French Southern and Antarctic Lands</option>
<option label="Gabon" value="ga">Gabon</option>
<option label="Gambia" value="gm">Gambia</option>
<option label="Georgia" value="ge">Georgia</option>
<option label="Germany" value="de">Germany</option>
<option label="Ghana" value="gh">Ghana</option>
<option label="Gibraltar" value="gi">Gibraltar</option>

<option label="Greece" value="gr">Greece</option>
<option label="Greenland" value="gl">Greenland</option>
<option label="Grenada" value="gd">Grenada</option>
<option label="Guadeloupe" value="gp">Guadeloupe</option>
<option label="Guam" value="gu">Guam</option>
<option label="Guatemala" value="gt">Guatemala</option>
<option label="Guernsey" value="gg">Guernsey</option>
<option label="Guinea" value="gn">Guinea</option>
<option label="Guinea Bissau" value="gw">Guinea Bissau</option>

<option label="Guyana" value="gy">Guyana</option>
<option label="Haiti" value="ht">Haiti</option>
<option label="Honduras" value="hn">Honduras</option>
<option label="Hong Kong" value="hk">Hong Kong</option>
<option label="Hungary" value="hu">Hungary</option>
<option label="Iceland" value="is">Iceland</option>
<option label="India" value="in">India</option>
<option label="Indonesia" value="id">Indonesia</option>
<option label="Iran" value="ir">Iran</option>

<option label="Iraq" value="iq">Iraq</option>
<option label="Ireland" value="ie">Ireland</option>
<option label="Isle of Man" value="im">Isle of Man</option>
<option label="Israel" value="il">Israel</option>
<option label="Italy" value="it">Italy</option>
<option label="Jamaica" value="jm">Jamaica</option>
<option label="Japan" value="jp">Japan</option>
<option label="Jersey" value="je">Jersey</option>
<option label="Jordan" value="jo">Jordan</option>

<option label="Kazakhstan" value="kz">Kazakhstan</option>
<option label="Kenya" value="ke">Kenya</option>
<option label="Kiribati" value="ki">Kiribati</option>
<option label="Kuwait" value="kw">Kuwait</option>
<option label="Kyrgyz Republic (Kyrgyzstan)" value="kg">Kyrgyz Republic (Kyrgyzstan)</option>
<option label="Laos" value="la">Laos</option>
<option label="Latvia" value="lv">Latvia</option>
<option label="Lebanon" value="lb">Lebanon</option>
<option label="Lesotho" value="ls">Lesotho</option>

<option label="Liberia" value="lr">Liberia</option>
<option label="Libya" value="ly">Libya</option>
<option label="Liechtenstein" value="li">Liechtenstein</option>
<option label="Lithuania" value="lt">Lithuania</option>
<option label="Luxembourg" value="lu">Luxembourg</option>
<option label="Macau" value="mo">Macau</option>
<option label="Macedonia" value="mk">Macedonia</option>
<option label="Madagascar" value="mg">Madagascar</option>
<option label="Malawi" value="mw">Malawi</option>

<option label="Malaysia" value="my">Malaysia</option>
<option label="Maldives" value="mv">Maldives</option>
<option label="Mali" value="ml">Mali</option>
<option label="Malta" value="mt">Malta</option>
<option label="Marshall Islands" value="mh">Marshall Islands</option>
<option label="Martinique" value="mq">Martinique</option>
<option label="Mauritania" value="mr">Mauritania</option>
<option label="Mauritius" value="mu">Mauritius</option>
<option label="Mayotte" value="yt">Mayotte</option>

<option label="Mexico" value="mx">Mexico</option>
<option label="Micronesia" value="fm">Micronesia</option>
<option label="Moldova" value="md">Moldova</option>
<option label="Monaco" value="mc">Monaco</option>
<option label="Mongolia" value="mn">Mongolia</option>
<option label="Montserrat" value="ms">Montserrat</option>
<option label="Morocco" value="ma">Morocco</option>
<option label="Mozambique" value="mz">Mozambique</option>
<option label="Namibia" value="na">Namibia</option>

<option label="Nauru" value="nr">Nauru</option>
<option label="Nepal" value="np">Nepal</option>
<option label="Netherlands" value="nl">Netherlands</option>
<option label="Netherlands Antilles" value="an">Netherlands Antilles</option>
<option label="New Caledonia" value="nc">New Caledonia</option>
<option label="New Zealand" value="nz">New Zealand</option>
<option label="Nicaragua" value="ni">Nicaragua</option>
<option label="Niger" value="ne">Niger</option>
<option label="Nigeria" value="ng">Nigeria</option>

<option label="Niue" value="nu">Niue</option>
<option label="Norfolk Island" value="nf">Norfolk Island</option>
<option label="North Korea" value="kp">North Korea</option>
<option label="Northern Mariana Islands" value="mp">Northern Mariana Islands</option>
<option label="Norway" value="no">Norway</option>
<option label="Oman" value="om">Oman</option>
<option label="Pakistan" value="pk">Pakistan</option>
<option label="Palau" value="pw">Palau</option>
<option label="Panama" value="pa">Panama</option>

<option label="Papua New Guinea" value="pg">Papua New Guinea</option>
<option label="Paraguay" value="py">Paraguay</option>
<option label="Peru" value="pe">Peru</option>
<option label="Philippines" value="ph">Philippines</option>
<option label="Pitcairn Island" value="pn">Pitcairn Island</option>
<option label="Poland" value="pl">Poland</option>
<option label="Portugal" value="pt">Portugal</option>
<option label="Puerto Rico" value="pr">Puerto Rico</option>
<option label="Qatar" value="qa">Qatar</option>

<option label="Reunion" value="re">Reunion</option>
<option label="Romania" value="ro">Romania</option>
<option label="Russian Federation" value="ru">Russian Federation</option>
<option label="Rwanda" value="rw">Rwanda</option>
<option label="Saint Helena" value="sh">Saint Helena</option>
<option label="Saint Kitts and Nevis Anguilla" value="kn">Saint Kitts and Nevis Anguilla</option>
<option label="Saint Lucia" value="lc">Saint Lucia</option>
<option label="Saint Pierre and Miquelon" value="pm">Saint Pierre and Miquelon</option>
<option label="Saint Vincent and The Grenadines" value="vc">Saint Vincent and The Grenadines</option>

<option label="Samoa" value="ws">Samoa</option>
<option label="San Marino" value="sm">San Marino</option>
<option label="Saint Tome (Sao Tome) and Principe" value="st">Saint Tome (Sao Tome) and Principe</option>
<option label="Saudi Arabia" value="sa">Saudi Arabia</option>
<option label="Senegal" value="sn">Senegal</option>
<option label="Serbia and Montenegro" value="yu">Serbia and Montenegro</option>
<option label="Seychelles" value="sc">Seychelles</option>
<option label="Sierra Leone" value="sl">Sierra Leone</option>
<option label="Singapore" value="sg">Singapore</option>

<option label="Slovakia" value="sk">Slovakia</option>
<option label="Slovenia" value="si">Slovenia</option>
<option label="Solomon Islands" value="sb">Solomon Islands</option>
<option label="Somalia" value="so">Somalia</option>
<option label="South Africa" value="za">South Africa</option>
<option label="South Georgia and the South Sandwich Islands" value="gs">South Georgia and the South Sandwich Islands</option>
<option label="South Korea" value="kr">South Korea</option>
<option label="Spain" value="es">Spain</option>
<option label="Sri Lanka" value="lk">Sri Lanka</option>

<option label="Sudan" value="sd">Sudan</option>
<option label="Suriname" value="sr">Suriname</option>
<option label="Svalbard and Jan Mayen Islands" value="sj">Svalbard and Jan Mayen Islands</option>
<option label="Swaziland" value="sz">Swaziland</option>
<option label="Sweden" value="se">Sweden</option>
<option label="Switzerland" value="ch">Switzerland</option>
<option label="Syria" value="sy">Syria</option>
<option label="Taiwan" value="tw">Taiwan</option>
<option label="Tajikistan" value="tj">Tajikistan</option>

<option label="Tanzania" value="tz">Tanzania</option>
<option label="Thailand" value="th">Thailand</option>
<option label="Togo" value="tg">Togo</option>
<option label="Tokelau" value="tk">Tokelau</option>
<option label="Tonga" value="to">Tonga</option>
<option label="Trinidad and Tobago" value="tt">Trinidad and Tobago</option>
<option label="Tunisia" value="tn">Tunisia</option>
<option label="Turkey" value="tr">Turkey</option>
<option label="Turkmenistan" value="tm">Turkmenistan</option>

<option label="Turks and Caicos Islands" value="tc">Turks and Caicos Islands</option>
<option label="Tuvalu" value="tv">Tuvalu</option>
<option label="Uganda" value="ug">Uganda</option>
<option label="Ukraine" value="ua">Ukraine</option>
<option label="United Arab Emirates" value="ae">United Arab Emirates</option>
<option label="Uruguay" value="uy">Uruguay</option>
<option label="Uzbekistan" value="uz">Uzbekistan</option>
<option label="Vanuatu" value="vu">Vanuatu</option>
<option label="Vatican City" value="va">Vatican City</option>

<option label="Venezuela" value="ve">Venezuela</option>
<option label="Vietnam" value="vn">Vietnam</option>
<option label="Virgin Islands (UK)" value="vg">Virgin Islands (UK)</option>
<option label="Virgin Islands (USA)" value="vi">Virgin Islands (USA)</option>
<option label="Wallis and Futuna Islands" value="wf">Wallis and Futuna Islands</option>
<option label="Yemen" value="ye">Yemen</option>
<option label="Zambia" value="zm">Zambia</option>
<option label="Zimbabwe" value="zw">Zimbabwe</option>
<option label="Equatorial Guinea" value="gq">Equatorial Guinea</option>

<option label="Western Sahara" value="eh">Western Sahara</option>
<option label="United States Minor Outlying Islands" value="um">United States Minor Outlying Islands</option>
  </select></td>
  </tr>
  <tr>
    <td><label for="customer_telephone">Phone</label></td>
    <td><input type="text" id="customer_telephone" name="customer_telephone" value="{$user['tel']}" /></td>
  </tr>
  <tr>
    <td><label for="customer_email">E-mail <span class="red">*</span></label></td>
    <td><input type="text" id="customer_email" name="customer_email" value="{$user['email']}" /></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="Submit" value="Proceed &raquo;" /></td>
  </tr>
</table>
<p>&nbsp;</p>
<p><img src="{$site_url}/orbicon/modules/orbitum-shop/gfx/opg.gif" alt="Ouroboros" title="Ouroboros" /></p>
<p><img src="{$site_url}/orbicon/modules/orbitum-shop/gfx/cards.gif" alt="Credit Cards" title="Credit Cards" /></p>
</form>
TXT;

?>