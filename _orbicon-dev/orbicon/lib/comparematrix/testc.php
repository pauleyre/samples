<?php

require 'class.comparematrix.php';

$matrix = new CompareMatrix();
$matrix->add_item('A', array('id' => 1, 'price' => 140.99));
$matrix->add_item('B', array('id' => 2, 'price' => 156.99));
$matrix->add_item('C', array('id' => 3, 'price' => 14.99));
$matrix->add_item('D', array('id' => 4, 'price' => 15.99));
$matrix->add_item('E', array('id' => 5, 'price' => 16.99));
$matrix->add_item('F', array('id' => 6, 'price' => 56.99));

echo $matrix;

?>