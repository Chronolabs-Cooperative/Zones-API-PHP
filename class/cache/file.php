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
 * File Storage engine for cache
 *
 * @todo       use the File and Folder classes (if it's not a too big performance hit)
 * @package    cake
 * @subpackage cake.cake.libs.cache
 */
class APICacheFile extends APICacheEngine
{
    /**
     * Instance of File class
     *
     * @var object
     * @access private
     */
    private $file;

    /**
     * settings
     *                path = absolute path to cache directory, default => CACHE
     *                prefix = string prefix for filename, default => api_
     *                lock = enable file locking on write, default => false
     *                serialize = serialize the data, default => false
     *
     * @var array
     * @see    CacheEngine::__defaults
     * @access public
     */
    public $settings = array();

    /**
     * Set to true if FileEngine::init(); and FileEngine::active(); do not fail.
     *
     * @var boolean
     * @access private
     */
    private $active = false;

    /**
     * True unless FileEngine::active(); fails
     *
     * @var boolean
     * @access private
     */
    private $init = true;

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
        if (!is_dir(API_TRUST_PATH . DS . parse_url(API_URL, PHP_URL_HOST) . DS . 'cache'))
            mkdir(API_TRUST_PATH . DS . parse_url(API_URL, PHP_URL_HOST) . DS . 'cache', 0777, true);
            
        parent::init($settings);
        $defaults       = array(
            'path'      => API_TRUST_PATH . DS . parse_url(API_URL, PHP_URL_HOST) . DS . 'cache',
            'extension' => '.php',
            'prefix'    => 'api_',
            'lock'      => false,
            'serialize' => true,
            'duration'  => 31556926);
        $this->settings = array_merge($defaults, $this->settings);
        if (!isset($this->file)) {
            APILoad::load('APIFile');
            $this->file = APIFile::getHandler('file', $this->settings['path'] . '/index.html', true);
        }
        $this->settings['path'] = $this->file->folder->cd($this->settings['path']);
        if (empty($this->settings['path'])) {
            return false;
        }

        return $this->active();
    }

    /**
     * Garbage collection. Permanently remove all expired and deleted data
     *
     * @return boolean True if garbage collection was successful, false on failure
     * @access public
     */
    public function gc()
    {
        return $this->clear(true);
    }

    /**
     * Write data for key into cache
     *
     * @param  string $key      Identifier for the data
     * @param  mixed  $data     Data to be cached
     * @param  mixed  $duration How long to cache the data, in seconds
     * @return boolean True if the data was successfully cached, false on failure
     * @access public
     */
    public function write($key, $data = null, $duration = null)
    {
        if (!isset($data) || !$this->init) {
            return false;
        }

        if ($this->setKey($key) === false) {
            return false;
        }

        if ($duration == null) {
            $duration = $this->settings['duration'];
        }
        $windows   = false;
        $lineBreak = "\n";

        if (substr(PHP_OS, 0, 3) === 'WIN') {
            $lineBreak = "\r\n";
            $windows   = true;
        }
        $expires = time() + $duration;
        if (!empty($this->settings['serialize'])) {
            if ($windows) {
                $data = str_replace('\\', '\\\\\\\\', serialize($data));
            } else {
                $data = serialize($data);
            }
            $contents = $expires . $lineBreak . $data . $lineBreak;
        } else {
            $contents = $expires . $lineBreak . 'return ' . var_export($data, true) . ';' . $lineBreak;
        }

        if ($this->settings['lock']) {
            $this->file->lock = true;
        }
        $success = $this->file->write($contents);
        $this->file->close();

        return $success;
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
        if ($this->setKey($key) === false || !$this->init) {
            return false;
        }
        if ($this->settings['lock']) {
            $this->file->lock = true;
        }
        $cachetime = $this->file->read(11);

        if ($cachetime !== false && (int)$cachetime < time()) {
            $this->file->close();
            $this->file->delete();

            return false;
        }

        $data = $this->file->read(true);
        if (!empty($data) && !empty($this->settings['serialize'])) {
            $data = stripslashes($data);
            // $data = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $data);
            $data = preg_replace_callback('!s:(\d+):"(.*?)";!s', function ($m) { return 's:' . strlen($m[2]) . ':"' . $m[2] . '";'; }, $data);
            $data = unserialize($data);
            if (is_array($data)) {
                APILoad::load('APIUtility');
                $data = APIUtility::recursive('stripslashes', $data);
            }
        } elseif ($data && empty($this->settings['serialize'])) {
            try {
                $data = eval($data);
            } catch (Exception $e) {
                $data = false;
            }
        }
        $this->file->close();

        return $data;
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
        if ($this->setKey($key) === false || !$this->init) {
            return false;
        }

        return $this->file->delete();
    }

    /**
     * Delete all values from the cache
     *
     * @param  boolean $check Optional - only delete expired cache items
     * @return boolean True if the cache was successfully cleared, false otherwise
     * @access public
     */
    public function clear($check = true)
    {
        if (!$this->init) {
            return false;
        }
        $dir = dir($this->settings['path']);
        if ($check) {
            $now       = time();
            $threshold = $now - $this->settings['duration'];
        }
        while (($entry = $dir->read()) !== false) {
            if ($this->setKey(str_replace($this->settings['prefix'], '', $entry)) === false) {
                continue;
            }
            if ($check) {
                $mtime = $this->file->lastChange();

                if ($mtime === false || $mtime > $threshold) {
                    continue;
                }

                $expires = $this->file->read(11);
                $this->file->close();

                if ($expires > $now) {
                    continue;
                }
            }
            $this->file->delete();
        }
        $dir->close();

        return true;
    }

    /**
     * Get absolute file for a given key
     *
     * @param  string $key The key
     * @return mixed  Absolute cache file for the given key or false if erroneous
     * @access private
     */
    private function setKey($key)
    {
        $this->file->folder->cd($this->settings['path']);
        $this->file->name   = $this->settings['prefix'] . $key . $this->settings['extension'];
        $this->file->handle = null;
        $this->file->info   = null;
        if (!$this->file->folder->inPath($this->file->pwd(), true)) {
            return false;
        }
        return null;
    }

    /**
     * Determine is cache directory is writable
     *
     * @return boolean
     * @access private
     */
    private function active()
    {
        if (!$this->active && $this->init && !is_writable($this->settings['path'])) {
            $this->init = false;
            trigger_error(sprintf('%s is not writable', $this->settings['path']), E_USER_WARNING);
        } else {
            $this->active = true;
        }

        return true;
    }
}
