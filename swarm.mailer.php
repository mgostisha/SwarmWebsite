#!/usr/bin/php
<?php

// Set Include Path for Mail.php
set_include_path(".:/usr/lib/php");

// Requires

require_once "Mail.php";

require_once "swarm.utils.php";

// Constants

$SWARM_OUTPUT = "swarm.out";

$FILE_SERVER = "astro.wisc.edu";
$FILE_USER = "gostisha";
$FILE_DIR = "/d/www/$FILE_USER/resource/download/swarm";

$WEB_PAGE = "http://www.astro.wisc.edu/~gostisha/swarm.html";
$WEB_DIR = "http://www.astro.wisc.edu/~gostisha/resource/download/swarm";

$SCP_COMMAND = "/usr/bin/scp";

$EMAIL_SERVER = "astro.wisc.edu";
$FROM_EMAIL = "gostisha@astro.wisc.edu";

$PREFIX = "MAILER";

// Parameters

$TO_EMAIL = $argv[1];
$JOB_ID = $argv[2];

// Create the zipfile

$filename = sprintf("swarm_%05d.zip", $JOB_ID);

$zip = new ZipArchive();

if($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
  write_log($PREFIX, "Cannot create zip archive $filename for id $JOB_ID");
  exit(1);
}

// Add files

foreach(glob("*.txt") as $add_filename) {
  $zip->addFile($add_filename);
}

foreach(glob("*.pkl") as $add_filename) {
	$zip->addFile($add_filename);
}

$zip->close();


// Copy the zipfile to the server

$result = system("mv $filename $FILE_DIR", $retval);

if($retval != 0) {
  write_log($PREFIX, "Cannot move $filename to $FILE_SERVER:$FILE_DIR/$filename for id $JOB_ID");
  exit(1);
}

// Send the notification email

$smtp =& Mail::factory('smtp', array('host' => $EMAIL_SERVER));

$headers = array('From' => "Swarm Server <$FROM_EMAIL>", 
		 'To' => $TO_EMAIL, 
		 'Subject' => "Your Swarm calculation (id $JOB_ID) has completed");

$output = file_get_contents($SWARM_OUTPUT);

$body = <<<EOF
Your Swarm calculation (id $JOB_ID) has completed. The files located in the
zip file are:

$output
You can retrieve the zip file containing the calculation results
from the following location:

$WEB_DIR/$filename

You can reply to this email to report any problems you encounter.
EOF;

$mail = $smtp->send($TO_EMAIL, $headers, $body);

if(PEAR::isError($mail)) {
 write_log($PREFIX, "Cannot send mail for id $JOB_ID: ".$mail->getMessage());
  exit(1);
}

// Finish

write_log($PREFIX, "Completed OK for id $JOB_ID");
