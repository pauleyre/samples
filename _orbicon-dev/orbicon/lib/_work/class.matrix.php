<?php
// A Library of Matrix Math functions.
// All assume a Matrix defined by a 2 dimensional array, where the first
//  index (array[x]) are the rows and the second index (array[x][y])
//  are the columns

// First create a few helper functions

class Matrix
{
	// A function to determine if a matrix is well formed.  That is to say that
	//  it is perfectly rectangular with no missing values:
	private function _validate($matrix)
	{
	    // If this is not an array, it is badly formed, return false.
	    if (!(is_array($matrix))) {
	        return false;
	    }
	    else {
	        // Count the number of rows.
	        $rows = count($matrix);

	        // Now loop through each row:
	        for ($r = 0; $r < $rows; $r++) {
	            // Make sure that this row is set, and an array.  Checking to
	            //  see if it is set is ensuring that this is a 0 based
	            //  numerically indexed array.
	            if (!(isset($matrix[$r]) && is_array($matrix[$r]))) {
	                return false;
	            }
	            else {
	                // If this is row 0, calculate the columns in it:
	                if ($r == 0) {
	                    $cols = count($matrix[$r]);
	                // Ensure that the number of columns is identical else exit
	                }
	                else if (count($matrix[$r]) != $cols) {
	                    return false;
	                }

	                // Now, loop through all the columns for this row
	                for ($c = 0; $c < $cols; $c++) {
	                    // Ensure this entry is set, and a number
	                    if (!(isset($matrix[$r][$c]) && is_numeric($matrix[$r][$c]))) {
	                        return false;
	                    }
	                }
	            }
	        }
	    }

	    // Ok, if we actually made it this far, then we have not found
        //  anything wrong with the matrix.
    	return true;
	}

	// A function to return the rows in a matrix -
	//   Does not check for validity, it assumes the matrix is well formed.
	function _rows($matrix)
	{
	    return count($matrix);
	}

	// A function to return the columns in a matrix -
	//   Does not check for validity, it assumes the matrix is well formed.
	function _columns($matrix)
	{
	    return count($matrix[0]);
	}

	// This function performs operations on matrix elements, such as addition
	//  or subtraction. To use it, pass it 2 matrices, and the operation you
	//  wish to perform, as a string: '+', '-'
	function element_operation($a, $b, $operation)
	{
	    // Verify both matrices are well formed
	    $valid = false;
	    if ($this->_validate($a) && $this->_validate($b)) {
	        // Make sure they have the same number of columns & rows
	        $rows = $this->_rows($a);
	        $columns = $this->_columns($a);

	        if (($rows == $this->_rows($b)) && ($columns ==$this->_columns($b))) {
	            // We have a valid setup for continuing with element math
	            $valid = true;
	        }
	    }

	    // If invalid, return false
	    if (!$valid) {
	    	return false;
	    }

	    // For each element in the matrices perform the operation on the
	    //  corresponding element in the other array to it:
	    for ($r = 0; $r < $rows; $r++) {
	        for ($c = 0; $c < $columns; $c++) {
	            eval('$a[$r][$c] '.$operation.'= $b[$r][$c];');
	        }
	    }

	    // Return the finished matrix:
	    return $a;
	}

