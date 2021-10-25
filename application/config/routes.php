<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'main';
$route['404_override'] = 'main';
$route['translate_uri_dashes'] = FALSE;
$route['online-exam'] = 'main/user_online_exam';
$route['logout'] = 'main/logout';
$route['updateanswers'] = 'main/update_answers';

