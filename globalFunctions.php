<?php
class prashikMinZip
{
	function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
	function createDirectory($myFolder)
	{
		if (!file_exists($myFolder)) {
			mkdir($myFolder, 0777, true);
		}
	}
	function minify()
	{
		$echoString = "";
		global $rootDir, $shellDir, $inputFolder, $outputFolder, $filesArray, $inputFile, $outputFile, $fullErrorLog, $errorLogFile, $dS, $globalNodeModules, $minZIPIISAppName, $prependText;

		$this->createDirectory($_SESSION['ID'] . $dS . "files");

		$this->createDirectory($_SESSION['ID'] . $dS . "min");

		$dir_iterator = new RecursiveDirectoryIterator(getcwd() . $dS . $inputFolder);
		$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
		// could use CHILD_FIRST if you so wish

		foreach ($iterator as $file) {
			if (!$iterator->isDot() && !$iterator->isDir() && (($this->endsWith($file->getFileInfo(), ".js") && !($this->endsWith($file->getFileInfo(), ".min.js"))) || ($this->endsWith($file->getFileInfo(), ".css") && !($this->endsWith($file->getFileInfo(), ".min.css"))))) {
				array_push($filesArray, str_replace(getcwd() . $dS, "", $file->getFileInfo()));
			}
		}

		foreach ($filesArray as $file) {
			$result = "";
			$resultNew = "";
			$currentErrorLog = "";
			$inputFile = $file;
			$outputFile = "";
			$status = true;
			if ($this->endsWith($file, ".js")) {
				$outputFile = substr($inputFile, 0, -3) . ($_POST['extensionflag'] != "true" ? ".js" : ".min.js");
			} elseif ($this->endsWith($file, ".css")) {
				$outputFile = substr($inputFile, 0, -4) . ($_POST['extensionflag'] != "true" ? ".css" : ".min.css");
			} else {
				$status = false;
			}
			if ($status) {
				$outputFile = str_replace($inputFolder, $outputFolder, $outputFile);
				$this->createDirectory(dirname($outputFile));
				$input = "";

				//$adminAuth = "echo adminpassword | runas /user:administrator ";
				//$adminAuth = "echo *** | runas /user:Administrator ";
				$adminAuth = "";

				if ($this->endsWith($file, ".js") && $_POST['version'] == "uglify") {
					$input = $adminAuth . "node \"" . $shellDir . "\\node_modules\@babel\cli\bin\babel\" \"" . $inputFile . "\" --config-file \"" . $shellDir . "\\babel.config.json" . "\" --out-file \"" . $outputFile . "\"";
					exec($input . " 2>&1", $result);
					$input = $adminAuth . "node \"" . $shellDir . "\\node_modules\uglify-es\bin\uglifyjs\" \"" . $outputFile . "\" -o \"" . $outputFile . "\" -c -m --ecma 5 --ie8 --safari10";
					exec($input . " 2>&1", $resultNew);
					$resultNew = implode("\r\n", (array)$resultNew);
					$result = implode("\r\n", (array)$result);
					$result = $result . "\r\n" . $resultNew;
				} else if ($this->endsWith($file, ".css") && $_POST['version'] == "uglify") {
					$input = $adminAuth . "node \"" . $shellDir . "\\node_modules\clean-css-cli\bin\cleancss\" --skip-rebase --compatibility ie7 \"" . $inputFile . "\" -o \"" . $outputFile . "\"";
					exec($input . " 2>&1", $result);
					$result = implode("\r\n", (array)$result);
				} else if ($_POST['version'] != "uglify") {
					$input = $adminAuth . "java -jar \"min.jar\" \"" . $inputFile . "\" -o \"" . $outputFile . "\"";
					exec($input . " 2>&1", $result);
					$result = implode("\r\n", (array)$result);
				}

				if (($result == "" || $result == "\r\n") && $resultNew == "") {
					$echoString = $echoString . "<div class=\"cSuccess\">" . str_replace($inputFolder, "", $inputFile) . "</div>";
					$this->prependText($outputFile, $prependText);
				} elseif (strpos(strtolower($result), "warning") === 0 || strpos(strtolower($resultNew), "warning") === 0) {
					$echoString = $echoString . "<div class=\"cWarning\">" . str_replace($inputFolder, "", $inputFile) . "</div>";
					$currentErrorLog = $result;
					$fullErrorLog = $fullErrorLog . "File=> " . str_replace($inputFolder, "", $inputFile) . "\r\n";
					$fullErrorLog = $fullErrorLog . "Warning=>\r\n" . str_replace($inputFolder, "", $currentErrorLog) . "\r\n\r\n";
					$this->prependText($outputFile, $prependText);
				} else {
					$echoString = $echoString . "<div class=\"cUnSuccess\">" . str_replace($inputFolder, "", $inputFile) . "</div>";
					$currentErrorLog = $result;
					$fullErrorLog = $fullErrorLog . "File=> " . str_replace($inputFolder, "", $inputFile) . "\r\n";
					$fullErrorLog = $fullErrorLog . "Error=>\r\n" . str_replace($inputFolder, "", $currentErrorLog) . "\r\n\r\n";
				}
			}
		}
		if ($fullErrorLog != "") {
			file_put_contents($errorLogFile, $fullErrorLog);
		}

		chdir($shellDir . $dS . $_SESSION['ID']);

		$zipped = $this->zipFolder(getcwd() . $dS . "min.zip");

		if (!$zipped) {
			$echoString = "";
		} else {
			rename($shellDir . $dS . $_SESSION['ID'] . $dS . "min.zip", str_replace("/", $dS, $rootDir . $dS . "zip" . $dS . $_SESSION['ID'] . ".zip"));
			$echoString = $echoString . "@$@" . $dS . $minZIPIISAppName . $dS . "zip" . $dS . $_SESSION['ID'] . ".zip";
		}

		chdir($shellDir);

		ob_end_clean();
		return $echoString;
	}

