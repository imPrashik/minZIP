<?php
//Note: give IUSR and IIS_IUSRS read+modify permission to execute in php for iisexpress
$GLOBALS['parentDIR'] = "C:\inetpub\wwwroot";
$GLOBALS['minZIPFolderName'] = "minZIP";
$GLOBALS['minZIPIISAppName'] = "minZIP";
$GLOBALS['minShellFolderName'] = "minShell";
$GLOBALS['dS'] = DIRECTORY_SEPARATOR;
$GLOBALS['rootDir'] = $GLOBALS['parentDIR'] . $GLOBALS['dS'] . $GLOBALS['minZIPFolderName'];
$GLOBALS['shellDir'] = $GLOBALS['rootDir'] . $GLOBALS['dS'] . $GLOBALS['minShellFolderName'];
$GLOBALS['jarFile'] = "min.jar";
$GLOBALS['inputFile'] = "";
$GLOBALS['outputFile'] = "";
$GLOBALS['currentErrorLog'] = "";
$GLOBALS['fullErrorLog'] = "";
$GLOBALS['filesArray'] = array();
$GLOBALS['inputFolder'] = "";
$GLOBALS['outputFile'] = "";
$GLOBALS['errorLogFile'] = "";
//$GLOBALS['npmLocal'] = "C:\Users\Prashik\AppData\Roaming\\npm";
//$GLOBALS['globalNodeModules'] = "C:\Users\Prashik\\AppData\Roaming\\npm\\node_modules";
$GLOBALS['newVersion'] = true;
$GLOBALS['disableVersion'] = false;
$GLOBALS['displayAppName'] = "minZIP";
$GLOBALS['displayAppVersion'] = "3.8";
$GLOBALS['displayToolsUsed'] = "PHP, Node(Babel, Uglify and CleanCSS) and JQuery";
$GLOBALS['developerName'] = "Prashik";
$GLOBALS['developerEmail'] = "prashik@agile-labs.com | prashik.nandeshwar@gmail.com";
$GLOBALS['companyName'] = "Agile-Labs";
$GLOBALS['smallAbout'] = $GLOBALS['displayAppName'] . " v" . $GLOBALS['displayAppVersion'] . " ❤ " . $GLOBALS['developerName'] . "@" . $GLOBALS['companyName'];
$GLOBALS['bigAbout'] = $GLOBALS['displayAppName'] . " v" . $GLOBALS['displayAppVersion'] . " is an effective tool powering AxpertWeb platform and AgileCloud which compresses JS/CSS files and provide browser compatibility for same.";
$GLOBALS['mailTo'] = "mailto:" . trim(explode("|",$GLOBALS['developerEmail'])[0]) . "?subject=" . $GLOBALS['displayAppName'] . " v" . $GLOBALS['displayAppVersion'] . " Feedback" . (count(explode("|",$GLOBALS['developerEmail'])) > 1 ? ("&cc=" . trim(explode("|",$GLOBALS['developerEmail'])[1])) : "");
$GLOBALS['prependText'] = "/*http://demo.agile-labs.com/" . $GLOBALS['displayAppName'] . "/*//*version=" . $GLOBALS['displayAppVersion'] . "*/";
?>