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



define('_API_FATAL_MESSAGE', 'Fatal:~ %s!');
require_once __DIR__ . '/include/common.inc.php';
defined('API_INSTALL') || die('API Installation wizard die');

$pageHasForm = false;
$pageHasHelp = false;

$vars =& $_SESSION['settings'];

include_once '../mainfile.php';

require_once __DIR__ . '/class/dbmanager.php';
$dbm = new Db_manager();

if (!$dbm->isConnectable()) {
    $wizard->redirectToPage('-3');
    exit();
}

require_once API_ROOT_PATH . '/class/apilists.php';
$files = APILists::getFileListAsArray(__DIR__ . DIRECTORY_SEPARATOR . 'sql');
foreach($files as $key => $file)
    if (substr($file, strlen($file)-3,3) != 'sql')
        unset($files[$key]);
sort($files, SORT_DESC);

if (count($files)==0) {
    $content = '<div class="alert alert-info"><span class="fa fa-info-circle text-info"></span> ' . API_TABLES_FOUND . '</div>';
} else {
    $content = "<script>
    // Loads Creation of Database
    $(document).ready(function() {
        // Redirects to URL
        function redirect(data){
            if (data.length>0) {
                $('#refreshurl').attr('content', '0;url='.data);
                $('#refreshurl').attr('http-equiv', 'Refresh');
                $.ajax({
                    url: data,
                    headers: {'Location': data}
                });
            }
        }
        // Updates DIV IDs with HTML
        function updateDiv(){
            $.ajax({
                url: \"" . API_URL . "/install/json.createdatabase.php\",
                dataType: \"json\",
                cache: false,
                success: function(data) {
                    $('#dbreport').html(data.dbreport);
                    $('#buttons').html(data.buttons);
                    $('#leftsql').html(data.leftsql);
                    $('#totalsql').html(data.totalsql);
                    $('#endmsg').html(data.endmsg);
                    if (data.refreshurl.length>0) {
                        redirect(data.refreshurl);
                    }
                }             
            });              
        }
        updateDiv();
        ".(count($files)>0?"
        setInterval(updateDiv, 169);
        $('#buttons').html('&nbsp;');
        ":"         $.ajax({ url: ".API_URL . "/install/page_siteinit.php', headers: { 'Location': '".API_URL . "/install/page_siteinit.php' }; });   ")."
    });
</script>
<div class=\"alert alert-success\"><h2><span class=\"fa fa-check text-success\" id='leftsql'>&nbsp;</span> / <span class=\"fa fa-check text-success\" id='totalsql'>&nbsp;</span> ~ <span class=\"text-success\" id='endmsg'>&nbsp;</span></h2></div>
<div class=\"alert alert-success\"><span class=\"fa fa-check text-success\"></span> " . API_TABLES_CREATED
        . "</div><div class=\"well\" id=\"dbreport\">&nbsp;</div>";
}

include './include/install_tpl.php';
