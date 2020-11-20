<?php
if (session_id()) {
	session_destroy();
}

include_once 'globalVariables.php';
include_once 'globalFunctions.php';

$pMZ = new prashikMinZip;
$pMZ->checkStartSession();
$pMZ->initialisePage();
$pMZ->deleteOldFilesAndFolders();
?>

<!doctype html>
<html lang="en">

<head>

	<meta charset="utf-8" />
	<title><?php echo $GLOBALS['displayAppName'] . "❤" . $GLOBALS['developerName'] ?></title>

	<link rel='icon' href='favicon.ico' type='image/x-icon' />

	<link href="css/iconsMdl.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="materialize/css/materialize.css">
	<link rel="stylesheet" type="text/css" href="css/style.min.css?v=3">

</head>

<div class="container mainContainer ">

	<div class="row  valign-wrapper">

		<body style="height:100vh;">

			<div class="col s12 m8 l6 offset-m2 offset-l3 valign">
				<div class="card" style="">
					<div class="loading hide" style="position: fixed; top: 0px;	left: 0px; z-index: 2; width: 100%; height: 100%; display: flex; background: url(loadingBarsWhite.gif) center center no-repeat rgba(0, 0, 0, 0.7); background-size: 135px;">
					</div>
					<div class="card-image">
						<form action="upload.php" method="POST" enctype="multipart/form-data" style="background: rgba(0, 0, 0, 0.9); color: white">
							<div>
								<div class="formDiv">
									<input type="file" name="files[]" id="files" multiple="multiple" style="cursor: pointer;">
									<p>
										<i class="material-icons">playlist_add</i><span>Drag your
											ZIP/JS/CSS here or click in this area.</span>
									</p>

								</div>
							</div>
							<button id="upload" type="submit" class="btn-floating halfway-fab waves-effect waves-red teal darken-1">
								<i class="material-icons">backup</i>
							</button>
						</form>
					</div>
					<div class="progress" style="margin:0px;">
						<div class="percent">0%</div>
						<div class="determinate" style="width: 0%"></div>
					</div>
					<div class="card-content" style="padding-top: 14px;padding-bottom: 14px;">
						<span class="card-title" style="font-size: 30px;margin-bottom:0px;">
							<!-- Dropdown Trigger -->
							<a class='dropdown-button btn waves-effect waves-light teal lighten-4' href='#' data-activates='dropdown1' style="text-transform: initial; font-size: smaller; padding-top: 11px; padding-bottom: 11px; height: 55px;font-family: monospace;color:black;">&lt;/<?php echo $GLOBALS['displayAppName'] ?>&gt;&nbsp;<i class="material-icons" style="font-size: 1rem;">settings</i></a>
							<!-- Dropdown Structure -->
							<ul id='dropdown1' class='dropdown-content'>
								<li style="display:none;"><span class="switch" style="">
										<label style="color: black; font-weight: 600;">
											<!-- <span class="tooltipped"  data-position="down" data-delay="50" data-tooltip="YUI">Ver1</span> -->
											<span>YUI</span>
											<?php
											global $newVersion, $newVerInput, $disableVersion, $disableVerInput;
											$newVersion == true ? $newVerInput = "checked='checked'" : $newVerInput = "";
											$disableVersion == true ? $disableVerInput = "disabled='disabled'"  : $disableVerInput = "";
											?>
											<input id="version" type="checkbox" <?php echo $newVerInput ?> <?php echo $disableVerInput ?>>
											<span class="lever" style=""></span>
											<!-- <span class="tooltipped"  data-position="down" data-delay="50" data-tooltip="Uglify (ES6+)">Ver2</span> -->
											<span>Uglify (ES6+)</span>
										</label>
									</span></li>
								<li class="divider" style="display:none;"></li>
								<li><span style="">
										<input type="checkbox" id="extensionFlag" class="filled-in" checked="checked" />
										<label for="extensionFlag" style="color: black; font-weight: 400;">.min Extension</label>
									</span></li>
								<li class="divider"></li>
								<li><a onclick="Materialize.toast('<?php echo $GLOBALS['bigAbout']; ?>', 5000)"><i class="material-icons" style="line-height: initial;">info</i><span style="color: black;font-weight: 400;">About</span></a></li>
							</ul>

							<!-- <div class="switch" style="float:right">
    <label>
      <span class="tooltipped"  data-position="down" data-delay="50" data-tooltip="YUI">Ver1</span>
	  <?php
		global $newVersion, $newVerInput, $disableVersion, $disableVerInput;
		$newVersion == true ? $newVerInput = "checked='checked'" : $newVerInput = "";
		$disableVersion == true ? $disableVerInput = "disabled='disabled'"  : $disableVerInput = "";
		?>
      <input id="version" type="checkbox" <?php echo $newVerInput ?> <?php echo $disableVerInput ?>>
      <span class="lever"></span>
      <span class="tooltipped"  data-position="down" data-delay="50" data-tooltip="Uglify (ES6+)">Ver2</span>
    </label>
  </div> -->
						</span>
					</div>
				</div>
			</div>
	</div>
</div>
<a class="btn btn-floating tooltippedDisable" style="position: fixed; bottom: -10px; left: -10px; color: white" href="<?php echo $GLOBALS['mailTo'] ?>" data-position="top" data-tooltip="<?php echo $GLOBALS['bigAbout']; ?>"><i class="material-icons">mail_outline</i></a>

<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="materialize/js/materialize.js"></script>
<script type="text/javascript" src="js/script.min.js?v=5"></script>
</body>

</html>