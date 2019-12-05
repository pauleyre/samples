<?php

$event=$_GET["event"];
$sql_query = "SELECT * FROM orbx_mod_estate";
$result = mysql_query($sql_query) or die ("Error in query: $sql_query. " . mysql_error());

		if (!$event=="detalj")
			{

	
// if records present make a list of all items in database
		if (mysql_num_rows($result) > 0)
		{
		// iterate through resultset
		// print title with links to edit and delete scripts
			while($row = mysql_fetch_assoc($result))
			{
			$show_l .= "<h6>".ucfirst($row['title'])."</h6>
		<div id='show_list'>
		<span>Adresa : ".ucwords($row['address'])." </span>
		<br/>
		<span>Tip nekretnine : ".ucfirst($row['type'])." </span>
		<br/>
		<span>Cijena : ".$row['price']." €/m<sup>2</sup></span>
		<br/>
		<span>Kvadratura : ".$row['msquare']." m<sup>2</sup><span>
		<br />
		<a href='?hr=mod.ponuda&event=detalj&id=".$row['id']."'>Detalji</a>
		</div>";
			}
		}
return $show_l;
			}
/////////////////////////////
//DETALJI                 ///
/////////////////////////////

else {
	
if (!isset($_GET['id']) || trim($_GET['id']) == '') 
{
	
	die("NEMA unosa sa tim ID!");
}

$id = $_GET['id'];
$query =mysql_query("SELECT * FROM orbx_mod_estate WHERE id = '$id' ");

$rezultat = mysql_fetch_assoc($query);
$show_details .="<h6>".ucfirst($rezultat['title'])."</h6>
<div id='show_list'>
<a href='http://www.dommreza.hr/site/gfx/".$rezultat['img_naziv_big']."' rel='lightbox' title ='".strtoupper($rezultat['tag_title'])."'>
<img src='http://www.dommreza.hr/site/gfx/".$rezultat['img_naziv']."' alt ='".strtoupper($rezultat['img_alt'])."' title = '".strtoupper($rezultat['img_title'])."' id='".$rezultat['img_css_id']."' align ='left'   height='180' width='270' /></a>
".$rezultat['text']." <br />
<span>Adresa : ".ucwords($rezultat['address'])." </span>
<br/>
<span>Tip nekretnine : ".ucfirst($rezultat['type'])." </span>
<br/>
<span>Cijena : ".$rezultat['price']." €/m<sup>2</sup></span>
<br/>
<span>Kvadratura : ".$rezultat['msquare']." m<sup>2</sup></span>
<br />
<br />
<a href='javascript:history.go(-1)' >NATRAG</a>
</div>";
return $show_details;

}
?>