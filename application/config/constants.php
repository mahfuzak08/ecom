<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

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
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
 | Template used constants
 */
defined('TEMPLATES_DIR')       OR define('TEMPLATES_DIR', APPPATH.'views/templates/'); // directory of templates
defined('VIEWS_DIR')           OR define('VIEWS_DIR', APPPATH.'views/'); // directory of views

/*
 | Define User Access
 */
define("INVENTORY", "100");
define("PUBLISH_PRODUCT", "101");
define("PRODUCTS", "102");
define("PRODUCTS_EDIT", "103");
define("PRODUCTS_DELETE", "104");
define("PRODUCTS_ACTIVE_INACTIVE", "105");
define("SHOP_CATEGORIES", "110");
define("SHOP_CATEGORIES_ADD", "111");
define("SHOP_CATEGORIES_DELETE", "112");

define("ECOM", "150");
define("DELIVERY_LOCATION", "151");
define("DELIVERY_LOCATION_ADD", "152");
define("DELIVERY_LOCATION_EDIT", "153");
define("DELIVERY_LOCATION_DELETE", "154");
define("WISH_LISTS", "155");
define("ORDERS", "160");
define("ORDERS_GET", "161");
define("ORDERS_CONBTN", "162");
define("ORDERS_REJBTN", "163");
define("ORDERS_PROBTN", "164");
define("ORDERS_SETTINGS", "165");
define("DISCOUNT_CODES", "190");

define("SALES", "200");
define("SALES_UPDATE", "201");
define("SALES_INV_SETUP", "214");
define("CUSTOMER", "215");
define("CUSTOMER_EDIT", "216");
define("CUSTOMER_DELETE", "217");
define("CUSTOMER_TRAN_DELETE", "217");

define("PURCHASE", "230");
define("NEW_VENDOR", "240");
define("VENDOR_MANAGE", "241");

define("REPORTS", "250");

define("OFFICE", "270");
define("ACCOUNTS", "271");
define("ACCOUNTS_DELETE", "272");
define("ACCOUNTS_TRANS_ADD", "273");
define("ACCOUNTS_TRANS_EDIT", "274");
define("ACCOUNTS_TRANS_DELETE", "275");
define("EXPENSE", "280");
define("EXPENSE_ADD", "281");
define("EXPENSE_EDIT", "282");
define("EXPENSE_DELETE", "283");
define("EXPENSE_TRANS_ADD", "284");
define("EXPENSE_TRANS_EDIT", "285");
define("EXPENSE_TRANS_DELETE", "286");
define("SMS", "287");
define("SUBSCRIBED_EMAILS", "290");
define("TEMPLATES", "295");
define("ADMIN_USERS", "300");
define("ACTIVITY_HISTORY", "310");
define("VISITOR_HISTORY", "311");

define("SETTINGS", "320");
define("DBA", "340");
define("STYLING", "350");
define("LANGUAGES", "351");
define("TITLES_DESCRIPTIONS", "352");
define("ACTIVE_PAGES", "353");
define("FILE_MANAGER", "354");
define("CLIENTS", "360");