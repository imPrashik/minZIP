<?php
include_once 'globalVariables.php';
include_once 'globalFunctions.php';

$pMZ = new prashikMinZip;
$pMZ->initialisePage();
?>
<?php
header('Content-Type: application/json');

$aResult = array();

if (!isset($_POST['functionname'])) {
	$aResult['error'] = 'No function name!';
}
if (!isset($aResult['error'])) {

	switch ($_POST['functionname']) {
		case 'minify':
			$aResult['result'] = $pMZ->minify();
			break;
		default:
			$aResult['error'] = 'Not found function ' . $_POST['functionname'] . '!';
			break;
	}
}

echo json_encode($aResult);
?>