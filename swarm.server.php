#!/usr/bin/php
<?php

// $Source$
//
// $Log$

// Requires

require_once 'swarm.utils.php';

// Constants

$PORT = 11000;

$PREFIX = "SERVER";

// Create the listening stream

$listen_stream = stream_socket_server("tcp://0.0.0.0:$PORT", $errno, $errstr);

if (!$listen_stream) {

  write_log($PREFIX, "Error opening socket: $errstr ($errno)");
  exit(1);

} else {

  // Store the stream

  $streams[] = $listen_stream;

  write_log($PREFIX, "Listening on ".stream_socket_get_name($listen_stream, FALSE));

  // Now poll

  while (TRUE) {

    $changed_streams = $streams;

    // Select the streams that have changed

    $write_streams = NULL;
    $except_streams = NULL;

    $n_changed = stream_select($changed_streams, $write_streams, $except_streams, 1);

    if($n_changed === FALSE) {
      break;
    }

    // Process the streams

    foreach ($changed_streams as $changed_stream) {

      if($changed_stream === $listen_stream) {

	// Create a new stream

	$streams[] = stream_socket_accept($listen_stream);

	var_dump($streams);

      }
      else {

    // ---------------- EVERYTHING ABOVE SHOULD BE FINE, SERVER STREAMS, LISTENING, ETC. --------------------

	// Read a request from the stream

	$data = array();

	while($line = rtrim(fgets($changed_stream))) {

	  $line_data = explode(' ', $line, 2);

	  $key = $line_data[0];
	  $value = $line_data[1];

	  $data[$key] = $value != '' ? escapeshellarg($value) : "''";

	}

	if(array_key_exists('wolfire1', $data) && $data['wolfire1'] == "'on'") {
	  $data['wolfire1'] = "'yes'";
	}
	else {
	  $data['wolfire1'] = "'no'";
	}

	if(array_key_exists('wolfire2', $data) && $data['wolfire2'] == "'on'") {
	  $data['wolfire2'] = "'yes'";
	}
	else {
	  $data['wolfire2'] = "'no'";
	}

	if(array_key_exists('wolfire3', $data) && $data['wolfire3'] == "'on'") {
		$data['wolfire3'] = "'yes'";
	}
	else {
		$data['wolfire3'] = "'no'";
	}

	if(array_key_exists('potential', $data) && $data['potential'] == "'pointsrc'") {
		$data['wolfire1'] = "'no'";
		$data['wolfire2'] = "'no'";
		$data['wolfire3'] = "'no'";
	}

	if(array_key_exists('filename', $data)){

		write_log($PREFIX, "Request $data[filename] $data[email] $data[potential] $data[wolfire1]".
			" $data[wolfire2] $data[wolfire3] $data[t_total] $data[timesteps]");

		// Dispatch the request

		$response = exec("$BIN_DIR/swarm.dispatcher.php $data[filename] $data[email] $data[potential] $data[wolfire1]".
			" $data[wolfire2] $data[wolfire3] $data[t_total] $data[timesteps]");

	} else {

		write_log($PREFIX, "Request $data[email] $data[xinit] $data[yinit] $data[zinit] $data[vxinit] $data[vyinit]".
			" $data[vzinit] $data[sigpos] $data[sigvel] $data[nparticles] $data[potential] $data[wolfire1]".
			" $data[wolfire2] $data[wolfire3] $data[t_total] $data[timesteps]");

		$response = exec("$BIN_DIR/swarm.dispatcher2.php $data[email] $data[xinit] $data[yinit] $data[zinit] $data[vxinit] $data[vyinit]".
			" $data[vzinit] $data[sigpos] $data[sigvel] $data[nparticles] $data[potential] $data[wolfire1]".
			" $data[wolfire2] $data[wolfire3] $data[t_total] $data[timesteps]");
	}




	// Send the response

	fputs($changed_stream, $response);

	// Close the stream

	fclose($changed_stream);

	$del_key = array_search($changed_stream, $streams, TRUE);
	unset($streams[$del_key]);

      }

    }

    // Loop around for the next changed stream

  }

}

?>
