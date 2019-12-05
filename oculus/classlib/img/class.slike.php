<?php

require(LIB_INC."ip/class.ip.php");

class Slike extends IP
{
	// * Izmjeni dimenzije $sSlika filea. Ako je $sOuput isti kao i $sSlika,
	// * onda će se prebrisati $sSlika
	function ResizeImg($sSlika, $sOutput, $nNovaSirina, $nNovaVisina, $nSkaliranje = 100, $nKvaliteta = 80)
	{
		if(file_exists($sSlika))
		{
			(array) $aSlikaInfo = getimagesize($sSlika);

			if($nNovaSirina != "-") {
				(int) $nSkaliranje = (($nNovaSirina / $aSlikaInfo[0]) * 100);
			}
			else if(($nNovaVisina != "-") && ($nSkaliranje == 100)) {
				(int) $nSkaliranje = (($nNovaVisina / $aSlikaInfo[1]) * 100);
			}

			if(($nSkaliranje > 0) || ($nSkaliranje < 100))
			{
				(int) $nNovaSirina = ($aSlikaInfo[0] * $nSkaliranje) / 100;
				(int) $nNovaVisina = ($aSlikaInfo[1] * $nSkaliranje) / 100;
			}
			$rNovaSlika = imagecreatetruecolor($nNovaSirina, $nNovaVisina); // Stvori praznu sliku

			if($nKvaliteta > 100) {
				$nKvaliteta = 100;
			}
			else if($nKvaliteta < 1) {
				$nKvaliteta = 1;
			}

			switch($aSlikaInfo["mime"])
			{
				case "image/jpeg":
				case "image/jpg":
					$rTempSlika = imagecreatefromjpeg($sSlika);
					imagecopyresampled($rNovaSlika, $rTempSlika, 0, 0, 0, 0, $nNovaSirina, $nNovaVisina, $aSlikaInfo[0], $aSlikaInfo[1]); // Redimenzioniraj org. JPEG
					imagejpeg($rNovaSlika, $sOutput, $nKvaliteta); 			// * Izbaci novi JPEG
				break;
				case "image/png":
					$rTempSlika = imagecreatefrompng($sSlika);
					imagecopyresampled($rNovaSlika, $rTempSlika, 0, 0, 0, 0, $nNovaSirina, $nNovaVisina, $aSlikaInfo[0], $aSlikaInfo[1]); // Redimenzioniraj org. PNG
					imagepng($rNovaSlika, $sOutput, $nKvaliteta); 			// * Izbaci novi PNG
				break;
				case "image/gif":
					$rTempSlika = imagecreatefromgif($sSlika);
					imagecopyresampled($rNovaSlika, $rTempSlika, 0, 0, 0, 0, $nNovaSirina, $nNovaVisina, $aSlikaInfo[0], $aSlikaInfo[1]); // Redimenzioniraj org. GIF
					imagegif($rNovaSlika, $sOutput, $nKvaliteta); 			// * Izbaci novi PNG
				break;
			}
			imagedestroy($rTempSlika);
		}
		else {
			$this -> AddError(__FUNCTION__." : $sSlika ne postoji");
		}
	}

	// Dobavi informacije o $sSlika
	function InfoImg($sImg, $nRet = 3)
	{
		(array) $aSlika = getimagesize($sImg);
		return $aSlika[$nRet];
	}
}

?>