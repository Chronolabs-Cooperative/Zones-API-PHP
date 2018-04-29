<?php
/**
 * Email Account Propogation REST Services API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         emails-api
 * @since           1.1.11
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         1.1.11
 * @description		A REST API for the creation and management of emails/forwarders and domain name parks for email
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/Emails-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 * 
 */


/**
 * If non-empty, only this user can access this installer
 */
define('INSTALL_USER', '');
define('INSTALL_PASSWORD', '');

define('API_INSTALL', 1);

function fatalPhpErrorHandler($e = null) {
    $messageFormat = '<br><div>Fatal %s %s file: %s : %d </div>';
    $exceptionClass = '\Exception';
    $throwableClass = '\Throwable';
    if ($e === null) {
        $lastError = error_get_last();
        if ($lastError['type'] === E_ERROR) {
            // fatal error
            printf($messageFormat, 'Error', $lastError['message'], $lastError['file'], $lastError['line']);
        }
    } elseif ($e instanceof $exceptionClass || $e instanceof $throwableClass) {
        /** @var $e \Exception */
        printf($messageFormat, get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());
    }
}
register_shutdown_function('fatalPhpErrorHandler');
set_exception_handler('fatalPhpErrorHandler');

// options for mainfile.php
if (empty($apiOption['hascommon'])) {
    $apiOption['nocommon'] = true;
    session_start();
}
@include dirname(__DIR__) . '/mainfile.php';
if (!defined('API_ROOT_PATH')) {
    define('API_ROOT_PATH', str_replace("\\", '/', realpath('../')));
}
if (!defined('API_URL')) {
    define('API_URL', (isset($_SERVER['HTTPS'])?'https://':'http://') . $_SERVER['HTTP_HOST']);
}

/*
error_reporting( 0 );
if (isset($apiLogger)) {
    $apiLogger->activated = false;
}
error_reporting(E_ALL);
$apiLogger->activated = true;
*/

date_default_timezone_set(@date_default_timezone_get());
include './class/installwizard.php';
include_once '../include/version.php';
include_once './include/functions.php';
include_once '../class/module.textsanitizer.php';
include_once '../class/libraries/vendor/autoload.php';

$pageHasHelp = false;
$pageHasForm = false;

$wizard = new APIInstallWizard();
if (!$wizard->xoInit()) {
    exit();
}

if (!@is_array($_SESSION['settings'])) {
    $_SESSION['settings'] = array();
}
