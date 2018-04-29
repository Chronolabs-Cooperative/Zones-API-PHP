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
defined('API_INSTALL') || die('API Installation wizard die');

$pageHasForm = false;
$pageHasHelp = false;

$vars =& $_SESSION['settings'];

include_once dirname(__DIR__) . '/mainfile.php';
include_once __DIR__ . '/class/dbmanager.php';
$dbm = new Db_manager();

if (!$dbm->isConnectable()) {
    $wizard->redirectToPage('dbsettings');
    exit();
}
$res = $dbm->query('SELECT COUNT(*) FROM ' . $dbm->db->prefix('users'));
if (!$res) {
    $wizard->redirectToPage('dbsettings');
    exit();
}

list($count) = $dbm->db->fetchRow($res);
$process = ($count == 0);
$update  = false;

extract($_SESSION['siteconfig'], EXTR_SKIP);

include_once './include/makedata.php';
//$cm = 'dummy';
$wizard->loadLangFile('install2');

$licenseFile = API_ROOT_PATH . '/include/license.php';
$touched = touch($licenseFile);
if ($touched) {
    $licenseReport = '<div class="alert alert-success"><span class="fa fa-check text-success"></span> '
        . writeLicenseKey($vars) . '</div>';
} else {
    $licenseReport = '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> '
        . sprintf(LICENSE_NOT_WRITEABLE, $licenseFile) . '</div>';
}
$error = false;

$hashedAdminPass = password_hash($adminpass, PASSWORD_DEFAULT);

$content .= $licenseReport;

include './include/install_tpl.php';
