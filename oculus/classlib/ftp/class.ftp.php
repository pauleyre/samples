<?php

require("h.ftp.php");

class FTP
{
	var $_sServer;
	var $_sUser;
	var $_sPassword;
	var $_sDirectory;
	var $_rConnection;
	var $_bLogin;

	function Connect($sServer, $sUser, $sPassword, $sDirectory)
	{
		// Connect to the remote FTP server and then attempt
		// to change into a remote directory. Returns FALSE
		// as well as a string in the $err reference if failed

		$this -> _sServer = $sServer;
		$this -> _sUser = $sUser;
		$this -> _sPassword = $sPassword;
		$this -> _sDirectory = $sDirectory;

		// Attempt to connect to the remote server
		$this -> _rConnection = @ftp_connect($this -> _sServer);

		if(!$this -> _rConnection)
		{
			$this -> AddError("Couldn't connect to server {$this -> _sServer}");
			return FALSE;
		}

		// Attempt to login to the remote server
		$this -> _bLogin = @ftp_login($this -> _rConnection, $this -> _sUser, $this -> _sPassword);

		if(!$this -> _bLogin)
		{
			$this -> AddError("Couldn't login as user {$this -> _sUser} to {$this -> _sServer}");
			return FALSE;
		}

		// Attempt to change into the working directory
		(bool) $bChangeDir = @ftp_chdir($this -> _rConnection, $this -> _sDirectory);

		if(!$bChangeDir)
		{
			$this -> AddError("Couldn't change into the {$this -> _sDirectory} directory");
			return FALSE;
		}

		// Everything worked OK, return true
		return TRUE;
	}

	function IsStillConnected()
	{
		// Attempt to call the ftp_systype to see if the connect
		// to the FTP server is still alive and kicking

		if(!@ftp_systype($this -> _rConnection))
		{
			// The connection is dead
			return FALSE;
		}
		else
		{
			// The connection is still alive
			return TRUE;
		}
	}

	function RetrieveDataFromRemoteFile($sFileName, $sDirectory)
	{
		// Change into the remote directory and retrieve the content
		// of a file. Once retrieve, return this value to the caller

		if(!$this -> IsStillConnected())
		{
		// Connect is dead, attempt to reconnect
		$err = "";
		@$this -> Connect($this -> _sServer, $this -> _sUser, $this -> _sPassword, $this -> _sDirectory);
	
		if($err != "")
		{
			$this -> HandleError($err);
			die();
		}
		else {
			return TRUE;
		}
	}

	// We are now connected, so let's retrieve the file contents.
	// Firstly, we change into the directory
	$chDir = @ftp_chdir($this -> _conn, $Directory);

	if(!$chDir)
	{
		$Err = "Couldn't change into directory: $Directory";
		return FALSE;
	}

	// We have changed into the directory, let's attempt to get the file
	$fp = @fopen("localfile", "wb");
	$getFile = @ftp_fget($this->__conn, $fp, $FileName, FTP_BINARY);
	fclose($fp);

	$fp = NULL;

	if(!$getFile)
	{
		$Err = "Unable to download file: $FileName from $Directory";
		return false;
	}

	// The file was downloaded successfully. Let's open it, read in its
	// contents and return it to the calling function
	
	$fp = @fopen("localfile", "rb");
	
	if(!$fp)
	{
		$Err = "Unable to open $FileName after it was downloaded from {$this->__server}";
		return false;
	}
	
	// Read in the contents of the file to a variable
	$data = "";
	
	while(!feof($fp)) {
		$data.= fread($fp, 4096);
	}
	
	@fclose($fp);
	
		// Return the HTML from the file
		return $data;
	}


function SaveDataToRemoteFile($Data, $FileName, $Directory, &$Err)
{
	// Save HTML to a remote file on the FTP server
if(!$this->IsStillConnected())
{
// Connect is dead, attempt to reconnect
$err = "";
@$this->Connect($this->__server, $this->__user, $this->__password, $this->__directory, $err);

if($err != "")
{
$this->HandleError($err);
die();
}
else
{
return true;
}
}

// We are now connected, so let's retrieve the file contents.
// Firstly, we change into the directory
$chDir = @ftp_chdir($this->__conn, $Directory);

if(!$chDir)
{
	$Err = "Couldn't change into directory: $Directory";
	return false;
}

// Save the HTML to a file which we then upload
$fp = @fopen("localfile", "wb");

if(!$fp)
{
	$Err = "Couldn't open a local file for temporary output";
	return false;
}

// The file was opened OK, let's write to it
$filePut = @fputs($fp, $Data, strlen($Data));

if(!$filePut)
{
	$Err = "Couldn't write to a local file for temporary output";
	return false;
}
else
{
@fclose($fp);
}

// Now we can try to write to the remote file
$CompleteFileName = "$Directory/$FileName";
$LocalFileName = "localfile";

$putFile = @ftp_put($this->__conn, $CompleteFileName, $LocalFileName, FTP_BINARY);

if(!$putFile)
{
	$Err = "Couldn't write to $CompleteFileName when trying to save file";
	return false;
}

// Everything worked OK
return true;
} 


