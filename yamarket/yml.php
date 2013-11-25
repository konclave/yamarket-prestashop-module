<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/yamarket.php');

$yamarket = new yamarket();
$yamarket->generate(Tools::GetValue('cron'));