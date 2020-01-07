#!/usr/bin/php
<?php
include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'startup.php';

Elrond\Core\Application::execute($argv);
