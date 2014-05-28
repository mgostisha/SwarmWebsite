<?php

	echo "<link type='text/css' rel='stylesheet' href='../swarm_stylesheet.css' />";
	$errMsg = $_GET['errMsg'];

	echo "<br>";
	echo "<h1> SWARM </h1>";
	echo "<br>";
	echo "<p id='top'> Your parameters were not passed successfully. Error Message: </p>".$errMsg;
	echo "<p id=bottom> Click <a href='http://www.astro.wisc.edu/~gostisha/swarm.html'>here</a> to return to the Swarm website.</p>";
	
?>