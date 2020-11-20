
<?php
header('Content-Type: application/json');
include_once 'globalVariables.php';
include_once 'globalFunctions.php';
$pMZ = new prashikMinZip;
$pMZ->initialisePage();
?>
<?php
global $rootDir, $shellDir, $inputFolder, $outputFolder, $fullErrorLog, $errorLogFile, $dS;
chdir($shellDir);
$pMZ->checkStartSessionsss();

$max_size = 1024 * 1024 * 200;
$extensions = array('js', 'css', 'zip');
$dir = "uploads" . $dS;
$count = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['files'])) {
	// loop all files
	foreach ($_FILES['files']['name'] as $i => $name) {
		// if file not uploaded then skip it
		if (!is_uploaded_file($_FILES['files']['tmp_name'][$i]))
			continue;

		// skip large files
		if ($_FILES['files']['size'][$i] >= $max_size)
			continue;

		// skip unprotected files
		if (!in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions))
			continue;

		if ((($pMZ->endsWith($name, ".js") && !($pMZ->endsWith($name, ".min.js"))))) {
			$dir = $inputFolder . $dS . "js" . $dS;
		} elseif (($pMZ->endsWith($name, ".css") && !($pMZ->endsWith($name, ".min.css")))) {
			$dir = $inputFolder . $dS . "css" . $dS;
		} elseif (($pMZ->endsWith($name, ".zip"))) {
			$dir = $_SESSION['ID'] . $dS . "zip" . $dS;
		} else {
			continue;
		}

		// now we can move uploaded files					
		$pMZ->createDirectory($dir);

		if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $dir . $name))
			$count++;

		if (($pMZ->endsWith($name, ".zip"))) {
			$zip = new ZipArchive;
			$res = $zip->open($dir . $name);
			if ($res === TRUE) {
				$targetPath = $inputFolder;
				$pMZ->createDirectory($targetPath);
				$zip->extractTo($targetPath);
				$zip->close();
				/* echo 'ok'; */
			} else {
				/* echo 'failed'; */ }
		}
	}
	ob_end_clean();
	echo json_encode(array('count' => $count));
}
?>