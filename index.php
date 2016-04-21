<?php
\PHPWS_Core::initModClass('douglas', 'Base.php');
if (isset($_REQUEST['aop'])) {
    PHPWS_Core::initModClass('douglas', 'Admin.php');
    $manager = new douglas\Admin;
} else {
    PHPWS_Core::initModClass('douglas', 'User.php');
    $manager = new douglas\User;
}
?>
