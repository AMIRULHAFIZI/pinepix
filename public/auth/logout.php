<?php
session_start();
require_once __DIR__ . '/../../config/autoload.php';

$auth = new Auth();
$auth->logout();

Helper::redirect(BASE_URL . 'index.php');