	// This function performs full matrix operations, such as matrix addition
	//  or matrix multiplication.  As above, pass it to matrices and the
	//  operation: '*', '-', '+'
	function operation($a, $b, $operation)
	{
	    // Verify both matrices are well formed
	    $valid = false;
	    if ($this->_validate($a) && $this->_validate($b)) {
	        // Make sure they have complementary numbers of rows and columns.
	        // The number of rows in A should be the number of columns in B
	        $rows = $this->_rows($a);
	        $columns = $this->_columns($a);

	        if (($columns == $this->_rows($b)) && ($rows == $this->_columns($b))) {
	            // We have a valid setup for continuing
	            $valid = true;
	        }
	    }

	    // If invalid, return false
	    if (!$valid) {
	    	return false;
	    }

	    // Create a blank matrix the appropriate size, initialized to 0
	    $new = array_fill(0, $rows, array_fill(0, $rows, 0));

	    // For each row in a ...
	    for ($r = 0; $r < $rows; $r++) {
	        // For each column in b ...
	        for ($c = 0; $c < $rows; $c++) {
	            // Take each member of column b, with each member of row a
	            // and add the results, storing this in the new table:
	            // Loop over each column in A ...
	            for ($ac = 0; $ac < $columns; $ac++) {
	                // Evaluate the operation
	                eval('$new[$r][$c] += $a[$r][$ac] ' . $operation . ' $b[$ac][$c];');
	            }
	        }
	    }

	    // Return the finished matrix:
	    return $new;
	}

	// A function to perform scalar operations.  This means that you take the,
	//  scalar value and the operation provided, and apply it to every element.
	function scalar_operation($matrix, $scalar, $operation)
	{
	    // Verify it is well formed
	    if ($this->_validate($matrix)) {
	        $rows = $this->_rows($matrix);
	        $columns = $this->_columns($matrix);

	        // For each element in the matrix, multiply by the scalar
	        for ($r = 0; $r < $rows; $r++) {
	            for ($c = 0; $c < $columns; $c++) {
	                eval('$matrix[$r][$c] '.$operation.'= $scalar;');
	            }
	        }

	        // Return the finished matrix:
	        return $matrix;
	    }
	    else {
	        // It wasn't well formed:
	        return false;
	    }
	}

	// A handy function for printing matrices (As an HTML table)
	function _print($matrix)
	{
	    // Verify it is well formed
	    if ($this->_validate($matrix)) {
	        $rows = $this->_rows($matrix);
	        $columns = $this->_columns($matrix);

	        // Start the table
	        echo '<table>';

	        // For each row in the matrix:
	        for ($r = 0; $r < $rows; $r++) {
	            // Begin the row:
	            echo '<tr>';

	            // For each column in this row
	            for ($c = 0; $c < $columns; $c++) {
	                // Echo the element:
	                echo "<td>{$matrix[$r][$c]}</td>";
	            }

	            // End the row.
	            echo '</tr>';
	        }

	        // End the table.
	        echo '</table>';
	    }
	    else {
	        // It wasn't well formed:
	        return false;
	    }
	}

}

// Let's do some testing.  First prepare some formatting:
echo "<style>table { border: 1px solid black; margin: 20px; }
td { text-align: center; }</style>\n";

// Now let's test element operations.  We need identical sized matrices:
$m1 = array(
    array(5, 3, 2),
    array(3, 0, 4),
    array(1, 5, 2),
    );
$m2 = array(
    array(4, 9, 5),
    array(7, 5, 0),
    array(2, 2, 8),
    );

// Element addition should give us:  9  12   7
//                                  10   5   4
//                                   3   7  10
matrix_print(matrix_element_operation($m1, $m2, '+'));

// Element subtraction should give us:  1  -6  -3
//                                     -4  -5   4
//                                     -1   3  -6
matrix_print(matrix_element_operation($m1, $m2, '-'));

// Do a scalar multiplication on the 2nd matrix: 8  18  10
//                                              14  10   0
//                                               4   4  16
matrix_print(matrix_scalar_operation($m2, 2, '*'));

// Define some matrices for full matrix operations.
// Need to be complements of each other:
$m3 = array(
    array(1, 3, 5),
    array(-2, 5, 1),
    );
$m4 = array(
    array(1, 2),
    array(-2, 8),
    array(1, 1),
    );

// Matrix multiplication gives:  0  31
//                             -11  37
matrix_print(matrix_operation($m3, $m4, '*'));

// Matrix addition gives:  9  20
//                         4  15
matrix_print(matrix_operation($m3, $m4, '+'));

?>