#!/usr/bin/php
<?php

// $Source$
//
// $Log$

// Requires

require_once 'swarm.utils.php';

// Constants

$SLURM_SBATCH = "/usr/data/ahnapee/src/slurm-2.6.6-2/src/sbatch -n1";

$EXEC = "$BIN_DIR/swarm_sbatch.sh";

$PREFIX = "DISPATCHER";

// Parameters

$TXTFILE = $argv[1];
$EMAIL = $argv[2];
$OUTPUT_FMT = $argv[3];
$POTENTIAL = $argv[4];
$WOLFIRE_DISK = $argv[5];
$WOLFIRE_BULGE = $argv[6];
$WOLFIRE_HALO = $argv[7];
$T_TOTAL = $argv[8];
$TIMESTEPS = $argv[9];

$error = '';

// Validate the parameters
/*
$error = '';

if(!preg_match('/^[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$/', $MASS)) {

  $error = 'the initial mass is invalid';

}
else if(!($MASS >= 0.1 && $MASS <= 100)) {

  $error = 'the initial mass must be in the range 0.1 to 100';

}
else if(!($METAL == '0001' ||
	  $METAL == '0003' ||
	  $METAL == '001' ||
	  $METAL == '004' ||
	  $METAL == '01' ||
	  $METAL == '02' ||
	  $METAL == '03')) {
	    
  $error = 'the metallicity is invalid';

}
else if(!preg_match('/^[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$/', $AGE)) {

  $error = 'the maximum age is not valid';

}
else if(!preg_match('/^[0-9]+$/', $NUMBER)) {

  $error = 'the maximum number of steps is not valid';

}
*/

if(!preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $EMAIL)) {

  $error = 'the supplied email address is not valid';

}

if($error) {

  write_log($PREFIX, "Validation error");

  print "An error was encountered: $error\n";

  exit(1);

}

// Create a temporary directory

$tmpdir = tmpdir();

if(chdir($tmpdir)) {

  // Submit the request

  $output=NULL;
  $job_id = rand(1,99999);

  $swarm_out = exec("$EXEC $BASE_DIR $TXTFILE $EMAIL $OUTPUT_FMT $T_TOTAL $TIMESTEPS $POTENTIAL $WOLFIRE_DISK $WOLFIRE_BULGE $WOLFIRE_HALO $job_id", 
                    $output, $ret_val);

  if(!$ret_val) {

    // Parse the job ID

    //preg_match('/([0-9]+)/', $slurm_out, $matches);

    //$job_id = $matches[1];

    write_log($PREFIX, "Submitted with id $job_id in directory $tmpdir");

    print "The request was succesfully submitted (id $job_id). When the calculation is complete, ".
      "an email will be sent to EMAIL with instructions on how to download the results\n";

  }
  else {
    
    write_log($PREFIX, "Submission error from SLURM: $swarm_out ($ret_val)");

    print "Unable to process the request due to a server problem\n";

    exit(1);

  }

}
else {

  write_log($PREFIX, "Unable to chdir into temporary directory $tmpdir");

  print "Unable to process the request due to a server problem\n";

  exit(1);

}

// Create a temporary directory

function tmpdir() {

  global $TMP_DIR;

  $tmpfile=tempnam($TMP_DIR, '');

  if (file_exists($tmpfile)) { unlink($tmpfile); }

  mkdir($tmpfile);

  if (is_dir($tmpfile)) { return $tmpfile; }

}

?>
