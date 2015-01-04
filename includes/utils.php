<?php

class clsUtils {

public function log() {
  global $fileroot;
  $logfile = $fileroot . 'logs/debuglog.txt';
  $fh = fopen($logfile, 'a') or die("can't open file");
  $args = func_get_args ();
  fwrite($fh, $args[0] . "<<<\n");
  for ( $i=1;$i<count($args);$i++) $lineout .= " >>>" . $args[$i] . "<<<\n";
  fwrite($fh, $lineout);
  fclose($fh);
} // close log

public function clearLog() {
  global $fileroot;
  $logfile = $fileroot . 'logs/debuglog.txt';
  $fh = fopen( $logfile, 'w' );
  fclose($fh);
} // close 

} // close class clsUtils
?>