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

include_once './include/functions.php';
include_once '../class/apilists.php';

$pageHasForm = true;
$pageHasHelp = true;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && @$_GET['var'] && @$_GET['action'] === 'checkfile') {
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wizard->redirectToPage('+1');
    return 302;
}
ob_start();

?>
    <div class="panel panel-info">
        <div class="panel-heading"><?php echo API_CONFIG_POWERDNS; ?></div>
        <div class="panel-body">
        <div class="form-group">
    	    <label for="platform"><?php echo API_PLATFORM_LABEL; ?></label>
            <div class="xoform-help alert alert-info"><?php echo API_PLATFORM_HELP; ?></div>
            <select type="text" class="form-control" name="platform" id="platform" />
            	<option value="" id="emptyselect" class="emptyselect">(select server's operating system)</option>
            <?php foreach(APILists::getDirListAsArray(__DIR__ . '/assets/configs') as $folder) { 
                echo '                  <option value="'.$folder.'">' . $folder . '</option>\n';
            }?>
            </select>
           
           <div class="form-group">
                <div id="article" class="article">&nbsp;</div>
           </div>
           
        </div>
        
   </div>
   <script>
    $(document).ready(function(){ 
        $(function() {
            $( "#platform" ).change(function(  event  ) {
            	$( "option.emptyselect" ).replaceWith( "<!-- <option value=\"\" id=\"emptyselect\" class=\"emptyselect\">(select server's operating system)</option> -->" );
            	var folder = $( "#platform" ).val();
                $.ajax({
                    type:'POST',
                    url:'<?php echo API_URL; ?>/install/json.getconfig.php?folder='+folder+"&typal=powerdns.html",
                    success:function(data){
                        $("#article").html(data.article);
                    }
                });
            });
        });
    });
    </script>
<?php
$content = ob_get_contents();
ob_end_clean();

include './include/install_tpl.php';