	function SwitchDirectory($Directory)
	{
		// Switch to another directory on the web server. If we don't
		// have permissions then an error will occur

		if(!$this -> IsStillConnected())
		{
			// Connect is dead, attempt to reconnect
			$err = "";
			@$this -> Connect($this->__server, $this->__user, $this->__password, $this->__directory, $err);

			if($err != "")
			{
				$this->HandleError($err);
				die();
			}
			else {
				return TRUE;
			}
		}

		// Try and change into another directory
		$chDir = ftp_chdir($this->__conn, $Directory);

		if(!$chDir)
		{
			$Err = "Couldn't change into directory: $Directory";
			return FALSE;
		}
		else
		{
			// Changing directories worked OK
			return TRUE;
		}
	} 


	function GetFileListAsArray($Directory)
	{
		// This function will attempt to change into the specified
		// directory and retrieve a list of files as an associative
		// array. This list will include file name, size and date last modified
		
		$err = "";
		$fileArray = array();

		// Can we switch to the desired directory?
		if(!$this->SwitchDirectory($Directory, $err)) {
			$this->HandleError($err);
		}

		// We are in the directory, let's retrieve a list of files
		$fileList = ftp_rawlist($this->__conn, $Directory);

		// Save the list of files
		if(@is_array($fileList))
		{
			// Interate through the array
			for($i = 0; $i < sizeof($fileList); $i++)
			{
				$itemArray = explode(" ", $fileList[$i]);
	
				// Are we dealing with a file or directory?
				// If the first letter of the attributes is
				// "d" then we are dealing with a directory
				$attributes = $itemArray[0];

				if(substr($attributes, 0, 1) == "d")
					$fileType = FT_DIRECTORY;
				else
					$fileType = FT_FILE;

				// Get the file/directory name
				$fileName = $itemArray[sizeof($itemArray)-1];
				
				// Get the size of the file
				$fileSize = $itemArray[sizeof($itemArray)-5];

				if(!is_numeric($fileSize))
				$fileSize = $itemArray[sizeof($itemArray)-6];

				// Get the date last modified
				$fileTimeStamp = $itemArray[sizeof($itemArray)-4]." ".$itemArray[sizeof($itemArray)-3]." ".$itemArray[sizeof($itemArray)-2];
				
				$fileArray[] = array("type" => $fileType,
				"filename" => $fileName,
				"filesize" => $fileSize,
				"filetime" => $fileTimeStamp);
}

sort($fileArray);
return $fileArray;
}
else
{
	$Err = "No files in directory";
	return -1;
}
} 


function CreateDirectory($folderName)
{
// Makes a new folder on the web server via FTP

if(!$this->IsStillConnected())
{
// Connect is dead, attempt to reconnect
$err = "";
@$this->Connect($this->_server, $this->_user, $this->_password, $this->_directory);

	if($err != "")
	{
		$this->HandleError($err);
		die();
	}
	else {
		return true;
	}
}

$createResult = @ftp_mkdir($this->_conn, $folderName);

if($createResult == true)
{
// Can we change the files permissions?
$execResult = @ftp_site($this->__conn, 'chmod 0777 ' . $folderName . '/');

if($execResult == true)
{
	return true;
}
else
{
	$Err = "Couldn't set owner permissions on $folderName";
	return false;
}
}
else
{
	$Err = "Couldn't create new folder $folderName";
	return false;
}
}

function RenameFile($OldFileName, $NewFileName, $IsFolder)
{
// Rename a file/directory on the FTP server
global $php_errormsg;

if(!$this->IsStillConnected())
{
// Connect is dead, attempt to reconnect
$err = "";
@$this->Connect($this->__server, $this->__user, $this->__password, $this->__directory, $err);

if($err != "")
{
	$this->HandleError($err);
	die();
}
else
{
	return true;
}
}

$renameResult = @ftp_rename($this->__conn, $OldFileName, $NewFileName);

if($renameResult == true)
{
	// The file/folder was renamed successfully
	return true;
}
else
{
// Couldn't rename the file/folder
if($IsFolder == 0)
	$Err = "Couldn't rename the selected folder: " . @$php_errormsg;
else
	$Err = "Couldn't rename the selected file: " . @$php_errormsg;

return false;
}
} 

function DeleteFile($FileName)
{
// Remove the specified file from the FTP server
if(!$this->IsStillConnected())
{
// Connect is dead, attempt to reconnect
$err = "";
@$this->Connect($this->__server, $this->__user, $this->__password, $this->__directory, $err);

if($err != "")
{
	$this->HandleError($err);
	die();
}
else
{
	return true;
}
}

$deleteResult = @ftp_delete($this->__conn, $FileName);

if($deleteResult)
{
	// The file/folder was renamed successfully
	return TRUE;
}
else
{
	// Couldn't delete the selected file
	$Err = "Couldn't delete the selected file: " . @$php_errormsg;
	return FALSE;
}
} 

	function DeleteFolder($FolderName)
	{
		// Remove the specified folder and all subdirectories/files from the FTP server
		global $php_errormsg;

		if(!$this->IsStillConnected())
		{
			// Connect is dead, attempt to reconnect
			$err = "";
			@$this->Connect($this->__server, $this->__user, $this->__password, $this->__directory, $err);
			
			if($err != "")
			{
				$this->HandleError($err);
				die();
			}
			else
			{
			return TRUE;
			}
		}

@ftp_chdir($this->__conn, $FolderName);
$location = @ftp_pwd($this->__conn);

$directories = array();
$files = array();
$dir_counter = 0;
$file_counter = 0;
$content = @ftp_nlist($this->__conn, ".");

for ($i = 0; $i < sizeof($content); $i++)
{
// If we can change into this then it's a directory.
// If not, it's a file
if($content[$i] != "." && $content[$i] != "..")
{
if(@ftp_chdir($this->__conn, $content[$i]))
{
// We have a directory
$directories[] = $content[$i];
$dir_counter++;
@ftp_cdup($this->__conn);
}
else
{
// We have a file
$files[] = $content[$i];
$file_counter++;
}
}
}

for ($j = 0; $j < $file_counter; $j ++) {
	@ftp_delete($this->__conn, $files[$j]);
}

for ($j = 0; $j < $dir_counter; $j++)
{
if($directories[$j] != "." || $directories[$j] != "..")
{
$location = ftp_pwd ($this->__conn);
$this -> DeleteFolder($directories[$j], $this->__dummyError);
@ftp_cdup ($this->__conn);
@ftp_rmdir($this->__conn,$directories[$j]);
}
}

// Lastly, we change into the directory that we want to delete and see
// if we can cdup. If we can, we delete it.
@ftp_chdir($this->__conn, $FolderName);
@ftp_cdup($this->__conn);
@ftp_rmdir($this->__conn, $FolderName);

// Did the recursive folder/file deletion work?
return TRUE;

/*
if(@$php_errormsg == "")
{
return true;
}
else
{
$Err = "Couldn't recursive delete folder: " . @$php_errormsg;
return false;
}
*/
}

	function DoesFileExist($FolderName, $FileName)
	{
		// Does the specified file exist on the remote FTP server?
		// Returns false on error, true on file exists and 2 if it doesn't exist

		global $php_errormsg;

		if(!$this->IsStillConnected())
		{
			// Connect is dead, attempt to reconnect
			$err = "";
			@$this -> Connect($this->__server, $this->__user, $this -> __password, $this->__directory);

			if($err != "")
			{
				$this -> HandleError($err);
				die();
			}
			else {
				return TRUE;
			}
		}

		if(!@ftp_chdir($this->__conn, $FolderName))
		{
			$Err = $php_errormsg;
			return FALSE;
		}
		else
		{
			// We have changed into the directory, let's get a list
			// of files using ftp_nlist and compare it to see if it exists
			$fileArray = @ftp_nlist($this->__conn, $FolderName);

			if(!is_array($fileArray))
			{
				$Err = $php_errormsg;
				return FALSE;
			}
			else
			{
				// Loop through each file and check it if exists
				for($i = 0; $i < sizeof($fileArray); $i ++)
				{
					if($fileArray[$i] == "$FolderName/$FileName") {
						return TRUE;
					}
				}
			
				// The file wasn't found, return 2 for not found
				return 2;
			}
		}
	}
}

?>