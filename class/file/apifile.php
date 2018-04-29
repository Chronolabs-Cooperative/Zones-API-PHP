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
 * APIFile
 *
 * @package
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @access public
 */
class APIFile
{
    /**
     * APIFile::__construct()
     */
    public function __construct()
    {
    }

    /**
     * APIFile::getInstance()
     *
     * @return
     */
    public function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class    = __CLASS__;
            $instance = new $class();
        }

        return $instance;
    }

    /**
     * APIFile::load()
     *
     * @param string $name
     *
     * @return bool
     */
    public static function load($name = 'file')
    {
        switch ($name) {
            case 'folder':
                if (!class_exists('APIFolderHandler')) {
                    if (file_exists($folder = __DIR__ . '/folder.php')) {
                        include $folder;
                    } else {
                        trigger_error('Require Item : ' . str_replace(API_ROOT_PATH, '', $folder) . ' In File ' . __FILE__ . ' at Line ' . __LINE__, E_USER_WARNING);

                        return false;
                    }
                }
                break;
            case 'file':
            default:
                if (!class_exists('APIFileHandler')) {
                    if (file_exists($file = __DIR__ . '/file.php')) {
                        include $file;
                    } else {
                        trigger_error('Require File : ' . str_replace(API_ROOT_PATH, '', $file) . ' In File ' . __FILE__ . ' at Line ' . __LINE__, E_USER_WARNING);

                        return false;
                    }
                }
                break;
        }

        return true;
    }

    /**
     * APIFile::getHandler()
     *
     * @param string $name
     * @param mixed  $path
     * @param mixed  $create
     * @param mixed  $mode
     * @return
     */
    public static function getHandler($name = 'file', $path = false, $create = false, $mode = null)
    {
        $handler = null;
        APIFile::load($name);
        $class = 'API' . ucfirst($name) . 'Handler';
        if (class_exists($class)) {
            $handler = new $class($path, $create, $mode);
        } else {
            trigger_error('Class ' . $class . ' not exist in File ' . __FILE__ . ' at Line ' . __LINE__, E_USER_WARNING);
        }

        return $handler;
    }
}
