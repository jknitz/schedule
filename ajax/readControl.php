<?php

class readControl {

public function __construct() {

} // close function __construct()

public function test() {
  global $dbaccess;
  $tables = $dbaccess->getTables();
  return $tables;
}

public function getSearch($text) {
  global $dbaccess;
  $rows = $dbaccess->getSearch($text);
  $today = date('Y-m-d');
  foreach ($rows as $id=>$row) {
    if ($row['date'] < $today) 
         $mystyle = 'background:#ffa;';
    else $mystyle = 'background:#afa;';
    $dayofweek = date('D', strtotime($row['date']));
    if ($dayofweek == 'Sat' || $dayofweek == 'Sun') $daystyle = 'background:#fcc;';
    else $daystyle = '';
    $daymdy = date('n/j/y', strtotime($row['date']));
    $html .= "<tr data-id='$id' data-dayymd='{$row['date']}'><td style='$daystyle'>"
          . $daymdy . '</td><td>'
          . $dayofweek . '</td><td>'
          . $row['person'] . '</td><td>'
          . $row['time'] . "</td><td style='$mystyle'>"
          . $row['event']
          . '</td></tr>';
  }
  return $html;
} // close getSearch()


public function getPastEvents() {
  global $dbaccess;
  $rows = $dbaccess->getRowsIndex('schedule', 'date', 'desc');
  $html = '<table>';
  $today = date('Y-m-d');
  $lastdatymd = '';
  foreach ($rows as $id=>$row) {
    if ($row['date'] >= $today) continue;
    if ( $person !='' && $row['person'] != $person ) continue;
    $dayofweek = date('D', strtotime($row['date']));
    if ($dayofweek == 'Sat' || $dayofweek == 'Sun') $daystyle = 'background:#fcc;';
    else $daystyle = '';
    $daymdy = date('n/j/y', strtotime($row['date']));
    $html .= "<tr data-id='$id' data-dayymd='{$row['date']}'><td style='$daystyle'>";
    if ($lastdatymd == $row['date']) $html .= '</td><td>';
    else $html .= $daymdy . '</td><td>';
    $html .= $dayofweek . '</td><td>'
          . $row['person'] . '</td><td>'
          . $row['time'] . '</td><td>'
          . $row['event']
          . '</td></tr>';
    $lastdatymd = $row['date'];
  }
  $html .= '</table>';
  return $html;
} // close getPastEvents

public function getEvents($person='') {
  global $dbaccess;
  $rows = $dbaccess->getRowsIndex('schedule', 'date');
  $html = '<table>';
  $today = date('Y-m-d');
  $lastdatymd = '';
  foreach ($rows as $id=>$row) {
    if ($row['date'] < $today) continue;
    if ( $person !='' && $row['person'] != $person && $row['person'] != 'Both' ) continue;
    $dayofweek = date('D', strtotime($row['date']));
    if ($dayofweek == 'Sat' || $dayofweek == 'Sun') $daystyle = 'background:#fcc;';
    else $daystyle = '';
    $daymdy = date('n/j/y', strtotime($row['date']));
    $html .= "<tr data-id='$id' data-dayymd='{$row['date']}'><td style='$daystyle'>";
    if ($lastdatymd == $row['date']) $html .= '</td><td>';
    else $html .= $daymdy . '</td><td>';
    $html .= $dayofweek . '</td><td>'
          . $row['person'] . '</td><td>'
          . $row['time'] . '</td><td>'
          . $row['event']
          . '</td></tr>';
    $lastdatymd = $row['date'];
  } // close foreach
  $html .= '</table>';
  return $html;
} // close public function getEvents()

public function getSchedule() {
  global $dbaccess, $utils;
  $utils->log("in getSchedule");
  $unixdaydelta = 86400;
  $rows = $dbaccess->getFullTable('config');
  foreach ($rows as $row) if ($row[1] == 'lastdate') $lastdate = $row[2];
  $lastdateunix = strtotime($lastdate);
  $todayymd = date('Y-m-d');
  $todayunix = strtotime($todayymd);
  $rows = $dbaccess->getRowsIndex('schedule', 'date');
  foreach ($rows as $id=>$row) {
    if ($row['date'] < $todayymd) continue;
    $rowids[] = $id;
  }
  //$utils->log("id: $id");
  $dbid = array_shift($rowids);
  $lineid = 0; // todo lineid not used; remove
  $dayunix = $todayunix;
  $dayymd = $todayymd;
  $daymdy = date('n/j/y');
  $dayofweek = date('D');
  $rowdateymd = $rows[$dbid ]['date'];
  $html = '<table>';
  while ($dayunix < $lastdateunix) {
    if ($dayofweek == 'Sat' || $dayofweek == 'Sun') $daystyle = 'background:#fcc;';
    else $daystyle = '';
    if ($rowdateymd == $dayymd) {
      $qty = 0;
      while ($rowdateymd == $dayymd) {
        $html .= "<tr data-id='$dbid' data-dayymd='$dayymd'>";
        if ($qty == 0) $html .= "<td style='$daystyle'>$daymdy</td>";
        else $html .= "<td style='$daystyle'>&nbsp;</td>";
        $html .= "<td>$dayofweek</td>
                  <td>" . $rows[$dbid ]['person'] . "</td>
                  <td>" . $rows[$dbid ]['time'] . "</td>
                  <td>" . $rows[$dbid ]['event'] . "</td></tr>";
        $lineid++;
        $dbid = array_shift($rowids);
        $rowdateymd = $rows[$dbid ]['date'];
        $qty++;
      } // close while ($rowdateymd == $dayymd)
    } // close if ($rowdateymd == $dayymd)
    else {
      $db0 = 0;
      $html .= "<tr data-id='$db0' data-dayymd='$dayymd'>
                <td style='$daystyle'>$daymdy</td><td>$dayofweek</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
      $lineid++;
    } // close else
    $dayunix += $unixdaydelta;
    $dayymd = date('Y-m-d', $dayunix);
    $daymdy = date('n/j/y', $dayunix);
    $dayofweek = date('D', $dayunix);
  } // close while ($dayunix < $lastdateunix)

  $html .= '</table>';
  return $html;
} // close public function getEvents()

public function getEditevent($rowid) {
  global $dbaccess, $utils;
  $row = $dbaccess->getRow($rowid);
  
  $html .= "Person:<br>";
  if ($row['person'] == 'Marcia')
       $html .= "<input type='radio' name='person' value='Marcia' checked>Marcia<br>";
  else $html .= "<input type='radio' name='person' value='Marcia' >Marcia<br>";
  if ($row['person'] == 'John')
       $html .= "<input type='radio' name='person' value='John' checked>John<br>";
  else $html .= "<input type='radio' name='person' value='John' >John<br>";
  if ($row['person'] == 'Both')
       $html .= "<input type='radio' name='person' value='Both' checked>Both<br>";
  else $html .= "<input type='radio' name='person' value='Both' >Both<br><br>";

  $html .= "Time&nbsp;&nbsp;&nbsp;<input type='text' name='time' value='{$row['time']}'/><br><br>";
  $html .= "Event&nbsp;&nbsp;<input type='text' name='event' value='{$row['event']}' size='45' /><br><br>";

  $html .= "<div class='button' id='submitbtnupdate'>Submit</div><br>";
  $html .= "<div class='button' id='cancelbtn'>Cancel</div>";

  $html .= "<input type='hidden' name='rowid' value='$rowid' />";

  return $html;
} // close getEditevent()

} // close class readControl
?>