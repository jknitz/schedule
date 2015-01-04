<?php

class ClsPdo {

function __construct() {
  $this->dsn = 'mysql:host=localhost;dbname=' . DB . ';charset=UTF8';
  try {
    $this->dsn = new PDO($this->dsn, DBUSER, DBPSWD);
    $this->dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->dsn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }  catch(PDOException $e) {
    error_log('ClsPdo construct exception: ' . $e->getMessage());
    exit('ClsPdo construct exception: ' . $e->getMessage());
  }
} // close __construct

private function handle_sql_errors($sql, $msg){
  trigger_error("sql: $sql" . '<br>' . $msg, E_USER_ERROR);
  //exit("sql: $sql" . '<br>' . $msg);
}

public function getArray($sql) {
  try {
    $stm = $this->dsn->prepare($sql);
    $stm->execute();
  }
  catch(PDOException $e) { $this->handle_sql_errors($sql, $e->getMessage()); }
  while ($row = $stm->fetch(PDO::FETCH_NUM)) $rows[] = $row[0];
  return $rows;
} // close getArray

public function getFullTable($table) {
  $sql = "select * from $table";
  try {
    $stm = $this->dsn->prepare($sql);
    $stm->execute();
  }
  catch(PDOException $e) { $this->handle_sql_errors($sql, $e->getMessage()); }
  while ($row = $stm->fetch(PDO::FETCH_NUM)) $rows[] = $row;
  return $rows;
} // close public function getFullTable($table)

public function getRowIndexed($sql, $index) {
  try {
    $stm = $this->dsn->prepare($sql);
    $stm->execute();
  }
  catch(PDOException $e) { $this->handle_sql_errors($sql, $e->getMessage()); }
  while ($row = $stm->fetch(PDO::FETCH_ASSOC)) $rows[$row[$index]] = $row;
  return $rows;
} // close getRowIndexed

public function updateRow($table, $fieldarr, $valuearr, $whereclause) {
  global $utils;
  $sql = "update $table set ";
  foreach ($fieldarr as $field) {
    $sql .= "$field = ?, ";
  }
  $sql = rtrim($sql, ', '); // delete last comma
  $sql .= ' ' . $whereclause;
  $utils->log('pdo updateRow sql: ' . $sql);
  try {
    $stm = $this->dsn->prepare($sql);
    $stm->execute($valuearr);
  } catch(PDOException $e) {error_log('updaterow except: ' .  $e->getMessage());}
} // close public function updateRow(

public function insertRow($table, $fieldarr, $valuearr) {
  global $utils;
  $sql = "insert into schedule (";
  $qty = 0;
  foreach ($fieldarr as $field) {
  $qty++;
    $sql .= "$field, ";
  }
  $sql = rtrim($sql, ', '); // delete last comma
  $sql .= ") values (";
  for ($i=0;$i<$qty;$i++) $sql .= '?, ';
  $sql = rtrim($sql, ', '); // delete last comma
  $sql .= ')';
  $utils->log("insertRow: $sql");
  try {
    $stm = $this->dsn->prepare($sql);
    $stm->execute($valuearr);
  } catch(PDOException $e) {error_log('getRowFromId except: ' .  $e->getMessage());}
} // close insertRow

public function getRowFromId($table, $id) {
  global $utils;
  $utils->log("getRowFromId: $table, $id");
  $sql = "select * from $table where id = ?";
  try {
    $stm = $this->dsn->prepare($sql);
    $stm->execute(array($id));
  } catch(PDOException $e) {error_log('getRowFromId except: ' .  $e->getMessage());}
  while ($row = $stm->fetch(PDO::FETCH_ASSOC)) {
    $utils->log("getRowFromId: " . json_encode($row));
    return $row;
  }
} // close getRowFromId

public function delRowId($id) {
  $sql = "delete from schedule where id = ?";
  try {
    $stm = $this->dsn->prepare($sql);
    $stm->execute(array($id));
  } catch(PDOException $e) {error_log('getRowFromId except: ' .  $e->getMessage());}
} // close delRowId

} // close class Pdo

class ClsDbAccess {

function __construct() {
  $this->pdo = new ClsPdo();
} // close function __construct()

public function getTables() {
  $sql = "show tables";
  $tables = $this->pdo->getArray($sql);
  return $tables;
} // close getTables

public function getColumns($table) {
  $sql = "show columns from $table";
  $columns = $this->pdo->getArray($sql);
  return $columns;
} // close getColumns

public function getRowsIndexLimit($table, $limit) {
  $index = 'id';
  $sql = "select * from $table order by date desc limit $limit";
  $rows = $this->pdo->getRowIndexed($sql, $index);
  foreach ($rows as $id=>$row) {
    foreach ($row as $field=>$item) {
      if (in_array($field, $this->type[$table]) && $this->type[$table][$field] == 'date')
        $rows[$id][$field] = date('m/d/y', strtotime($rows[$id][$field]));
    }
  }
  return $rows;
} // close getRowsIndexLimit

public function getRowsIndex($table, $sort='', $ascordesc='') {
  $index = 'id';
  if ($sort == '') $sql = "select * from $table order by id asc";
  elseif ($sort != '' && $ascordesc == '') $sql = "select * from $table order by $sort asc";
  elseif ($sort != '' && $ascordesc != '') $sql = "select * from $table order by $sort $ascordesc";
  $rows = $this->pdo->getRowIndexed($sql, $index);
  return $rows;
}

public function getFullTable($table) {
  $rows = $this->pdo->getFullTable($table);
  return $rows;
}

public function updaterow($table, $rowid, $fields, $values) {
  global $utils;
  //$utils->log('updaterow: ' . print_r($values, true));
  $whereclause = "where id = $rowid";
  $this->pdo->updateRow($table, $fields, $values, $whereclause);
  //$utils->log('in updaterow: ' . $rowid);
} // close function updaterow($table, $rowid, $fields, $values)

public function createrow($table, $fields, $values) {
  $this->pdo->insertRow($table, $fields, $values);
}

public function getRow($rowid) {
  global $utils;
  $utils->log("getRow: $table, $rowid");
  $row = $this->pdo->getRowFromId('schedule', $rowid);
  return $row;
} // close getRow

public function getSearch($text) {
  $sql = "select * from schedule where event like '%$text%' order by date";
  $rows = $this->pdo->getRowIndexed($sql, 'id');
  return $rows;
}

public function deleteRow($rowid) {
  $this->pdo->delRowId($rowid);
}




//xxx column types xxx//
private $type = array(
  'schedule' => array('date' => 'date')
); // close array $type

} // close class ClsDbAccess

?>