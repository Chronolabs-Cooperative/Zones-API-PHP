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

/**
 * APIDatabaseFactory
 *
 * @package Kernel
 * @author  Kazumi Ono <onokazu@api.org>
 * @access  public
 */
class APIDatabaseFactory
{
    /**
     * APIDatabaseFactory constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get a reference to the only instance of database class and connects to DB
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * @static
     * @staticvar APIDatabase The only instance of database class
     * @return APIDatabase Reference to the only instance of database class
     */
    public static function getDatabaseConnection()
    {
        static $instance;
        if (!isset($instance)) {
            if (file_exists($file = API_ROOT_PATH . '/class/database/' . API_DB_TYPE . 'database.php')) {
                require_once $file;

                if (!defined('API_DB_PROXY')) {
                    $class = 'API' . ucfirst(API_DB_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'API' . ucfirst(API_DB_TYPE) . 'DatabaseProxy';
                }

                $apiPreload = APIPreload::getInstance();
                $apiPreload->triggerEvent('core.class.database.databasefactory.connection', array(&$class));

                $instance = new $class();
                $instance->setLogger(APILogger::getInstance());
                $instance->setPrefix(API_DB_PREFIX);
                if (!$instance->connect()) {
                    trigger_error('notrace:Unable to connect to database', E_USER_ERROR);
                }
            } else {
                trigger_error('notrace:Failed to load database of type: ' . API_DB_TYPE . ' in file: ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
            }
        }

        return $instance;
    }

    /**
     * Gets a reference to the only instance of database class. Currently
     * only being used within the installer.
     *
     * @static
     * @staticvar object  The only instance of database class
     * @return object Reference to the only instance of database class
     */
    public static function getDatabase()
    {
        static $database;
        if (!isset($database)) {
            if (file_exists($file = API_ROOT_PATH . '/class/database/' . API_DB_TYPE . 'database.php')) {
                include_once $file;
                if (!defined('API_DB_PROXY')) {
                    $class = 'API' . ucfirst(API_DB_TYPE) . 'DatabaseSafe';
                } else {
                    $class = 'API' . ucfirst(API_DB_TYPE) . 'DatabaseProxy';
                }
                unset($database);
                $database = new $class();
            } else {
                trigger_error('notrace:Failed to load database of type: ' . API_DB_TYPE . ' in file: ' . __FILE__ . ' at line ' . __LINE__, E_USER_WARNING);
            }
        }

        return $database;
    }
}
