<?php

include 'inc.ip.php';

var_dump(valid_public_ip('192.168.1.18'));
var_dump(cidr_match('10.0.50.255', '10.0.50.0/24'));
var_dump(network_match('10.0.50.20', '10.0.50.10-10.0.50.20'));
?>