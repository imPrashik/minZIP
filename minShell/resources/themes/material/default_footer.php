<!-- <!-?php if($supportdirectorylister || $supportjustinkruit) { ?>
<div class="container">
<div class="divider <!-?php echo $divider; ?>"></div>

<div class="center-align <!-?php echo $textcolor; ?>">
    <!-?php if ($supportdirectorylister) {?>Powered by, <a href="http://www.directorylister.com" class="<!-?php echo $color; ?>-text" target="_blank">Directory Lister</a>.<!-?php } ?>
	<br class="hide-on-med-and-up"><!-?php if ($supportjustinkruit) {?>Material theme made by <a href="http://justinkruit.com" class="<!-?php echo $color; ?>-text" target="_blank">Justin Kruit</a><!-?php } ?>
</div></div>
<!-?php } ?> -->
<?php require_once('../globalVariables.php'); ?>

<div class="container">
<div class="divider"></div>

<div class="center-align <?php echo $textcolor; ?>">
    <?php if ($supportdirectorylister) {?><a href="http://agilecloud.biz/minZIP" class="<?php echo $color; ?>-text"><?php echo $GLOBALS['displayAppName'] ?></a> ❤ <a href="<?php echo $GLOBALS['mailTo'] ?>" class="<?php echo $color; ?>-text"><?php echo $GLOBALS['developerName'] ?></a><?php } ?>
</div></div>
