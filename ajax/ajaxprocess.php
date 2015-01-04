<?php
if ( strpos( $_SERVER["DOCUMENT_ROOT"], 'public_html' ) > -1 ) 
     define('LOCATION', 'REMOTE');
else define('LOCATION', 'LOCAL');
list($fileroot) = explode('ajax/ajaxprocess.php', $_SERVER["SCRIPT_FILENAME"]);
include $fileroot . 'includes/configuration.php';
ini_set('error_log', $fileroot . 'logs/errorlog.txt');

include $fileroot . 'includes/utils.php';
$utils = new clsUtils();
$utils->log('in ajaxprocess: ' . $_POST['process']);
include $fileroot . 'includes/dbClasses.php';
$dbaccess = new ClsDbAccess();

$jsondata = stripslashes($_POST['jsondata']);
$data = json_decode($jsondata, true);
$utils->log('jsondata: ' . $jsondata);

//XXX process == read xxx//
if ($_POST['process'] == 'read') {
$utils->log('read: ' . $data['id'] );
  include 'readControl.php';
  $readCtl = new readControl();
  if ($data['id'] == 'futureevents') {
    $html = $readCtl->getEvents();
    $retarray['html'] .= $html;
  }
  elseif ($data['id'] == 'pastevents') {
    $html = $readCtl->getPastEvents();
    $retarray['html'] .= $html;
  }
  elseif ($data['id'] == 'schedule') {
    $html = $readCtl->getSchedule();
    $retarray['html'] .= $html;
  }
  elseif ($data['id'] == 'john') {
    $html = $readCtl->getEvents('John');
    $retarray['html'] .= $html;
  }
  elseif ($data['id'] == 'marcia') {
    $html = $readCtl->getEvents('Marcia');
    $retarray['html'] .= $html;
  }
  elseif ($data['id'] == 'editevent') {
    $html = $readCtl->getEditevent($data['rowid']);
    $retarray['html'] .= $html;
  }
  elseif ($data['id'] == 'search') {
    $html = $readCtl->getSearch($data['text']);
    $retarray['html'] .= $html;
  }
  else $retarray['html'] .= 'no result';
} // close process == read

//XXX process == update xxx//
elseif ($_POST['process'] == 'update') {
  $utils->log('update: ' . $jsondata);
  include 'updateControl.php';
  $updateCtl = new updateControl();
  if ($data['type'] == 'updateschedule')    $updateCtl->updateRow($data);
  else if ($data["type"] == "addsixmonths") {
    $utils->log('it is addsixmonths');
    $updateCtl->updateSixMonths($data);
    
  }
  else if ($data['type'] == 'modifydatecolumn') $updateCtl->updateDateColumn($data);

  include 'readControl.php';
  $readCtl = new readControl();
  $html = $readCtl->getSchedule();
  $retarray['html'] .= $html;

} // close process == update

//XXX process == create xxx//
elseif ($_POST['process'] == 'create') {
  $utils->log($jsondata . ' ' . $data['type']);
  include 'createControl.php';
  $createCtl = new createControl();
  
  if ($data['type'] == 'overlaycontent') {
    $retarray['html'] .= $createCtl->createOverlaycontent($data);
  } 
  elseif ($data['type'] == 'createrow') {
    $createCtl->createRow($data);
    include 'readControl.php';
    $readCtl = new readControl();
    $html = $readCtl->getSchedule();
    $retarray['html'] .= $html;
  }
} // close process == create

//XXX process == delete xxx//
elseif ($_POST['process'] == 'delete') {
  $utils->log('rowid: ' . $data['rowid']);
  include 'deleteControl.php';
  $deleteCtl = new deleteControl();
  $deleteCtl->deleteRow($data);
  include 'readControl.php';
  $readCtl = new readControl();
  $html = $readCtl->getSchedule();
  $retarray['html'] .= $html;
} // close process == delete

else { $retarray['msg'] =  "No process found " . $_POST['process']; }
echo json_encode($retarray);
exit;
?>