	function zipFolder($zipFile)
	{
		// Get real path for our folder
		$rootPath = preg_replace('/\\.[^.\\s]{3,4}$/', '', $zipFile);

		// Initialize archive object
		$zip = new ZipArchive();
		if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
			return false;
		}

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

		foreach ($files as $name => $file) {
			// Skip directories (they would be added automatically)
			if (!$file->isDir()) {
				// Get real and relative path for current file
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);

				// Add current file to archive
				$zip->addFile($filePath, $relativePath);
			}
		}

		// Zip archive will be created only after closing object
		$zip->close();
		return file_exists($zipFile);
	}

	function deleteOldFilesAndFolders()
	{
		global $rootDir, $shellDir, $dS;

		$dir = new DirectoryIterator($shellDir);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot() && ($this->isValidTimeStamp($fileinfo->getFilename()))) {
				$diff = $this->getTimeStampHourDifference($fileinfo->getFilename(), date_create()->getTimestamp());
				if ($diff > 0) {
					$this->deleteDir($shellDir . $dS . $fileinfo->getFilename());
				}
			}
		}
		$dir = new DirectoryIterator(str_replace("/", $dS, $rootDir . $dS . "zip"));
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDir() && !$fileinfo->isDot() && ($this->isValidTimeStamp(basename($fileinfo->getFilename(), ".zip")))) {

				$diff = $this->getTimeStampHourDifference(basename($fileinfo->getFilename(), ".zip"), date_create()->getTimestamp());
				if ($diff > 0) {
					unlink(str_replace("/", $dS, $rootDir . $dS . "zip") . $dS . $fileinfo->getFilename());
				}
			}
		}
	}
	function isValidTimeStamp($timestamp)
	{
		return (( string )( int )$timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX);
	}
	function getTimeStampHourDifference($start_time, $end_time)
	{
		$total_time = $end_time - $start_time;
		$days = floor($total_time / 86400);
		$hours = floor($total_time / 3600);
		return $hours;
	}
	function deleteDir($dirPath)
	{
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				$this->deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
	function initialisePage()
	{
		global $rootDir, $shellDir, $inputFolder, $outputFolder, $errorLogFile, $dS;
		chdir($shellDir);
		$this->checkStartSessionsss();
		$inputFolder = $_SESSION['ID'] . $dS . "files" . $dS;
		$outputFolder = $_SESSION['ID'] . $dS . "min" . $dS;
		$errorLogFile = $outputFolder . "log.txt";
	}
	function checkStartSession()
	{
		$sId = session_id();
		if (empty($sId)) {
			session_start();
			$_SESSION['ID'] = date_create()->getTimestamp();
		}
	}
	function checkStartSessionsss()
	{
		if (empty(session_id())) {
			session_start();
			$_SESSION['IDs'] = date_create()->getTimestamp();
		}
	}
	function prependText($file, $prependText)
	{
		$handle = fopen($file, "r+");
		$len = strlen($prependText);
		$final_len = filesize($file) + $len;
		$cache_old = fread($handle, $len);
		rewind($handle);
		$i = 1;
		while (ftell($handle) < $final_len) {
			fwrite($handle, $prependText);
			$prependText = $cache_old;
			$cache_old = fread($handle, $len);
			fseek($handle, $i * $len);
			$i++;
		}
	}
}
function Main()
{ }
