<?php

namespace TODOS;

require_once '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config.php';
require_once APP_PATH . 'lib' . DS .'autoload.php';

use TODOS\LIB\AppSessionHandler;
use TODOS\LIB\FrontController;

ob_start();
##################################################################################################
$session = new AppSessionHandler();
$session->start();
##################################################################################################

(new FrontController($session))->dispatch();

$session->checkSessionValidity(); 
ob_end_flush();