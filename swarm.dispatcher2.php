#!/usr/bin/php
<?php

// $Source$
//
// $Log$

// Requires

require_once 'swarm.utils.php';

// Constants

$SLURM_SBATCH = "/usr/data/ahnapee/src/slurm-2.6.6-2/src/sbatch -n1";

$EXEC = "$BIN_DIR/swarm_sbatch2.sh";

$PREFIX = "DISPATCHER";

// Parameters

$EMAIL = $argv[1];
$XINIT = $argv[2];
$YINIT = $argv[3];
$ZINIT = $argv[4];
$VXINIT = $argv[5];
$VYINIT = $argv[6];
$VZINIT = $argv[7];
$SIGPOS = $argv[8];
$SIGVEL = $argv[9];
$NPARTICLES = $argv[10];
$POTENTIAL = $argv[11];
$WOLFIRE_DISK = $argv[12];
$WOLFIRE_BULGE = $argv[13];
$WOLFIRE_HALO = $argv[14];
$T_TOTAL = $argv[15];
$TIMESTEPS = $argv[16];
$DRAG_OPTN = $argv[17];
$VFIELD = $argv[18];
$VZERO = $argv[19];
$VRSC = $argv[20];
$DENFIELD = $argv[21];
$NHCEN = $argv[22];
$NHCEN2 = $argv[23];
$RSCPOW = $argv[24];
$ALPHAPOW = $argv[25];
$NHDISK1 = $argv[26];
$RSCDISK1 = $argv[27];
$ZSCDISK1 = $argv[28];
$NHDISK2 = $argv[29];
$RSCDISK2 = $argv[30];
$ZSCDISK2 = $argv[31];
$NHDISK3 = $argv[32];
$RSCDISK3 = $argv[33];
$ZSCDISK3 = $argv[34];
$COLDEN = $argv[35];
$SIGDEN = $argv[36];
$TOL = $argv[37];

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

  $swarm_out = exec("$EXEC $BASE_DIR $EMAIL $XINIT $YINIT $ZINIT $VXINIT $VYINIT $VZINIT $SIGPOS $SIGVEL".
                    " $NPARTICLES $T_TOTAL $TIMESTEPS $POTENTIAL $WOLFIRE_DISK $WOLFIRE_BULGE $WOLFIRE_HALO".
                    " $DRAG_OPTN $VFIELD $VZERO $VRSC $DENFIELD $NHCEN $NHCEN2 $RSCPOW $ALPHAPOW". 
                    " $NHDISK1 $RSCDISK1 $ZSCDISK1 $NHDISK2 $RSCDISK2 $ZSCDISK2 $NHDISK3 $RSCDISK3 $ZSCDISK3".
                    " $COLDEN $SIGDEN $TOL $job_id", $output, $ret_val); 


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
