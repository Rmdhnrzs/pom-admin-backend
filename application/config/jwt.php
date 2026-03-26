<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config['jwt_secret'] = 'my_super_secret_key_very_long_1234567890!@#';
$config['jwt_algorithm'] = 'HS256';
$config['jwt_expire'] = 60 * 60 * 24;