<?php
include("settings.php");
$event=$_GET["event"];

?>


<div style="float: left; width: 10%;">


    <br />
<br /><a href="<?php echo "?hr=orbicon/mod/estate&amp;event=add";?>">Dodaj nekretninu </a>


       <br /> <br />
    	<a href="<?php echo "?hr=orbicon/mod/estate&amp;event=view";?>">Pregledaj nekretnine</a><br /><br />


</div>

<div style="float: left; margin-top: 0pt; margin-right: 0pt; margin-bottom: 0pt;  width: 100%;">
<?php
//ovjde počinje dio koji sadrži cijeli kôd za dodavanje u baz admin.ad.php
if ($event=="add")
    {
			if (!isset($_POST['Submit']))

			{	$values = $dbc->_db->fetch_array($resultv);
		?>
<form method="post" action="" >
<div style="float:left; margin-left:10px; width: 300px">
			<p>
			<label>Naslov<br />
			<input name="title" type="text" id="title" size="45"/>
			</label>
			</p>

			<p>
			  <label>Opis<br />
			  <textarea name="text" rows="9" id="text"  cols="45"></textarea>
			</label>
			</p>
			 			 <p>
			 <label>Adresa<br />
			 <input name="address" type="text" id="address" size="45"/>
			 </label>
			 </p>

			<p>
			  <label>Lokacija<br />
			  <input name="location" type="text" id="location" size="45"/>
			  </label>
			</p>

			<p>
			  <label>Tip nekretnine <br />
			  <select name="type" id="type">
			    <option value="zemljiste">Zemljište</option>
			    <option value="kuca">Kuća</option>
			    <option value="stan" selected="selected">Stan</option>
			    <option value="apartman">Apartmansko naselje</option>
			  </select>
			  </label>
			</p>

			<p>
			  <label>Kvadratura (m2)<br />
			  <input name="msquare" type="text" id="msquare" size="45"/>
			  </label>
			</p>

			<p>
			  <label>Cijena (&euro;/m2)<br />
			  <input name="price" type="text" id="price" size="45"/>
			  </label>
			</p>

			<div id="news_image" style="padding: 3px;overflow:auto; height: auto; width: auto;background:#ffffff;border:1px solid #cccccc;"></div>
			 	<input id="news_img" name="news_img" type="hidden" />
	 	<br />
			</div>
            <div style="border-left-style:dashed;border-left-width:2px;float:left;padding-left:10px;">
			<p>
			<label>Opis slike<br />
			<input type="text" name="tag_title" size="45" value="<?php echo $values['title']?>"/>
			</label>
			</p>
			<p>
			<label>
			ALT-keyword<br />
			<input name="img_alt" type="text" id="img_alt" value="slika" size="45"/>
			</label>
			</p>
			<p>
			<label>Naziv slike <br />
			<input type="text" name="img_title" id="img_title" size="45"  value="<?php echo $values['title']?>"/>
			</label>
			</p>

			<p>
			<label>CSS style <br />
			<input name="img_css_id" type="text" value="frontimg" size="45"/>
			</label>
			</p>

			<p>
			<label>Galerija slika (uključiti galeriju ako nekretnina ima više slika)</label><br  />
				<select id="image_categories" name="image_categories" onblur="javascript: orbx_carrier(this, document.column_form.image_categories);" onchange="javascript: orbx_carrier(this, document.column_form.image_categories);">
				<option value="" selected="selected">&mdash;</option>
				<optgroup label="<?php echo _L('pick_a_category'); ?>">
				<?php
					require_once DOC_ROOT . '/orbicon/venus/class.venus.php';

					$venus = new Venus;

					echo $venus->get_categories($values['gallery']);
					unset($venus);

				?>
				</optgroup>
			</select>
			</p>
			<!--
			<p>
			<label>Unesi te naziv slike koja se nalazi u site/gfx folderu, <br />ova slika će biti prikazana na stranici<br />nije bitno koje su dimenzije<br />
			<input name="img_naziv" type="text" id="img_naziv" size="45" value="<?php echo $values['title']?>"/>
			</label>
			</p>
			<p>
			<label>Unesi te naziv slike koja se nalazi u site/gfx folderu, <br />ova slika će biti prikazana <br />kad se klikne na sliku koju ste unijeli iznad<br />
			<input name="img_naziv_big" type="text" id="img_naziv_big" size="45" value="<?php echo $values['title']?>"/>
			</label>
			</p>
			<p><label>Ukoliko imate još slika unesite ovdje imena <br />manjih slika na način da ih odvajate<br />
					zerezom ( slika1.jpg,slika34.gif,moja_kuca.png).<br />
					NAPOMENA : imena slika neka budu bez ž,ć,č,š,đ.<br />
			<input name="other_img"	 type="text" size="45" id="other_img"/>
			</label>
			<p/>

			  <label>Ukoliko imate još slika unesite ovdje<br /> imena manjih slika na način da ih odvajate<br />
					zerezom ( slika1.jpg,slika34.gif,moja_kuca.png). <br />
  Ova slika će biti prikazana kad se <br />klikne na sliku koju ste unijeli iznad<br />
					NAPOMENA : imena slika neka budu bez ž,ć,č,š,đ.<br />
	          <input name="other_img_big"	 type="text" size="45" id="other_img_big"/>
			  </label>
			  -->

<label><br />
			<input type="submit" name="Submit" value="submit" />
			<input type="reset" name="reset" value="reset" />
			</label>
			</div>
			</form>
			<?php
			}

				else {

			//blank array for possible errors

					$errorList = array();

			//declaring variables
					$id = $_POST['id'];
					$title = $_POST['title'];
					$text = $_POST['text'];
					$location = $_POST['location'];
					$address = $_POST['address'];
					$type = $_POST['type'];
					$price = $_POST['price'];
					$msquare = $_POST['msquare'];
					$tag_title = $_POST['tag_title'];
					$img = $_POST['img'];
					$img_alt = $_POST['img_alt'];
					$img_title = $_POST['img_title'];
					$img_css_id = $_POST['img_css_id'];
					$img_naziv = $_POST['news_img'];
					$img_naziv_big = $_POST['img_naziv_big'];
					$other_img = $_POST['other_img'];
					$other_img_big = $_POST['other_img_big'];
					$gallery = $_POST['image_categories'];





					//if there is no errors form can be submitted

				if (sizeof($errorList == 0))
				{
					$sql = sprintf(' INSERT INTO orbx_mod_estate
									(		title,
											text,
											location,
											address,
											type,
											price,
											msquare,
											img,
											tag_title,
											img_alt,
											img_title,
											img_css_id,
											img_naziv,
											img_naziv_big,
											other_img,
											other_img_big,
											gallery
									)
							VALUES
							(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
							',
							$dbc->_db->quote($title),
							$dbc->_db->quote($text),
							$dbc->_db->quote($location),
							$dbc->_db->quote($address),
							$dbc->_db->quote($type),
							$dbc->_db->quote($price),
							$dbc->_db->quote($msquare),
							$dbc->_db->quote($img),
							$dbc->_db->quote($tag_title),
							$dbc->_db->quote($img_alt),
							$dbc->_db->quote($img_title),
							$dbc->_db->quote($img_css_id),
							$dbc->_db->quote($img_naziv),
							$dbc->_db->quote($img_naziv_big),
							$dbc->_db->quote($other_img),
							$dbc->_db->quote($other_img_big),
							$dbc->_db->quote($gallery)
						);

					$r = $dbc->_db->query($sql);


						echo 'Unos je uspješno izvršen.<br \>';

				}
				//there is an error(s)
				else {
					echo 'Sljedeće greške su nađene :';
					echo  "<br />";
					echo  "<ul>";
					for ($x=0;$x<sizeof($errorList);$x++)
					{
						echo "<li>$errorList[$x]</li>";
					}

					echo "</ul>";
				}
			}
			mysql_free_result($r);

}
//završava dio za dodavanje u bazu
?>
<?php
// počinje edit

if ($event=="edit")
{			//form not submitted
		if (!$_POST['update'])
			{
				//check for record
				if (!isset($_GET['id']) || trim($_GET['id']) == '') {

					die("<br />Nedostaje ID od nekretnine!");
				}

				//generate and execute query
				$id = $_GET['id'];
				$uquery = "SELECT * FROM orbx_mod_estate WHERE id ='$id' ";
				$uresult = mysql_query($uquery);

				//result is returned
				if (mysql_num_rows($uresult) > 0)
				{
				//turn it into an object
				$urow = mysql_fetch_object($uresult);

				//print form with values
				?>

			<form method="post" action="" ><div style="float:left;">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<p>
			<label>Naslov<br />
			<input name="title" type="text" id="title" size="50" value="<?php echo $urow->title; ?>"/>
			</label>
			</p>

			<p>
			  <label>Opis<br />
			  <textarea name="text" rows="9" id="text"  cols="50"><?php echo $urow->text; ?></textarea>
			</label>
			</p>

			 <p>
			 <label>Adresa<br />
			 <input name="address" type="text" id="address" size="50" value="<?php echo $urow->address; ?>"/>
			 </label>
			 </p>

			<p>
			  <label>Lokacija<br />
			  <input name="location" type="text" id="location" size="50" value="<?php echo $urow->location; ?>"/>
			  </label>
			</p>

			<p>
			  <label>Tip nekretnine <br />
			  <select name="type" id="type">
			  <option value=""></option>
			    <option value="zemljiste">Zemljište</option>
			    <option value="kuca">Kuća</option>
			    <option value="stan">Stan</option>
			    <option value="apartman">Apartmansko naselje</option>
			  </select>
			  </label>
			</p>

			<p>
			  <label>Kvadratura (m2)<br />
			  <input name="msquare" type="text" id="msquare" size="50" value="<?php echo $urow->msquare; ?>"/>
			  </label>
			 <div id="news_image" style="padding: 3px;overflow:auto; height: auto; width: auto;background:#ffffff;border:1px solid #cccccc;"></div>
			 	<input id="news_img" name="news_img" type="text" value="<?php echo $urow->img_naziv; ?>"/><br />
			</p>

			<p>
			  <label>Cijena (&euro;/m2)<br />
			  <input name="price" type="text" id="price" size="50" value="<?php echo $urow->price; ?>"/>
			  </label>
			</p>
			</div>
            <div style="float:left; width=350px">
			<p>
			<label>Opis slike<br />
			<input type="text" name="tag_title" size="50" value="<?php echo $urow->tag_title; ?>"/>

			</label>
			</p>
			<p>
			<label>
			ALT-keyword<br />
			<input name="img_alt" type="text" id="img_alt" value="<?php echo $urow->img_alt; ?>" size="50"/>
			</label>
			</p>
			<p>
			<label>Naziv slike <br />
			<input type="text" name="img_title" id="img_title" size="50" value="<?php echo $urow->img_title; ?>" />
			</label>
			</p>

			<p>
			<label>CSS style <br />
			<input name="img_css_id" type="text" value="<?php echo $urow->img_css_id; ?>" size="50"/>
			</label>
			</p>

			<!--
			<label>Unesi te naziv slike koja se nalazi u site/gfx folderu<br />
			<input name="img_naziv" type="text" id="img_naziv" size="50" value="$urow->img_naziv; "/>
			</label>
			</p>
			<p>
			<label>Unesi te naziv slike koja se nalazi u site/gfx folderu, velika slika<br />
			<input name="img_naziv_big" type="text" id="img_naziv_big" size="50" value="$urow->img_naziv_big;" />
			</label>
			</p>
			<p>
			<label>ostale slike<br />
			<input name="other_img" type="text" id="other_img" size="50" value="$urow->other_img; " />
			</label>
			</p>
			<p>
			<label>ostale slike veće<br />
			<input name="other_img_big" type="text" id="other_img_big" size="50" value="$urow->other_img_big; "  />
			</label>
			</p>
			-->

			<p>
			<label>Galerija slika (uključiti galeriju ako nekretnina ima više slika)</label><br />
				<select id="image_categories" name="image_categories" onblur="javascript: orbx_carrier(this, document.column_form.image_categories);" onchange="javascript: orbx_carrier(this, document.column_form.image_categories);">
				<option value="" selected="selected">&mdash;</option>
				<optgroup label="<?php echo _L('pick_a_category'); ?>">
				<?php
					require_once DOC_ROOT . '/orbicon/venus/class.venus.php';

					$venus = new Venus;

					echo $venus->get_categories($values['gallery']);
					unset($venus);

				?>
				</optgroup>
			</select>
			</p>
			<label>
			<input type="submit" name="update" value="Promijeni" />
			<input type="reset" name="reset" value="reset" />
			</label>
            </div>
			</form>
			<?php
				}
				//no good result
				else {
					echo "Nema normalnog rezultata!";
				}

			}

			else
			{
					//create array for errors
					$updateErrors = array();

					$id = $_POST['id'];
					$title = $_POST['title'];
					$text = $_POST['text'];
					$location = $_POST['location'];
					$address = $_POST['address'];
					$type = $_POST['type'];
					$price = $_POST['price'];
					$msquare = $_POST['msquare'];
					$tag_title = $_POST['tag_title'];
					$img_alt = $_POST['img_alt'];
					$img_title = $_POST['img_title'];
					$img_css_id = $_POST['img_css_id'];
					$img_naziv = $_POST['news_img'];
					$img_naziv_big = $_POST['img_naziv_big'];
					$druge_img = $_POST['other_img'];
					$druge_img_big = $_POST['other_img_big'];
					$gallery = $_POST['image_categories'];


					if ((!isset($_POST['id']) || trim($_POST['id']) == ''))
					{
						die ("NEMA ID-a!!!");
					}


					 //check for errors
					 //if there is no errors continue with update

					 if (sizeof($updateErrors) == 0 )
					 {

					 	//generates update query

					 	$kveri = "UPDATE orbx_mod_estate SET

									title = '$title',
									text = '$text',
									location = '$location',
									address = '$address',
									type = '$type',
									price = '$price',
									msquare = '$msquare',
									tag_title = '$tag_title',
									img_alt = '$img_alt',
									img_title = '$img_title',
									img_css_id = '$img_css_id',
									img_naziv = '$img_naziv',
									img_naziv_big = '$img_naziv_big',
									other_img = '$druge_img',
									other_img_big ='$druge_img_big',
									gallery = '$gallery'

									WHERE id = '$id'";


					  	$kveri_sql = mysql_query($kveri) or  die(mysql_error()."broj greške ".mysql_errno());
					 	echo "Uređivanje je prošlo bez grešaka!!<br />
					 			<a href='?hr=orbicon/mod/estate&event=view'>Natrag</a>";

					 }
					 else {
					 	//errors occurred
					 		echo "<ul>";

					 		for ($y=0; $y<sizeof($updateErrors); $y++)
					 		{
					 			echo "<li>$updateErrors[$y]</li>";

					 		}
					 		echo "</ul>";
					 	}



			}
}
//završava edit
?>
<?php
if ($event=="delete")
{

if (!isset($_GET['id']) || trim($_GET['id']) == '') {

	die("NEMA unosa sa tim ID!");}

$id = $_GET['id'];
$dquery = "DELETE FROM orbx_mod_estate WHERE id = '$id' ";
$dresult = mysql_query($dquery) or die("Error :" . mysql_error());

echo "Uspješno ste obrisali nekretninu.";
}
?>
<?php
//lista
echo "<div style='float:left; width:auto;'>";
if ($event=="view")
{
$sql_queryv = "SELECT * FROM orbx_mod_estate ORDER BY `id` DESC";
$resultv = $dbc->_db->query($sql_queryv)
or die ("Error in query: $sql_query. " . mysql_error());
$num_brojv = mysql_num_rows($resultv);

echo "<h2>Trenutno ima $num_brojv zapisa u bazi!</h2><br />";

// if records present
if (mysql_num_rows($resultv) > 0)
{
// iterate through resultset
// print title with links to edit and delete scripts
echo '<table border="0"><tr>';
$i=0;
	while($fetch = mysql_fetch_assoc($resultv))
	{
	 echo "<td valign='top'><div style='float:left; margin:5px 10px 15px 10px; width:auto;'>
			<h4>" . $fetch['title'] . "</h4>
				<div style='border:1px solid #868686; padding:5px;'>";
				#<a href='?hr=orbicon/mod/estate&event=details&id=".$fetch['id']."'>
					#<img   height='93' width='124' class='" . $fetch['img_css_id'] . "' src='".ORBX_SITE_URL.'/site/venus/'.$fetch['img_naziv']."' alt='" . $fetch['img_alt']  . "' title='" . $fetch['img_title'] . "' /></a>";

										$thumb_img = (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$fetch['img_naziv'])) ? '<img class="thumb_image" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$fetch['img_naziv'].'" alt="'.$fetch['img_alt'].'" title="'.$fetch['img_title'].'" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$fetch['img_naziv'].'" class="'.$fetch['img_css_id'].'" alt="'.$fetch['img_alt'].'" title="'.$fetch['img_title'].'" />';

				#echo "<a href='?hr=orbicon/mod/estate&event=edit&id=".$fetch['id']."'>" . $thumb_img . "</a>";
				unset($thumb_img);


					/*echo '<a href="?hr=orbicon/mod/estate&event=details&id="'.$fetch['id'].'">';

					echo (is_file(DOC_ROOT . '/site/venus/thumbs/t-'.$fetch['img_naziv'])) ? '<img class="' . $fetch['img_css_id'] . '" src="'.ORBX_SITE_URL.'/site/venus/thumbs/t-'.$fetch['img_naziv'].'" alt="' . $fetch['img_alt'] .'" title="'. $fetch['img_title'] .'" />' : '<img src="'.ORBX_SITE_URL.'/site/venus/'.$fetch['img_naziv'].'" class="'. $fetch['img_css_id'] .'" alt="' .$fetch['img_alt'] .'" title="'.$fetch['img_title'].' />';
					echo '</a>';
					#echo $thumb;

					#var_dump($thumb);*/

							echo '<p>Cijena: <span>' . $fetch['price'] . ' (&euro;/m2)</span></p>
							<p>Kvadratura: <span>' . $fetch['msquare'] . ' (m2)</span></p>
							<a href="?hr=orbicon/mod/estate&event=edit&id='.$fetch['id'].'">Uredi</a>
							<a href="?hr=orbicon/mod/estate&event=delete&id='.$fetch['id'].'" onmousedown="' . delete_popup($fetch['title']) . '"
    onclick="javascript:return false;">Obriši</a>
					</div></div></td>';
			$i++;
			if ($i % 4 == 0) {
				echo "</tr><tr>";
			}

	}

echo '</tr></table>';
}

}
echo "</div>";
?>
<?php
//počinje detalji
if ($event=="details")
{

//check for record
	if (!isset($_GET['id']) || trim($_GET['id']) == '')
	{
		die("<br />Nedostaje ID od nekretnine!");
	}

	//generate and execute query
	#$back.=BackButton();
	$id = $_GET['id'];
	$dquery = "SELECT * FROM orbx_mod_estate WHERE id ='$id' ";
	$dresult = $dbc->_db->query($dquery);
	while ($dresulto = mysql_fetch_object($dresult))
	{
	/*<img align='left' width='300' height='250'src='http://www.dommreza.hr/site/venus/$dresulto->img_naziv' alt='$dresulto->img_alt' title='$dresulto->img_title'/>*/
		echo "<h2>$dresulto->title</h2><br />

<table border='0'>
      <tr>
        <td><font size='2'><a href='?hr=orbicon/mod/estate&event=edit&id=$dresulto->id'>Uredi</a></font></td>
        <td>$back</td>
        <td><font size='2'> <a href='?hr=orbicon/mod/estate&event=delete&id=$dresulto->id'>Obriši</a></font></td>
      </tr>
    </table>";
	}

}
?>
</div>
