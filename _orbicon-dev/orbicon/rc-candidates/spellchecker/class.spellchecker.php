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
class Spellcheck
{
	var $personal_path = '/path/to/personal_dict/';
	var $skip_len = 3;
	var $mode = PSPELL_NORMAL;

	var $pspell_handle;
	var $pspell_cfg_handle;
	var $personal = FALSE;
	
	function spell_checker($dict = 'en', $pconfig = '') {

      $pspell_cfg_handle = pspell_config_create($dict);

      pspell_config_ignore($pspell_cfg_handle,$skip_len);
      pspell_config_mode($pspell_cfg_handle, $mode);

      if($pconfig != '')
	  {
         $pspell_handle = pspell_config_personal($pspell_cfg_handle, $personal_path.$pconfig.".pws");
         $personal = true;
      }

      $pspell_handle = pspell_new_config($pspell_cfg_handle);
   }
   
   function check($word)
   {
      return pspell_check($this->pspell_handle, $word);
   }

   function suggest($word)
   {
      return pspell_suggest($this->pspell_handle, $word);
   }

   function add($word)
   {
      if(!$personal) return FALSE;

      return pspell_add_to_personal($this->pspell_handle, $word);
   }

	function close()
	{
		if(!$personal) return;
	
		return pspell_save_wordlist($this->pspell_handle);
	}
}

?>

<?php

   $spell_chk = new spell_checker("en", "zend-john");

   $spell_chk->add('ttest');

   $mystr = "This is a ttest of a mispellled word";

   $words = explode(' ', $mystr);

   foreach($words as $val) {

      if($spell_chk->check($val)) {

         echo "The word '$val' is spelled correctly<BR>";
      } else {

         echo "The word '$val' was not spelled correctly<BR>";
         echo "Possible correct spellings are: ";

         foreach($spell_chk->suggest($val) as $suggestion) {

            echo ' '.$suggestion;

         }

         echo "<BR>";

      }

    }

    $spell_chk->close();

?> 