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


require_once './include/common.inc.php';
defined('API_INSTALL') || die('API Installation wizard die');

$pageHasForm = false;

$content = '';
include "./language/{$wizard->language}/welcome.php";

$writable = '<div class="alert alert-warning"><ul style="list-style: none;">';
foreach ($wizard->configs['writable'] as $key => $value) {
    if (is_dir('../' . $value)) {
        $writable .= '<li><span class="fa fa-fw fa-folder-open-o"></span> <strong>' . $value . '</strong></li>';
    } else {
        $writable .= '<li><span class="fa fa-fw fa-file-code-o"></span> <strong>' . $value . '</strong></li>';
    }
}
$writable .= '</ul></div>';

$api_trust = '<div class="alert alert-warning"><ul style="list-style: none;">';
foreach ($wizard->configs['apiPathDefault'] as $key => $value) {
    $api_trust .= '<li><span class="fa fa-fw fa-folder-open-o"></span> <strong>' . $value . '</strong></li>';
}
$api_trust .= '</ul></div>';

$writable_trust = '<div class="alert alert-warning"><ul style="list-style: none;">';
foreach ($wizard->configs['tmpPath'] as $key => $value) {
    $writable_trust .= '<li><span class="fa fa-fw fa-folder-open-o"></span> <strong>' . $wizard->configs['apiPathDefault']['tmp'] . '/' . $key . '</strong></li>';
    if (is_array($value)) {
        foreach ($value as $key2 => $value2) {
            $writable_trust .= '<li><span class="fa fa-fw fa-folder-open-o"></span> <strong>' . $wizard->configs['apiPathDefault']['tmp'] . '/' . $key . '/' . $value2 . '</strong></li>';
        }
    }
}
$writable_trust .= '</ul></div>';

$content = sprintf($content, $writable, $api_trust, $writable_trust);

include './include/install_tpl.php';

if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'dbreport.html'))
{
    unlink(__DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'dbreport.html');
    require_once dirname(__DIR__) . '/class/apilists.php';
    $files = APILists::getFileListAsArray(__DIR__ . DIRECTORY_SEPARATOR . 'sql');    
    foreach($files as $key => $file)
        if (substr($file, strlen($file)-3,3) == 'ran')
            rename(__DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . $file, __DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . substr($file,0,strlen($file)-4));
}
