<?php

/*
 *	Dev config for Unicent
 *
 *
 */

define('DEV_LOGS', TRUE);

class Dev
{

	function devlog($string)
	{

		echo '<script>console.log("Unicent DEVLOG: ' . $string . '");</script>';

	}

}