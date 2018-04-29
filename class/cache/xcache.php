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


/**
 * Xcache storage engine for cache
 *
 * @link       http://trac.lighttpd.net/xcache/ Xcache
 * @package    cake
 * @subpackage cake.cake.libs.cache
 */
class APICacheXcache extends APICacheEngine
{
    /**
     * settings
     *          PHP_AUTH_USER = xcache.admin.user, default cake
     *          PHP_AUTH_PW = xcache.admin.password, default cake
     *
     * @var array
     * @access public
     */
    public $settings = array();

    /**
     * Initialize the Cache Engine
     *
     * Called automatically by the cache frontend
     * To reinitialize the settings call Cache::engine('EngineName', [optional] settings = array());
     *
     * @param  array $settings array of setting for the engine
     * @return boolean True if the engine has been successfully initialized, false if not
     * @access   public
     */
    public function init($settings = array())
    {
        parent::init($settings);
        $defaults       = array('PHP_AUTH_USER' => 'cake', 'PHP_AUTH_PW' => 'cake');
        $this->settings = array_merge($defaults, $this->settings);

        return function_exists('xcache_info');
    }

    /**
     * Write data for key into cache
     *
     * @param  string  $key      Identifier for the data
     * @param  mixed   $value    Data to be cached
     * @param  integer $duration How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure
     * @access public
     */
    public function write($key, $value, $duration = null)
    {
        return xcache_set($key, $value, $duration);
    }

    /**
     * Read a key from the cache
     *
     * @param  string $key Identifier for the data
     * @return mixed  The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
     * @access public
     */
    public function read($key)
    {
        if (xcache_isset($key)) {
            return xcache_get($key);
        }

        return false;
    }

    /**
     * Delete a key from the cache
     *
     * @param  string $key Identifier for the data
     * @return boolean True if the value was successfully deleted, false if it didn't exist or couldn't be removed
     * @access public
     */
    public function delete($key)
    {
        return xcache_unset($key);
    }

    /**
     * Delete all keys from the cache
     *
     * @return boolean True if the cache was successfully cleared, false otherwise
     * @access public
     */
    public function clear($check = null)
    {
        $result = true;
        $this->__auth();
        for ($i = 0, $max = xcache_count(XC_TYPE_VAR); $i < $max; ++$i) {
            if (!xcache_clear_cache(XC_TYPE_VAR, $i)) {
                $result = false;
                break;
            }
        }
        $this->__auth(true);

        return $result;
    }

    /**
     * Populates and reverses $_SERVER authentication values
     * Makes necessary changes (and reverting them back) in $_SERVER
     *
     * This has to be done because xcache_clear_cache() needs to pass Basic Http Auth
     * (see xcache.admin configuration settings)
     *
     * @param bool $reverse Revert changes
     * @access   private
     */
    private function __auth($reverse = false)
    {
        static $backup = array();
        $keys = array('PHP_AUTH_USER', 'PHP_AUTH_PW');
        foreach ($keys as $key) {
            if ($reverse) {
                if (isset($backup[$key])) {
                    $_SERVER[$key] = $backup[$key];
                    unset($backup[$key]);
                } else {
                    unset($_SERVER[$key]);
                }
            } else {
                $value = env($key);
                if (!empty($value)) {
                    $backup[$key] = $value;
                }
                $varName       = '__' . $key;
                $_SERVER[$key] = $this->settings[$varName];
            }
        }
    }
}
