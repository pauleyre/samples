<?php
error_reporting(0);
function read ($length='255')
{
   if (!isset ($GLOBALS['StdinPointer']))
   {
     $GLOBALS['StdinPointer'] = fopen ("php://stdin","r");
   }
   $line = fgets ($GLOBALS['StdinPointer'],$length);
   return trim ($line);
}

// then

echo "Enter your name: ";
$name = read ();
if(strtolower($name) == 'slaven') {
echo "\nHello Mr. Slaven Petric! I am RedHat Linux and you are my owner. Where do we live?\n ";	
}
else {
echo "\nHello $name! Where you came from? ";
}
$where = read ();
echo "\nI see. $where is a very good place. Bye!";
?>