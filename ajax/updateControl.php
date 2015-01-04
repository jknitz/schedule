<?php

class updateControl {

public function __construct() {
} // close function __construct()

//xxx update a schedule row xxx//
public function updateRow($data) {
  global $utils, $dbaccess;
  $rowid = $data['rowid'];
  $fields = array(); $values = array();
  foreach ($data as $field=>$value) {
    if ($field == 'rowid' || $field == 'dayymd'|| $field == 'type') continue;
    $fields[] = $field;
    $values[] = $value;
  }
  $dbaccess->updaterow('schedule', $rowid, $fields, $values);
} // close public function updateRow($data)

//xxx increase the config value 'lastdate' by six months xxx//
public function updateSixMonths($data) {
  global $utils, $dbaccess;
  $utils->log('in updateSixMonths');
  $unixdaydelta = 86400;
  $rows = $dbaccess->getFullTable('config');
  foreach ($rows as $row) if ($row[1] == 'lastdate') {
    $lastdate = $row[2];
    $rowid = $row[0];
  }
  $lastdateunix = strtotime($lastdate);
  $sixmonths = $unixdaydelta * 30 * 6;
  $newlastdateunix = $lastdateunix + $sixmonths;
  $utils->log('lastdate: ' . date('Y-m-d', $lastdateunix) . ', newlastdate: ' . date('Y-m-d', $newlastdateunix) );
  $dbaccess->updaterow('config', $rowid, array('value'), array(date('Y-m-d', $newlastdateunix)));
}

//xxx used to change date format Ymd to Y-m-d xxx//
public function updateDateColumn($data) {
  global $utils, $dbaccess;
  $utils->log('in updateDateColumn');
  $rows = $dbaccess->getRowsIndex('schedule');

  $qty = 0;
  $newdate = array();
  foreach ($rows as $id=>$row) {
    $newdate[$id] = substr($rows[$id]['date'], 0, 4) . '-'
                  . substr($rows[$id]['date'], 4, 2) . '-'
                  . substr($rows[$id]['date'], 6, 2);
    $utils->log($i . ' ' . $rows[$id]['date'] . ' ' . $newdate[$id]);
    $qty++;
    //if ($qty == 5) break;
  }
  foreach ($rows as $id=>$row) {
    $dbaccess->updaterow('newschedule', $id, array('newdate'), array($newdate[$id]));
  }
}
} // close class readControl
?>