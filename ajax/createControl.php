<?php

class createControl {

public function __construct() {
} // close function __construct()

public function createOverlaycontent($data) {
  $html .= "Person:<br>";
  $html .= "<input type='radio' name='person' value='Marcia' >Marcia<br>";
  $html .= "<input type='radio' name='person' value='John' >John<br>";
  $html .= "<input type='radio' name='person' value='Both' >Both<br><br>";

  $html .= "Time&nbsp;&nbsp;&nbsp;<input type='text' name='time' value='' /><br><br>";
  $html .= "Event&nbsp;&nbsp;<input type='text' name='event' value='' size='45' /><br><br>";

  $html .= "<div class='button' id='submitbtncreate'>Submit</div><br>";
  $html .= "<div class='button' id='cancelbtn'>Cancel</div>";

  $html .= "<input type='hidden' name='dayymd' value='{$data['dayymd']}' />";
  
  return $html;
} // close createOverlaycontent

public function createRow($data) {
  global $utils, $dbaccess;
  $utils->log('createRow: ');
  $fields = array(); $values = array();
  foreach ($data as $field=>$value) {
    if ($field == 'rowid' || $field == 'type') continue;
    if ($field == 'dayymd') $field = 'date';
    $fields[] = $field;
    $values[] = $value;
  }
  $dbaccess->createrow('schedule', $fields, $values);
} // close public function createRow($data)

} // close class readControl
?>