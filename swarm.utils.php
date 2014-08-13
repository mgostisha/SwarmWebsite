<?php

// Set the timezone

date_default_timezone_set('America/Chicago');

// Parameters

$BASE_DIR = "/usr/users/gostisha/home";
$BIN_DIR = "$BASE_DIR/bin";
$TMP_DIR = "$BASE_DIR/work";
$LOG_DIR = "$BASE_DIR/logs";

$LOG_FILE = "$LOG_DIR/swarm.log";

// Append a line to the log file

function write_log ($prefix, $text) {

  global $LOG_FILE;

  // Obtain a lock on the file

  $fp = fopen($LOG_FILE, "a");

  if(!$fp) {
    exit("Unable to open log file");
  }

  if(flock($fp, LOCK_EX)) {
    fwrite($fp, date("c")." [$prefix] $text\n");
  }
  else {
    exit("Unable to obtain lock on log file");
  }

  fclose($fp);

}
