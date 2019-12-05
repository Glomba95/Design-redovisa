<?php
/**
 * Global functions used in various modules.
 */



/**
 * i18n, internationalization, send all strings though this function to enable i18n. Inspired by Drupal´s t()-function.
 *
 * @param string $str  the string to check up for translation.
 * @param array  $args associative array with arguments to be
 *                     replaced in the str.
 *                      - !variable: Inserted as is. Use this for text
 *                        that has already been sanitized.
 *                      - @variable: Escaped to HTML using htmlEnt(). Use
 *                        this for anything displayed on a page on the site.
 *
 * @return string the translated string.
 */
function t($str, $args = [])
{
    /*
    if (CLydia::Instance()->config["i18n"]) {
        $str = gettext($str);
    }
    */
    
    // santitize and replace arguments
    if (!empty($args)) {
        foreach ($args as $key => $val) {
            switch ($key[0]) {
                case "@":
                    $args[$key] = htmlentities($val);
                    break;

                case "!":
                default: /* pass through */
                    break;
            }
        }
        return strtr($str, $args);
    }
    return $str;
}



/**
 * Sort array but maintain index when compared items are equal.
 * http://www.php.net/manual/en/function.usort.php#38827
 *
 * @param array    &$array       input array
 * @param callable $cmpFunction custom function to compare values
 *
 * @return void
 *
 * @codeCoverageIgnore
 */
function mergesort(&$array, $cmpFunction)
{
    // Arrays of size < 2 require no action.
    if (count($array) < 2) {
        return;
    }
    // Split the array in half
    $halfway = count($array) / 2;
    $array1 = array_slice($array, 0, $halfway);
    $array2 = array_slice($array, $halfway);
    // Recurse to sort the two halves
    mergesort($array1, $cmpFunction);
    mergesort($array2, $cmpFunction);
    // If all of $array1 is <= all of $array2, just append them.
    if (call_user_func($cmpFunction, end($array1), $array2[0]) < 1) {
        $array = array_merge($array1, $array2);
        return;
    }
    // Merge the two sorted arrays into a single sorted array
    $array = array();
    $ptr1 = $ptr2 = 0;
    while ($ptr1 < count($array1) && $ptr2 < count($array2)) {
        if (call_user_func($cmpFunction, $array1[$ptr1], $array2[$ptr2]) < 1) {
            $array[] = $array1[$ptr1++];
        } else {
            $array[] = $array2[$ptr2++];
        }
    }
    // Merge the remainder
    while ($ptr1 < count($array1)) {
        $array[] = $array1[$ptr1++];
    }
    while ($ptr2 < count($array2)) {
        $array[] = $array2[$ptr2++];
    }
    return;
}



/**
 * Glob recursivly.
 * http://in.php.net/manual/en/function.glob.php#106595
 *
 * @param string $pattern  pattern to search for
 * @param int    $flags    flags to use, as in glob()
 *
 * @return void
 *
 * @codeCoverageIgnore
 */
function glob_recursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . "/*", GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, glob_recursive($dir .  "/" . basename($pattern), $flags));
    }
    return $files;
}



/**
* array_merge_recursive does indeed merge arrays, but it converts values
* with duplicate keys to arrays rather than overwriting the value in the
* first array with the duplicate value in the second array, as array_merge
* does. I.e., with array_merge_recursive, this happens (documented behavior):
*
* array_merge_recursive(['key' => 'org value'], ['key' => 'new value']);
*     => ['key' => ['org value', 'new value']];
*
* array_merge_recursive_distinct does not change the datatypes of the values
* in the arrays. Matching keys' values in the second array overwrite those
* in the first array, as is the case with array_merge, i.e.:
*
* array_merge_recursive_distinct(['key' => 'org value'], ['key' => 'new value']);
*     => ['key' => ['new value']];
*
* Parameters are passed by reference, though only for performance reasons.
* They're not altered by this function.
*
* @param array $array1
* @param array $array2
* @return array
* @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
* @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
*
* @codeCoverageIgnore
*/
function array_merge_recursive_distinct(array &$array1, array &$array2)
{
    $merged = $array1;

    foreach ($array2 as $key => &$value) {
        if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
            $merged [$key] = array_merge_recursive_distinct($merged [$key], $value);
        } else {
            $merged [$key] = $value;
        }
    }

    return $merged;
}
