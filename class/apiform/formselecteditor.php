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

api_load('APIFormElementTray');

/**
 * APIFormSelectEditor
 */
class APIFormSelectEditor extends APIFormElementTray
{
    public $allowed_editors = array();
    public $form;
    public $value;
    public $name;
    public $nohtml;

    /**
     * Constructor
     *
     * @param string    $form  the form calling the editor selection
     * @param string    $name  editor name
     * @param string    $value Pre-selected text value
     * @param bool      $nohtml
     * @param array     $allowed_editors
     *
     */

    public function __construct($form, $name = 'editor', $value = null, $nohtml = false, $allowed_editors = array())
    {
        parent::__construct(_SELECT);
        $this->allowed_editors = $allowed_editors;
        $this->form            = $form;
        $this->name            = $name;
        $this->value           = $value;
        $this->nohtml          = $nohtml;
    }

    /**
     * APIFormSelectEditor::render()
     *
     * @return string
     */
    public function render()
    {
        api_load('APIEditorHandler');
        $editor_handler                  = APIEditorHandler::getInstance();
        $editor_handler->allowed_editors = $this->allowed_editors;
        $option_select                   = new APIFormSelect('', $this->name, $this->value);
        $extra                           = 'onchange="if (this.options[this.selectedIndex].value.length > 0) {
            window.document.forms.' . $this->form->getName() . '.submit();
            }"';
        $option_select->setExtra($extra);
        $option_select->addOptionArray($editor_handler->getList($this->nohtml));
        $this->addElement($option_select);

        return parent::render();
    }
}
