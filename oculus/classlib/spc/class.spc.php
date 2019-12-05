<?php

class SerialComm()
{
	function SPCWrite()
	{
		// HOW TO USE PHP TO WRITE TO YOUR SERIAL PORT: TWO METHODS
		$serproxy=true;
		if($serproxy)
		{
			// Use this code in conjunction with SERPROXY.EXE
			// (http://www.lspace.nildram.co.uk/freeware.html)
			// which converts a Serial stream to a TCP/IP stream
			$fp = fsockopen("localhost", 5331, $errno, $errstr, 30);
			if(!$fp) {
				echo "$errstr ($errno)";
			}
			else 
			{
				$e = chr(27);
				$string  = $e."A".$e."H300";
				$string .= $e."V100".$e."XL1SATO";
				$string .= $e."Q1".$e."Z";
				echo $string;
				fputs($fp, $string);
				fclose($fp);
			}
		}
		else if($com1)
		{
			// Use this code to write directly to the COM1 serial port
			// First, you want to set the mode of the port. You need to set
			// it only once; it will remain the same until you reboot.
			// Note: the backticks on the following line will execute the
			// DOS 'mode' command from within PHP
			`mode com1: BAUD=9600 PARITY=N data=8 stop=1 xon=off`;
			$fp = fopen("COM1:", "w+");
			if(!$fp) {
				echo "Uh-oh. Port not opened.";
			}
			else
			{
				$e = chr(27);
				$string  = $e."A".$e."H300";
				$string .= $e."V100".$e."XL1SATO";
				$string .= $e."Q1".$e."Z";
				echo $string;
				fputs($fp, $string);
				fclose($fp);
			}
		}
	}
}


 <?
/*
 *
 * Title: Serial port comms with PHP CLI (Windows or DOS)
 * Version: 1.0
 * Author: Tony Frame
 * Date: Sunday, 05/11/2003 - 04:27 AM
 *
*/


// *    Description / Example:
// *    
// *    Full PHP CLI script to open COM1 or COM2 on Windows and retrieve readings from a digital dial gauge. The data are logged to *.csv file on the local disk and also inserted into a remote (could be local) MySQL database. On startup the script checks for existance of the database and appropriate table - if not found it creates the database and table (as long as it has appropriate priviledges set in the MySQL server).
// *    
// *    The script has been succesfully run on Windows 2K and XP systems where it is started by the Task Scheduler at 12:00 am (ie midnight) every day.

?>

<?php  
//==============================================================================
// mitutoyo.php - version 1.00 dated 3 May 2003
//
// php 4.3.2rc1 script to read data from a Mitutoyo digital dial gauge, log 
// <time, reading, status> triples to a local ASCII CSV format log file and 
// insert same into a MySQL database server.
//
//==============================================================================

//------------------------------------------------------------------------------
// function to set DOS com port parameters. 
// Does sanity check on parity and numerical parameters and then calls the 
// DOS MODE function to set the parameters. The output from the MODE command is
// echoed to the local console. If the MODE command is not successful the 
// script exits.
//------------------------------------------------------------------------------
function set_com($com_no, $baud, $parity, $data, $stop)
{
	// check com port number is either 1 or 2
	switch ($com_no)
	{
		case 1: 	$port_name = "COM1:"; break;
		case 2: 	$port_name = "COM2:"; break;
		default : 	echo "Error: a COM port number of $com_no is not allowed. Allowed range is [1,2]\n";
					exit();
	}

	// check baud rate
	if(($baud < 1200) || ($baud > 9600 ))
	{
		echo "Error: a baud rate of $baud is not allowed. Allowed range is [1200,9600]\n";
		exit();
	}

	// check parity
	switch($parity)
	{
		case "EVEN":
		case "eVEN":
		case "Even":
		case "even":
		case "E":
		case "e":
			$parity = "E";
		break;
		case "ODD":
		case "oDD":
		case "Odd":
		case "odd":
		case "O":
		case "o":
			$parity = "O";
		break;
		case "NONE":
		case "nONE":
		case "None":
		case "none":
		case "N":
		case "n":
			$parity = "N";
		break;
		default:
			echo "Error : a parity of $parity is not allowed. Allowed settings are [EVEN, ODD, NONE]\n";
			exit();
	}

	// check data bits is either 7 or 8
	switch($data)
	{
		case 7: case 8: break;
		default :
			echo("Error: a data bits value of $data is not allowed. Allowed range is [7,8]\n");
			exit();
	}

//
// check stop bits is either 0 or 1
//
  switch ($stop) {
    case 0 :
    case 1 :
      break;
    default :
      echo("Error : a stop bits value of ".$stop." is not allowed. Allowed range is [0,1]\n");
      exit;
  }

//
// use DOS mode command to set com port parameters
//
  $cmd_str = "MODE $port_name BAUD=$baud PARITY=$parity DATA=$data STOP=$stop TO=ON OCTS=ON ODSR=OFF IDSR=OFF RTS=HS DTR=ON";
  echo("\nRunning DOS command: \n  $cmd_str ....\n");
  $output = array();
  exec($cmd_str, $output, $result);
  echo("\nOutput is : \n\n");
  print_r($output);

//
// check exit status from MODE command
//
  switch ($result)
  {
    case 0 :
      echo("\nCOM port parameters set successfully.\n");
      break;
    default :
      echo("\nError while trying to set COM port parameters - exiting.\n");
      exit;
  }
}
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// Function to open console as a binary input file, echo a prompt and read
// the input which must be terminated by a [CR][LF] ie press the [ENTER] key.
// The [CR][LF] sequence is stripped from the input that is returned to the 
// calling script.
//------------------------------------------------------------------------------
function read($prompt)
{
	// open DOS CON device ie console, display prompt and read input
	$fp = fopen("con", "rb");
	echo "\n$prompt";
	$input = fgets($fp, 255);
	fclose($fp);
	return str_replace("\r\n", "", $input);
}
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
// main segment.
// Sets initial values, configures and opens DOS com port, opens local log file,
// connects to database server and then starts the data acquisition (DAQ). The
// time stamped gauge reading and status data are written to the log file and
// inserted into the database. After completion of the DAQ, the com port, log
// file and database connections are closed. 
//------------------------------------------------------------------------------

