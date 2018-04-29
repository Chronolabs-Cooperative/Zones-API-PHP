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
 * Password Field
 */
class APIFormPassword extends APIFormElement
{
    /**
     * Size of the field.
     *
     * @var int
     * @access private
     */
    public $_size;

    /**
     * Maximum length of the text
     *
     * @var int
     * @access private
     */
    public $_maxlength;

    /**
     * Initial content of the field.
     *
     * @var string
     * @access private
     */
    public $_value;

    /**
     * Cache password with browser. Disabled by default for security consideration
     * Added in 2.3.1
     *
     * @var boolean
     * @access public
     */
    public $autoComplete = false;

    /**
     * Constructor
     *
     * @param string $caption      Caption
     * @param string $name         "name" attribute
     * @param int    $size         Size of the field
     * @param int    $maxlength    Maximum length of the text
     * @param string $value        Initial value of the field.
     *                             <strong>Warning:</strong> this is readable in cleartext in the page's source!
     * @param bool   $autoComplete To enable autoComplete or browser cache
     */
    public function __construct($caption, $name, $size, $maxlength, $value = '', $autoComplete = false)
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_size      = (int)$size;
        $this->_maxlength = (int)$maxlength;
        $this->setValue($value);
        $this->autoComplete = !empty($autoComplete);
    }

    /**
     * Get the field size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Get the max length
     *
     * @return int
     */
    public function getMaxlength()
    {
        return $this->_maxlength;
    }

    /**
     * Get the "value" attribute
     *
     * @param  bool $encode To sanitizer the text?
     * @return string
     */
    public function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
    }

    /**
     * Set the initial value
     *
     * @patam $value    string
     * @param $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        return APIFormRenderer::getInstance()->get()->renderFormPassword($this);
    }
}
