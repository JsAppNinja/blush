<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define("IS_TEST", strpos($_SERVER['SERVER_NAME'], 'scmreview') > 0);
include_once(APPPATH.'version.php');
define("USER_TYPE_CUSTOMER", 1);
define("USER_TYPE_COUNSELOR", 2);
define("USER_TYPE_ADMIN", 99);
define("CREDITS_COUNSELING", 2);
define("CREDITS_DIARY", 1);
define("PRICE_COUNSELING", 100);
define("PRICE_DIARY", 50);
define("PRICE_CREDIT", 50);

define("IMG_SIZE_SM", 50);
define("IMG_SIZE_MD", 160);
define("IMG_SIZE_LG", 230);

define("TRANSACTION_COUNSELOR_TYPE_DIARY", 1);
define("TRANSACTION_COUNSELOR_PAYOUT_DIARY", 10);
define("TRANSACTION_COUNSELOR_TYPE_VIDEO_SESSION", 2);
define("TRANSACTION_COUNSELOR_PAYOUT_VIDEO_SESSION", 20);

define("UPCOMING_EVENT_DELAY", 60);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */