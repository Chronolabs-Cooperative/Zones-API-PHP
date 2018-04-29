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
$pageHasHelp = true;

$vars =& $_SESSION['settings'];

$hostConnectPrefix = empty($vars['DB_PCONNECT']) ? '' : 'p:';
$link = mysqli_connect($hostConnectPrefix.$vars['DB_HOST'], $vars['DB_USER'], $vars['DB_PASS']);
if (0 !== $link->connect_errno) {
    $error = ERR_NO_DBCONNECTION .' (' . $link->connect_errno . ') ' . $link->connect_error;
    $wizard->redirectToPage('-1', $error);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['charset']) && @$_GET['action'] === 'updateCollation') {
    echo xoFormFieldCollation('DB_COLLATION', $vars['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $link, $_GET['charset']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = array('DB_NAME', 'DB_CHARSET', 'DB_COLLATION', 'DB_PREFIX');
    foreach ($params as $name) {
        $vars[$name] = isset($_POST[$name]) ? $_POST[$name] : '';
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($vars['DB_NAME'])) {
    $dbName   = mysqli_real_escape_string($link, $vars['DB_NAME']);
    $error    = validateDbCharset($link, $vars['DB_CHARSET'], $vars['DB_COLLATION']);
    $db_exist = true;
    if (empty($error)) {
        if (!@mysqli_select_db($link, $dbName)) {
            // Database not here: try to create it
            $result = mysqli_query($link, 'CREATE DATABASE `' . $dbName . '`');
            if (!$result) {
                $error    = ERR_NO_DATABASE;
                $db_exist = false;
            }
        }
        if ($db_exist && $vars['DB_CHARSET']) {
            $sql = 'ALTER DATABASE `' . $dbName . '` DEFAULT CHARACTER SET '
                   . mysqli_real_escape_string($link, $vars['DB_CHARSET'])
                   . ($vars['DB_COLLATION'] ? ' COLLATE ' . mysqli_real_escape_string($link, $vars['DB_COLLATION']) : '');
            if (!mysqli_query($link, $sql)) {
                $error = ERR_CHARSET_NOT_SET . $sql;
            }
        }
    }
    if (empty($error)) {
        $wizard->redirectToPage('+1');
        exit();
    }
}

if (@empty($vars['DB_NAME'])) {
    // Fill with default values
    $vars = array_merge($vars, array(
                                 'DB_NAME'      => '',
                                 'DB_CHARSET'   => 'utf8',
                                 'DB_COLLATION' => '',
                                 'DB_PREFIX'    => 'x' . substr(md5(time()), 0, 3)));
}

ob_start();
?>
<?php if (!empty($error)) {
    echo '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> ' . htmlspecialchars($error) . "</div>\n";
} ?>

    <script type="text/javascript">
        function setFormFieldCollation(id, val) {
            $.get('<?php echo $_SERVER['PHP_SELF']; ?>', { action: 'updateCollation', charset: val } )
                .done(function( tmp ) {
                    $('#'+id).html(tmp);
                });
        }
    </script>

    <div class="panel panel-info">
        <div class="panel-heading"><?php echo LEGEND_DATABASE; ?></div>
        <div class="panel-body">

        <?php echo xoFormField('DB_NAME', $vars['DB_NAME'], DB_NAME_LABEL, DB_NAME_HELP); ?>
        <?php echo xoFormField('DB_PREFIX', $vars['DB_PREFIX'], DB_PREFIX_LABEL, DB_PREFIX_HELP); ?>
        <?php echo xoFormFieldCharset('DB_CHARSET', $vars['DB_CHARSET'], DB_CHARSET_LABEL, DB_CHARSET_HELP, $link); ?>
        <?php echo xoFormBlockCollation('DB_COLLATION', $vars['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $link, $vars['DB_CHARSET']); ?>
        </div>
    </div>

<?php
$content = ob_get_contents();
ob_end_clean();
include './include/install_tpl.php';
