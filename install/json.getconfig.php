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

require_once __DIR__ . '/include/common.inc.php';
require_once API_ROOT_PATH . '/class/apilists.php';
include_once dirname(__DIR__) . '/mainfile.php';
include_once __DIR__ . '/class/dbmanager.php';

defined('API_INSTALL') || die('API Installation wizard die');

header('Content-type: application/json');
file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'tmp.txt', print_r($_REQUEST, true));
if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'assets' .  DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . $_REQUEST['folder'] . DIRECTORY_SEPARATOR . $_REQUEST['typal']))
    $article = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'assets' .  DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . $_REQUEST['folder'] . DIRECTORY_SEPARATOR . $_REQUEST['typal']);
else 
    die(json_encode(array('article' => "<font style='color:rgb(250,10,10);'>Error File not found:</font> /install" . DIRECTORY_SEPARATOR . 'assets' .  DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . $_REQUEST['folder'] . DIRECTORY_SEPARATOR . $_REQUEST['typal'])));
$article = str_replace('%dbuser', API_DB_USER, $article);
$article = str_replace('%dbhost', API_DB_HOST, $article);
$article = str_replace('%dbpassword', API_DB_PASS, $article);
$article = str_replace('%dbname', API_DB_NAME, $article);
echo json_encode(array('article' => $article));
