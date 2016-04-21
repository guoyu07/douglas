<?php

/**
 * Boost will call this function on-uninstall, passing in a reference
 * to the content string that will be printed to the user.
 *
 * @param string &$content The content array that will be imploded
 *                         and echoed
 * @return boolean True on success, anything else on failure.
 */
function douglas_uninstall(&$content) {
    \PHPWS_DB::dropTable('douglas_applicants');
    return true;
}

?>
