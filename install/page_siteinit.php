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

$pageHasForm = true;
$pageHasHelp = false;

$vars =& $_SESSION['siteconfig'];

$error =& $_SESSION['error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['settings']['ADMIN_COMPANY']  =   $vars['company']    = $_POST['company'];
    $_SESSION['settings']['ADMIN_UNAME']    =   $vars['adminname']  = $_POST['adminname'];
    $_SESSION['settings']['ADMIN_EMAIL']    =   $vars['adminmail']  = $_POST['adminmail'];
    $_SESSION['settings']['ADMIN_PASS']     =   $vars['adminpass']  = $_POST['adminpass'];
    $vars['adminpass2'] = $_POST['adminpass2'];
    $error              = array();

    if (empty($vars['company'])) {
        $error['company'][] = ERR_REQUIRED;
    }
    if (empty($vars['adminname'])) {
        $error['name'][] = ERR_REQUIRED;
    }
    if (empty($vars['adminmail'])) {
        $error['email'][] = ERR_REQUIRED;
    }
    if (empty($vars['adminpass'])) {
        $error['pass'][] = ERR_REQUIRED;
    }
    if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $vars['adminmail'])) {
        $error['email'][] = ERR_INVALID_EMAIL;
    }
    if ($vars['adminpass'] != $vars['adminpass2']) {
        $error['pass'][] = ERR_PASSWORD_MATCH;
    }
    if ($error) {
        $wizard->redirectToPage('+0');

        return 200;
    } else {
        $wizard->redirectToPage('+1');

        return 302;
    }
} else {
    include_once './class/dbmanager.php';
    $dbm = new Db_manager();

    if (!$dbm->isConnectable()) {
        $wizard->redirectToPage('dbsettings');
        exit();
    }

    $res = $dbm->query('SELECT COUNT(*) FROM ' . $dbm->db->prefix('users'));
    list($isadmin) = $dbm->db->fetchRow($res);
}

ob_start();

$extraSources = '<script type="text/javascript" src="./assets/js/zxcvbn.js"></script>';
if ($isadmin) {
    $pageHasForm = false;
    $pageHasHelp = false;
    echo "<div class='alert alert-warning'>" . ADMIN_EXIST . "</div>\n";
} else {
        echo '<script type="text/javascript">
                var desc = new Array();
                desc[0] = "' . PASSWORD_VERY_WEAK . '";
                desc[1] = "' . PASSWORD_WEAK . '";
                desc[2] = "' . PASSWORD_BETTER . '";
                desc[3] = "' . PASSWORD_MEDIUM . '";
                desc[4] = "' . PASSWORD_STRONG . '";
        </script>';

    ?>
    <div class="panel panel-info">
        <div class="panel-heading"><?php echo LEGEND_ADMIN_ACCOUNT; ?></div>
        <div class="panel-body">

        <?php
        echo '<div class="row"><div class="col-md-9">';
        echo xoFormField('company', $vars['company'], ADMIN_COMPANY_LABEL);
        if (!empty($error['company'])) {
            foreach ($error['company'] as $errmsg) {
                echo '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> ' . $errmsg . '</div>';
            }
        }
        
        echo xoFormField('adminname', $vars['adminname'], ADMIN_LOGIN_LABEL);
        if (!empty($error['name'])) {
            foreach ($error['name'] as $errmsg) {
                echo '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> ' . $errmsg . '</div>';
            }
        }

        echo xoFormField('adminmail', $vars['adminmail'], ADMIN_EMAIL_LABEL);
        if (!empty($error['email'])) {
            foreach ($error['pass'] as $errmsg) {
                echo '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> ' . $errmsg . '</div>';
            }
        }
        ?>

        <div id="password">
            <div id="passwordinput">
                <?php
                echo xoPassField('adminpass', '', ADMIN_PASS_LABEL);
                echo xoPassField('adminpass2', '', ADMIN_CONFIRMPASS_LABEL);
                if (!empty($error['pass'])) {
                    foreach ($error['pass'] as $errmsg) {
                        echo '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> ' . $errmsg . '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
            <div id="passwordmeter" class="well">
                <h4><?php echo PASSWORD_LABEL; ?></h4>

                <div id='passwordStrength' class='strength0'>
                    <span id='passwordDescription'><?php echo PASSWORD_DESC; ?></span>
                </div>


                <div id="passwordgenerator">
                    <label for='password_generator'><?php echo PASSWORD_GENERATOR; ?></label>
                    <input type="text" class="form-control" name="generated_pw" id="generated_pw" value=""  onclick="this.setSelectionRange(0, this.value.length); document.execCommand('copy');"><br>
                    <button type="button" class="btn btn-default" onclick="suggestPassword(16);">
                    <?php echo PASSWORD_GENERATE; ?></button>
                    <button type="button" class="btn btn-default" onclick="suggestPasswordCopy();">
                    <?php echo PASSWORD_COPY; ?></button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <?php

}
$content = ob_get_contents();
ob_end_clean();
$error = !empty($error);
include './include/install_tpl.php';
