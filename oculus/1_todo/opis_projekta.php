<?php


	require('class.todo.php');
	(object) $oOpis = new TODO;
	ini_set('error_display', 1);
	error_reporting(E_ALL);
	(string) $sQuery = sprintf('SELECT radni_nalog_id,klijent_id,opis,rok,projekt_naziv FROM radni_nalog WHERE id = %s', $_GET['id']);
	$oOpis -> DB_Spoji('is');
	$rResult = $oOpis -> DB_Upit($sQuery);
	(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);


	$sKlijent = sprintf('SELECT id,tvrtka FROM klijenti WHERE id = %s', $aResult['klijent_id']);
	$rKlijent = $oOpis -> DB_Upit($sKlijent);
	$aKlijent = mysql_fetch_array($rKlijent, MYSQL_ASSOC);

	$oOpis -> DB_Zatvori();

	// * add docs
	
	require('3_financije/class.financije.php');
	(object) $docs = new Financije;
	$docs -> AddProjectDoc();

?>

<div style="padding-left:1em !important;">
	<h3 style="text-transform: uppercase;"><?= $aResult['projekt_naziv']; ?></h3>
	<strong>KLIJENT: <?= $aKlijent['tvrtka']; ?></strong> <a href="?page=adresar_pregled&action=edit&id=<?= $aKlijent['id']; ?>">[ adresar &raquo;]</a><br>
	<strong>ROK:</strong> <?= strftime('%d.%m.%Y', $aResult['rok']); ?><br />
	<strong>OPIS PROJEKTA:</strong><br>
	<?= str_replace("\n", '<br />', $aResult['opis']); ?>
<fieldset>
	<legend><strong>PROJEKTNA DOKUMENTACIJA</strong></legend>
<?php

		$useApplet=0;
		
		$user_agent =$_SERVER['HTTP_USER_AGENT'];

	   
		if(stristr($user_agent,'konqueror') || stristr($user_agent,"macintosh") || stristr($user_agent,"opera"))
		{ 		
			$useApplet=1;
			echo '<applet name="Rad Upload Plus"
					archive="http://'.$_SERVER['SERVER_NAME'].'/_modals/rad/dndplus.jar"
					code="com.radinks.dnd.DNDAppletPlus"
					 MAYSCRIPT="yes"
					 id="rup_applet">';
		}
		else
		{			   
			if(strstr($user_agent,"MSIE")) { 
				echo '<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
					id="rup" name="rup"
					codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4_1-windows-i586.cab#version=1,4,1">';
					
			} else {
				echo '<object type="application/x-java-applet;version=1.4.1"
					 id="rup" name="rup">';
			} 
			echo '	<param name="archive" value="http://'.$_SERVER['SERVER_NAME'].'/_modals/rad/dndplus.jar">
				<param name="code" value="com.radinks.dnd.DNDAppletPlus" />
				<param name="name" value="Rad Upload Plus" />';
				
		}		?>

   		<param name="browse" value="1" />
		<param name="browse_button" value="1" />

   		<param name="max_upload" value="8192" />
   		<!-- size in kilobytes (takes effect only in Rad Upload Plus) -->
		<param name="message" value="http://corp.orbitum.net/_modals/upload_screens/upload.projektna_dokumentacija.php?ftype=<?=$_GET['ftype'];?>&amp;id=<?=$_GET['id'];?>" />
		<!-- edit the above line to customize the welcome message displayed. example
		value='http://www.radinks.com/upload/init.html' -->
		<param name="url" value="http://corp.orbitum.net/_modals/upload_screens/upload.projektna_dokumentacija.php?ftype=<?=$_GET['ftype'];?>&amp;id=<?=$_GET['id'];?>&amp;my_id=<?=$_SESSION['zaposlenik_id'];?>" />
		<!-- you can pass additional parameters by adding them to the url-->
		<!-- to upload to an ftp server instead of a web server, please specify a url
			 in the following format:
				ftp://username:password@ftp.myserver.com
			 replacing username, password and ftp.myserver.com with corresponding entries for your site -->
<?php
		if(isset($_SERVER['PHP_AUTH_USER']))
		{
			printf('<param name="chap" value="%s" />',
			base64_encode($_SERVER['PHP_AUTH_USER'].':'.$_SERVER['PHP_AUTH_PW']));
		}
		
		if($useApplet == 1) {
			echo '</applet>';
		}
		else {
			echo '</object>';
		}
?>		
</fieldset>
</div>