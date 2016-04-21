This file is a placeholder to make sure this directory is created in the repository.

Javascript directories should be structured like so:

javascript/myJavascript/body.js
javascript/myJavascript/head.js
javascript/myJavascript/otherFiles.js

Any javascript package can be conveniently loaded within phpWebSite like so:

$vars = array('key' => 'val',...);
$body = javascript('modules/douglas/myJavascript', $vars);

The contents of body.js will be processed through the template engine with the
values of $vars and returned into the $body variable.

The contents of head.js will be processed ONCE AND ONLY ONCE through the
template engine with the values of $vars and inserted into the {HEAD} of the
theme.
