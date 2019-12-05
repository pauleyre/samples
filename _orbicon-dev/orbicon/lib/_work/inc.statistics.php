<?php
// Various statistical functions that operate on arrays.

// The range of a list, is the maximum value minus the lowest value.
function array_range($values)
{
    // Use PHP builtin functions max, and min, to make this easy.
    return max($values) - min($values);
}

// The mean is the average of all values in the list
function array_mean($values)
{
    // Simply sum all the values, and divide it by the number of values.
    return array_sum($values) / count($values);
}

// The median is the value in the middle of the list.  Or the average
// of the two middle values if there is an even number of values.
function array_median($values)
{
    // First sort the array
    sort($values);

    // Now, if even, average the middle two values
    $length = count($values);
    if ($length % 2) {
        // It's odd, just return the middle value
        return $values[$length / 2];
    }
    else {
        // Else even, divide by 2 for the upper middle number and
        // then subtract 1 to get the lower middle.
        return ($values[$length / 2] + $values[($length / 2) - 1]) / 2;
    }
}

// The mode is the value (or values) that occur the most times.
// A value must occur more than once to be a mode, else there is none.
function array_mode($values)
{
    // Array count values will return us an array with only unique values
    // and a count of how many times each occurred.
    $unique_count = array_count_values($values);

    // Now sort these, keeping keys intact, in descending order
    arsort($unique_count);

    // Now loop down through these keys and count values:
    $mode = array();
    $stored_count = 0;

    foreach ($unique_count as $value => $count) {
        // First of all, if the count is 1, then exit, we are done
        if ($count == 1) {
        	break;
        }

        // Now if we don't have a mode yet, or this one is equal to the
        // stored modes, then keep this one.
        if ( (count($mode) == 0) || ($count == $stored_count) ) {
            $mode[] = $value;
            $stored_count = $count;
        }
        else {
            // Otherwise we have found a lesser count meaning we are done.
            break;
        }
    }

    // Return the mode, this might be an empty array if there was none.
    return $mode;
}

// Declare a set of numbers, and run the various procedures on them:
$set = array(1, 4, 12, 4, 6, 4, 7, 8, 1, 3, 1, 7, 0, 15);

// Output the values:
echo '<pre>';
echo 'The mean is: ', array_mean($set), "\n";
echo 'The range is: ', array_range($set), "\n";
echo 'The median is: ', array_median($set), "\n";
echo "The mode is: \n";
print_r(array_mode($set));
echo '</pre>';
?>
