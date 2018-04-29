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

setcookie('xo_install_lang', 'english', null, null, null);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['lang'])) {
    $lang = $_REQUEST['lang'];
    setcookie('xo_install_lang', $lang, null, null, null);

    $wizard->redirectToPage('+1');
    exit();
}

$_SESSION['settings'] = array();
setcookie('xo_install_user', '', null, null, null);

$pageHasForm = true;
$title = LANGUAGE_SELECTION;
$label = 'Available Languages';
$content =<<<EOT
<div class="form-group col-md-4">
    <label for="lang" class="control-label">{$label}</label>
    <select name="lang" id="lang" class="form-control">
EOT;

$languages = getDirList('./language/');
foreach ($languages as $lang) {
    $sel = ($lang == $wizard->language) ? ' selected' : '';
    $content .= "<option value=\"{$lang}\"{$sel}>{$lang}</option>\n";
}
$content .=<<<EOB
    </select>
</div><div class="clearfix"></div>
EOB;


include './include/install_tpl.php';
