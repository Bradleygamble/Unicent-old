<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$url = @$_GET['url'];

require_once ( ROOT . DS . 'system' . DS . 'library' . DS . 'wrapper.php');