<?php

/**
 * Boost will call this function on-update, passing in a reference
 * to the content string that will be printed to the user, and the
 * current version being updated from.  Use the php function
 * @see version_compare() to conveniently compare versions.
 *
 * @param string &$content The content array that will be imploded
 *                         and echoed
 * @param string $currentVersion The current version of the installed
 *                               module
 * @return boolean True on success, anything else on failure
 */
function douglas_update(&$content, $currentVersion)
{
    $home_dir = PHPWS_Boost::getHomeDir();
    switch ($currentVersion) {
        case version_compare($currentVersion, '1.1.0', '<'):
            $db = \Database::newDB();
            $t1 = $db->addTable('douglas_applicants');
            $dtype = new \Database\Datatype\Varchar($t1, 'professional_super_email');
            $dtype2 = new \Database\Datatype\Text($t1, 'admin_comments');
            $dtype->add();
            $dtype2->add();
            $content[] = <<<EOF
<pre>Version 1.1.0
------------------
+ Added comment box.
+ Supervisors are emailed.
+ Every email is CCed to admins
</pre>
EOF;
    } // end switch
    return true;
}

?>
