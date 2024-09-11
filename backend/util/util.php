<?php

define("HR_ROLE", "1");
define("EMPLOYEE_ROLE", "2");
define("ACTIVE_SESSION", 1);

function dd($value)
{
	echo "<pre>";

	var_dump($value);

	echo "</pre>";
	die();
}