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

// include_once './class/dbmanager.php';
// RMV
// TODO: Shouldn't we insert specific field names??  That way we can use
// the defaults specified in the tmpbase...!!!! (and don't have problem
// of missing fields in install file, when add new fields to tmpbase)
function make_groups(&$dbm)
{
    return array();
}

/**
 * @param $dbm
 * @param $adminname
 * @param $hashedAdminPass
 * @param $adminmail
 * @param $language
 * @param $groups
 *
 * @return mixed
 */
function make_data(&$dbm, $settings, $language, $groups)
{
    $sql = array();
    $sql[] = "INSERT INTO `users` (`uid`, `name`, `uname`, `email`, `pass`, `url`, `api_avatar`, `api_regdate`, `actkey`, `hits`, `attachsig`, `timezone`, `last_login`, `last_online`, `api_mailok`) VALUES(1, '" . addslashes($settings['ADMIN_COMPANY']) . "', '" . addslashes($settings['ADMIN_UNAME']) . "', '" . addslashes($settings['ADMIN_EMAIL']) . "', '" . addslashes(md5($settings['ADMIN_PASS'])) . "', '" . addslashes(API_URL) . "', 'avatars/blank.gif', UNIX_TIMESTAMP(), '" . (chr(mt_rand(ord('a'), ord('z'))).chr(mt_rand(ord('a'), ord('z'))).chr(mt_rand(ord('A'), ord('Z'))).chr(mt_rand(ord('a'), ord('z'))).chr(mt_rand(ord('A'), ord('Z')))) . "', 0, 1, '" . date_default_timezone_get() . "', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 1)";
    $report = array();
    
    foreach($sql as $question)
        if (!$dbm->query($question))
            $report['failed'][] = "Failed SQL: $question;";
        else 
            $report['success'][] = "Success SQL: $question;";
        
    $out = array();
    if (count($report['failed'])) {
        $out[] = '<h2>Failed SQL Questions</h2>';
        $out[] = '<ul><li>'.implode('</li>\n<li>', $report['failed']).'</li></ul>';
    }
    if (count($report['success'])) {
        $out[] = '<h2>Successful SQL Questions</h2>';
        $out[] = '<ul><li>'.implode('</li>\n<li>', $report['success']).'</li></ul>';
    }
    return implode("\n", $out);
}
