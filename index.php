<?php
if ( strpos( $_SERVER["DOCUMENT_ROOT"], 'public_html' ) > -1 ) 
     define('LOCATION', 'REMOTE');
else define('LOCATION', 'LOCAL');
ini_set('error_log', 'logs/errorlog.txt');
list($fileroot) = explode('index.php', __FILE__);
include 'includes/configuration.php';
include 'includes/utils.php';
$utils = new clsUtils();
$utils->clearLog();
$utils->log('Page Load');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?php echo TITLE;?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" >
  <script type='text/javascript' src='includes/jsfiles/jquery-1.11.0.js'></script>
  <script type='text/javascript' src='includes/jsfiles/definitions.js'></script>
  <script type='text/javascript' src='includes/jsfiles/ajaxQueue.js'></script>
  <script type='text/javascript' src='includes/jsfiles/general.js'></script>
  <script type='text/javascript' src='includes/jsfiles/screenutils.js'></script>
  <link rel='stylesheet' type='text/css' href='includes/csfiles/reset.css' >
  <link rel='stylesheet' type='text/css' href='includes/csfiles/general.css' >
<style>
  body      { text-align:center; }
  #wrapper  { text-align:left; margin:5px auto; }
</style>
</head><body>
<div id='wrapper'>

<div id='header'>
  <div class='content' >
    <div id='date' class='headitem'>date</div>
    <div id='time' class='headitem'>time</div>
    <div class='headitem'>&nbsp;&nbsp;&nbsp;&nbsp;</div>
    <input type='text' id='search' name='search' class='headitem' />Search
  </div>
</div> <!-- close id header -->

<div id='left'>
<div class='leftdiv' id='schedule'>Schedule</div>
<div class='leftdiv' id='editevent'>Edit Event</div>
<div class='leftdiv' id='newevent'>New Event</div>
<div class='leftdiv' id='futureevents'>Future Events</div>
<div class='leftdiv' id='pastevents'>Past Events</div>
<div class='leftdiv' id='john'>John</div>
<div class='leftdiv' id='marcia'>Marcia</div>
<div class='leftdiv' id='delete'>Delete</div>
<div class='leftdiv' id='addsixmonths'>Add Six Months</div>
<!--<div class='leftdiv' id='modifydatecolumn'>Modify New Date Column</div>-->
</div>

<div id='right'></div>

<div id='overlay'>
  <div id='overlayheader'></div>
  <div id='overlaycontent'></div>
</div>
</div> <!-- close wrapper -->

</body></html>

