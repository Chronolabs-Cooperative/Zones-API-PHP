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
 * Database Storage engine for cache
 *
 * @package    cake
 * @subpackage cake.cake.libs.cache
 */
class APICacheModel extends APICacheEngine
{
    /**
     * settings
     *              className = name of the model to use, default => Cache
     *              fields = database fields that hold data and ttl, default => data, expires
     *
     * @var array
     * @access public
     */
    public $settings = array();

    /**
     * Model instance.
     *
     * @var object
     * @access private
     */
    public $model;

    /**
     * Model instance.
     *
     * @var object
     * @access private
     */
    public $fields = array();

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
        $apiDB = APIDatabaseFactory::getDatabaseConnection();

        parent::init($settings);
        $defaults       = array('fields' => array('data', 'expires'));
        $this->settings = array_merge($defaults, $this->settings);
        $this->fields   = $this->settings['fields'];
        $this->model    = new APICacheModelHandler($apiDB);

        return true;
    }

    /**
     * Garbage collection. Permanently remove all expired and deleted data
     *
     * @access public
     */
    public function gc()
    {
        return $this->model->deleteAll(new Criteria($this->fields[1], time(), '<= '));
    }

    /**
     * Write data for key into cache
     *
     * @param  string  $key      Identifier for the data
     * @param  mixed   $value     Data to be cached
     * @param  integer $duration How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure
     * @access public
     */
    public function write($key, $value, $duration = null)
    {
        // if (isset($this->settings['serialize'])) {
        $value = serialize($value);
        // }
        if (!$value) {
            return false;
        }
        $cache_obj = $this->model->create();
        $cache_obj->setVar($this->model->keyname, $key);
        $cache_obj->setVar($this->fields[0], $value);
        $cache_obj->setVar($this->fields[1], time() + $duration);

        return $this->model->insert($cache_obj);
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
        $criteria = new CriteriaCompo(new Criteria($this->model->keyname, $key));
        $criteria->add(new Criteria($this->fields[1], time(), '>'));
        $criteria->setLimit(1);
        $data = $this->model->getAll($criteria);
        if (!$data) {
            return null;
        }

        return unserialize($data[0]);
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
        return $this->model->delete($key);
    }

    /**
     * Delete all keys from the cache
     *
     * @return boolean True if the cache was successfully cleared, false otherwise
     * @access public
     */
    public function clear($check = null)
    {
        return $this->model->deleteAll();
    }
}

/**
 * APICacheModelObject
 *
 * @package
 * @author              John
 * @copyright       (c) 2000-2016 API Project (www.api.org)
 * @access              public
 */
class APICacheModelObject extends APIObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('key', XOBJ_DTYPE_TXTBOX);
        $this->initVar('data', XOBJ_DTYPE_SOURCE);
        $this->initVar('expires', XOBJ_DTYPE_INT);
    }
}

/**
 * APICacheModelHandler
 *
 * @package
 * @author              John
 * @copyright       (c) 2000-2016 API Project (www.api.org)
 * @access              public
 */
class APICacheModelHandler extends APIPersistableObjectHandler
{
    const TABLE     = 'cache_model';
    const CLASSNAME = 'APICacheModelObject';
    const KEYNAME   = 'key';
}
