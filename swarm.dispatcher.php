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
$POTENTIAL = $argv[3];
$WOLFIRE_DISK = $argv[4];
$WOLFIRE_BULGE = $argv[5];
$WOLFIRE_HALO = $argv[6];
$T_TOTAL = $argv[7];
$TIMESTEPS = $argv[8];
$DRAG_OPTN = $argv[9];
$VFIELD = $argv[10];
$VZERO = $argv[11];
$VRSC = $argv[12];
$RHOFIELD = $argv[13];
$RHOCEN = $argv[14];
$RHOCEN2 = $argv[15];
$RSCPOW = $argv[16];
$ALPHAPOW = $argv[17];
$RHODISK1 = $argv[18];
$RSCDISK1 = $argv[19];
$ZSCDISK1 = $argv[20];
$RHODISK2 = $argv[21];
$RSCDISK2 = $argv[22];
$ZSCDISK2 = $argv[23];
$RHODISK3 = $argv[24];
$RSCDISK3 = $argv[25];
$ZSCDISK3 = $argv[26];

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

  $output = NULL;
  $job_id = rand(1,99999);

  $swarm_out = exec("$EXEC $BASE_DIR $TXTFILE $EMAIL $T_TOTAL $TIMESTEPS $POTENTIAL $WOLFIRE_DISK $WOLFIRE_BULGE".
                    " $WOLFIRE_HALO $DRAG_OPTN $VFIELD $VZERO $VRSC $RHOFIELD $RHOCEN $RHOCEN2 $RSCPOW $ALPHAPOW". 
                    " $RHODISK1 $RSCDISK1 $ZSCDISK1 $RHODISK2 $RSCDISK2 $ZSCDISK2 $RHODISK3 $RSCDISK3 $ZSCDISK3 $job_id", 
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