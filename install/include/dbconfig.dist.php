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

// Database
// Choose the database to be used
define('API_DB_TYPE', 'mysqli');

// Set the database charset if applicable
if (defined('API_DB_CHARSET')) {
    die('Restricted Access');
}
define('API_DB_CHARSET', '');

// Table Prefix
// This prefix will be added to all new tables created to avoid name conflict in the database. If you are unsure, just use the default "api".
define('API_DB_PREFIX', 'api');

// Database Hostname
// Hostname of the database server. If you are unsure, "localhost" works in most cases.
define('API_DB_HOST', 'localhost');

// Database Username
// Your database user account on the host
define('API_DB_USER', '');

// Database Password
// Password for your database user account
define('API_DB_PASS', '');

// Database Name
// The name of database on the host. The installer will attempt to create the database if not exist
define('API_DB_NAME', '');

// Use persistent connection? (Yes=1 No=0)
// Default is "Yes". Choose "Yes" if you are unsure.
define('API_DB_PCONNECT', 0);
