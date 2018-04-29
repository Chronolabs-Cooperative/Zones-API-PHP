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
defined('API_ROOT_PATH') || exit('Restricted access');

return $config = array(
    'extensions' => array(
        'iframe' => 0,
        'image' => 1,
        'flash' => 1,
        'youtube' => 1,
        'mp3' => 0,
        'wmp' => 0,
        // If other module is used, please modify the following detection and 'link' in /wiki/config.php
        'wiki' => is_dir(API_ROOT_PATH . '/modules/mediawiki/'),
        'mms' => 0,
        'rtsp' => 0,
        'soundcloud' => 0, //new in API 2.5.7
        'ul' => 1,
        'li' => 1),
    'truncate_length' => 60,
    // Filters XSS scripts on display of text
    // There is considerable trade-off between security and performance
    'filterxss_on_display' => false);
