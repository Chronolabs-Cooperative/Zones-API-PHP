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
 * A hidden field
 */
class APIFormHidden extends APIFormElement
{
    /**
     * Value
     *
     * @var string
     * @access private
     */
    public $_value;

    /**
     * Constructor
     *
     * @param string $name  "name" attribute
     * @param string $value "value" attribute
     */
    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->setHidden();
        $this->setValue($value);
        $this->setCaption('');
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
     * Sets the "value" attribute
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
        return '<input type="hidden" name="' . $this->getName() . '" id="' . $this->getName() . '" value="' . $this->getValue() . '" />';
    }
}
