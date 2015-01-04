<?php

class deleteControl {

public function deleteRow($data) {
  global $dbaccess, $utils;
  $utils->log('in deleteRow, rowid: ' . $data['rowid']);
  $dbaccess->deleteRow($data['rowid']);


} // close deleteRow


} // close class deleteControl

?>