<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Set the alias to be used when accessing the admin area
define('ADMIN_ALIAS', 'admin');
define('FRONTEND_MODULE_ALIAS', 'fp_module');
define('FILE_DIR', 'files');

define('TABLE_DATE_FORMAT', 'jS M Y, H:i');

define('OPEN_TAG', '[*');
define('CLOSE_TAG', '*]');
define('ESCAPED_OPEN_TAG', '[\*');
define('ESCAPED_CLOSE_TAG', '*\]');

define('TAG_REGEX', '/' . preg_quote(OPEN_TAG, '/') . '[ \t]*((((?:[a-z][a-z0-9_]*))\\.)?((?:[a-z][a-z0-9_]*))(?:\(.*\)))[ \t]*' . preg_quote(CLOSE_TAG, '/') . '/');

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