function SPCMain()
{
	//define("DEBUG", TRUE);
	define("DEBUG", FALSE);
	$com_no = 1;
	$baud = 2400;
	$parity = "N";
	$data_bits = 8;
	$stop_bits = 1;
	$log_file_name = "_rdg.csv";
	$db_server_address = "localhost";
	//$db_server_address = "10.1.34.64;
	$db_name = "tko_geomon";
	$db_username = "tko_geomon";
	$db_password = "";
	$table_name = "mitutoyo_gauge";
	$gauge_id = 1;
	$start_time = 0; // ie any time after midnight
	$end_time = 86100; // ie 5 minutes before midnight
	//$end_time = 9100;

	// configure and open serial port
	set_com($com_no, $baud, $parity, $data_bits, $stop_bits);  
	echo "\nTrying to open serial port COM$com_no ... \n";
	$serial_port = fopen("COM$com_no", "w+b");
	if($serial_port) {
		echo "\nSuccessfully opened serial port COM$com_no.\n";
	}
	else
	{
		echo "\nError while trying to open serial port COM$com_no - exiting.\n";
		exit();
	}

	// open log file
	$log_file_name = time().$log_file_name;
	echo "\nTrying to open log file $log_file_name ... \n";
	$log_file = fopen("$log_file_name", "wb");
	if($log_file) {
		echo "\nSuccessfully opened log file $log_file_name.\n";
	}
	else
	{
		echo "\nError while trying to open log file $log_file_name - exiting.\n";
		exit();
	}

// open connection to database server
echo "\nTrying to connect to database server at $db_server_address\n   with [username]:[password] = [$db_username]:[$db_password]\n";

$db = mysql_connect($db_server_address, $db_username, $db_password) or die("\nError : " . mysql_error()."\n");
echo("\nConnected successfully to database server.\n");

//
// select database in the database server
//
echo("\nTrying to select database $db_name ...\n");  
if (!mysql_select_db($db_name, $db)) {
  echo("\nError : " . mysql_error()."\n");
  echo("\nTrying to create database $db_name ...\n");
  if (!mysql_query("CREATE DATABASE $db_name", $db)) {
    echo("\nError : " . mysql_error()."\n");
    echo("\nError trying to create database $db_name. Giving up!\n");
    exit;
  }
  else {
    echo("\nSuccessfully created database.\n");
    echo("\nRe-trying to select database $db_name\n");  
    if (!mysql_select_db($db_name, $db)) {
      echo("\nError : " . mysql_error()."\n");
      echo("\nError trying to select database $db_name. Giving up!\n");
      exit;
    }
  }
}
echo "\nSuccessfully selected database.\n";

// check tables in the database
echo "\nChecking database structure ...\n";
$table_list = mysql_list_tables($db_name, $db);
if(!$table_list)
{
	echo "\nError : " . mysql_error()."\n";
	exit();
}
else {
  if (mysql_num_rows($table_list) < 1) {
    echo("\nNo tables found in database $db_name.\n\nCreating new table $table_name ...\n");
    if (!mysql_query("CREATE TABLE $table_name (
                        id smallint(5) unsigned NOT NULL, 
                        rdg_time datetime NOT NULL, 
                        rdg_data float(10,3) default NULL, 
                        rdg_status varchar(10) default NULL, 
                        PRIMARY KEY  (id, rdg_time)
                      ) TYPE=MyISAM COMMENT='Mitutoyo digital dial gauge data'", $db)) {
      echo("\nError : " . mysql_error()."\n");
      echo("\nError trying to create table. Giving up!\n");
      exit();
    }
    else {
       echo "\nSuccessfully created table.\n";
    }
  }
  else
  {
		$i = 0;
    while ( ($i < mysql_num_rows($table_list)) and (mysql_tablename($table_list, $i) != $table_name) ) {
      $i = $i + 1;
    }
    if ($i < mysql_num_rows($table_list)) {
      if (mysql_tablename($table_list, $i) == $table_name) {
        echo("\nFound table $table_name.\n");
      }
      else {
        echo("\nDatabase contains tables but not $table_name. Exiting.\n");
        exit;
      }
    }
    else {
      echo("\nDatabase contains tables but not $table_name. Exiting.\n");
      exit;
    }
  }
}

//
// read data from gauge, write to log file and insert into database
//
echo("\nStarting data aquisition ...\n\n");
$last_time = time();
$time_array = localtime(time());
$elapsed_time = $time_array[0] + 60*($time_array[1] + 60*$time_array[2]);

while (($elapsed_time > $start_time) and ($elapsed_time < $end_time)) { 
//for ($ctr = 1; $ctr <= 3; $ctr++) {
  
  $time_array = localtime(time());
  $elapsed_time = $time_array[0] + 60*($time_array[1] + 60*$time_array[2]);

  // busy wait until at least 1s elapsed
  while (time() == $last_time) ;
  $last_time = time();
  if (DEBUG) {
    echo("\nTrying to write to serial port COM$com_no... \n");
  }
  $result = fwrite($serial_port, "1");
  fflush($serial_port);
  if ($result) {
    if (DEBUG) {
      echo("\nSuccessfully wrote $result bytes to serial port COM$com_no.\n");
    }
  }
  else {
    echo("\nError while trying to write to serial port COM$com_no.\n");
  }
  if (DEBUG) {
    echo("\nTrying to read from serial port COM$com_no... \n");
  }
  $data = NULL;
  $status = str_replace(array("\r", "\n"), array('', ''), fgets($serial_port, 4));
  $rdg_time = date("Y-m-d H:i:s");
  if ($status <> "01A") {
    echo("Error in reading data. Code = $status.\n");
  }
  else {
    $data = str_replace(array("\r", "\n"), array('', ''),fgets($serial_port, 11));
    echo("$rdg_time $elapsed_time $data\n");
  }
  fwrite($log_file, $rdg_time.", $data, $status\n");
  fflush($log_file);
  $sql = "INSERT IGNORE INTO $table_name (id, rdg_time, rdg_data, rdg_status) VALUES ('$gauge_id', '$rdg_time', '$data', '$status')";
  if (DEBUG) {
    echo("\nSQL query is [$sql].\n");
  }
  if (!mysql_query($sql, $db)) {
    echo("\nError : " . mysql_error()."\n");
  }
}
echo("\nCompleted data aquisition.\n");

//
// close serial port
//
echo("\nTrying to close serial port COM$com_no ... \n");
$result = fclose($serial_port);
if ($result) {
  echo("\nSuccessfully closed serial port COM$com_no.\n");
}
else {
  echo("\nError while trying to close serial port COM$com_no.\n");
}

//
// close log file
//
echo("\nTrying to close log file $log_file_name ... \n");
$result = fclose($log_file);
if ($result) {
  echo("\nSuccessfully closed log file $log_file_name.\n");
}
else {
  echo("\nError while trying to close log file $log_file_name.\n");
}

//
// close database connection
//
echo("\nTrying to close connection to database server at $db_server_address ... \n");
$result = mysql_close($db);
if ($result) {
  echo("\nSuccessfully closed connection to database server at $db_server_address.\n");
}
else {
  echo("\nError while trying to close connection to database server at $db_server_address.\n");
}

//
// dummy read to prevent console screen closing before user has time to read messages
// only used during development / debugging - use in conjunction with for loop at line 288
// instead of while loop at line 287 in the read data section above
//
//$dump = read("Press [Enter] to continue ... ");  
//------------------------------------------------------------------------------

?> 

?>