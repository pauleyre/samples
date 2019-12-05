<?php
$sql1 = mysql_query("SELECT DISTINCT type FROM orbx_mod_estate");
$sql2 = mysql_query("SELECT DISTINCT location FROM orbx_mod_estate");
$prvi_dio ="
<h5>Pretraga</h5>
<form id='search_form' method='post' action='?hr=mod.pretraga'>
			<div class='split_view'>
				<p>
					<label for='type'>Vrsta nekretnine</label><br />
					
					<select name='type' id='type'>";
							 	while ($option = mysql_fetch_object($sql1)) 
							 	{
							 		$voption .="<option value='$option->type'>".$option->type."</option>";
							 	}					
				
					$srednji_dio ="</select>
				</p>
				<p>
					<label for='location'>Lokacija</label><br />
					
					<select name='location' id='location'>";
					 while ($option1=mysql_fetch_object($sql2)) {
						$toption .="<option value='$option1->location'>".ucfirst($option1->location)."</option>";
					}
					$zadnje ="</select>
				</p>
			</div>
			<div class='split_view'>
				<p>
					<label for='from_price'>Cjenovni prag €/m&sup2;</label><br />
					<label for='from_price'>od</label> 
					<input type='text' id='from_price' name='from_price' value='0' /> 
					<label for='till_price'>do</label> 
					<input type='text' id='till_price' name='till_price' value='0' />
				</p>
				<p>
					<label for='from_size'>Veličina nekretnine(m&sup2;)</label><br />
					<label for='from_size'>od</label> 
					<input type='text' id='from_size' name='from_size' value='0' /> 
					<label for='till_size'>do</label> 
					<input type='text' id='till_size' name='till_size' value='0' />
				</p>
			</div>
			<p class='cleaner sub_btn'>Unesite vrijednosti granica i započnite pretragu</p>
			<p class='cleaner sub_btn'>
				<input type='submit' name='submit_search' id='submit_search' value='Započni pretragu' />
			</p>
	</form>";
return $prvi_dio.$voption.$srednji_dio.$toption.$zadnje;
					?>
