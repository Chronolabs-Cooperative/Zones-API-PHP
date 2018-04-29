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


class APIFormRenderer
{
    /**
     * @var APIFormRenderer The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * @var APIFormRendererInterface The reference to *Singleton* instance of this class
     */
    protected $renderer;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return APIFormRenderer the singleton instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * set the renderer
     *
     * @param APIFormRendererInterface $renderer instance of renderer
     *
     * @return void
     */
    public function set(APIFormRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * get the renderer
     *
     * @return APIFormRendererInterface
     */
    public function get()
    {
        // return a default if not set
        if (null === $this->renderer) {
            api_load('apiformrendererlegacy');
            $this->renderer = new APIFormRendererLegacy();
        }

        return $this->renderer;
    }
}
