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
 * base class
 */
api_load('APIFormTextArea');

/**
 *  A textarea with apiish formatting and smilie buttons
 *
 */
class APIFormDhtmlTextArea extends APIFormTextArea
{
    /**
     * Extended HTML editor
     *
     * <p>If an extended HTML editor is set, the renderer will be replaced by the specified editor, usually a visual or WYSIWYG editor.</p>
     *
     * <ul>Developer and user guide:
     *                         <li><ul>For run-time settings per call
     *                                 <li>To use an editor pre-configured by {@link APIEditor}, e.g. 'fckeditor': <code>$options['editor'] = 'fckeditor';</code></li>
     *                                 <li>To use a custom editor, e.g. 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>$options['editor'] = array('MyEditor', API_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
     *                             </ul></li>
     *                         <li><ul>For pre-configured settings, which will force to use a editor if no specific editor is set for call
     *                                 <li><ul>Set up custom configs: in API_VAR_PATH . '/configs/apiconfig.php' set a editor as default, e.g.
     *                                         <li>a pre-configured editor 'fckeditor': <code>return array('editor' => 'fckeditor');</code></li>
     *                                         <li>a custom editor 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>return array('editor' => array('MyEditor', API_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
     *                                     </ul></li>
     *                                 <li>To disable the default editor, in API_VAR_PATH . '/configs/apiconfig.php': <code>return array();</code></li>
     *                                 <li>To disable the default editor for a specific call: <code>$options['editor'] = 'dhtmltextarea';</code></li>
     *                             </ul></li>
     * </ul>
     */
    public $htmlEditor = array();

    /**
     * Hidden text
     *
     * @var string
     * @access private
     */
    public $_hiddenText;

    public $skipPreview = false;
    public $doHtml      = false;
    public $js          = '';

    /**
     * Constructor
     *
     * @param string $caption    Caption
     * @param string $name       "name" attribute
     * @param string $value      Initial text
     * @param int    $rows       Number of rows
     * @param int    $cols       Number of columns
     * @param string $hiddentext Identifier for hidden Text
     * @param array  $options    Extra options
     */
    public function __construct($caption, $name, $value = '', $rows = 5, $cols = 50, $hiddentext = 'apiHiddenText', $options = array())
    {
        global $apiConfig;
        static $inLoop = 0;

        ++$inLoop;
        // Second loop, invalid, return directly
        if ($inLoop > 2) {
            return null;
        }
        // Else, initialize
        parent::__construct($caption, $name, $value, $rows, $cols);
        $this->_hiddenText = $hiddentext;

        if ($inLoop > 1) {
            return null;
        }
        if (!isset($options['editor'])) {
            if (isset($apiConfig['editor'])) {
                $options['editor'] = $apiConfig['editor'];
            }
        }

        if (!empty($this->htmlEditor) || !empty($options['editor'])) {
            $options['name']  = $this->getName();
            $options['value'] = $this->getValue();
            if (!empty($options['editor'])) {
                $this->htmlEditor = is_array($options['editor']) ? $options['editor'] : array($options['editor']);
            }

            if (count($this->htmlEditor) == 1) {
                api_load('APIEditorHandler');
                $editor_handler   = APIEditorHandler::getInstance();
                $this->htmlEditor = $editor_handler->get($this->htmlEditor[0], $options);
                if ($inLoop > 1) {
                    $this->htmlEditor = null;
                }
            } else {
                list($class, $path) = $this->htmlEditor;
                include_once API_ROOT_PATH . $path;
                if (class_exists($class)) {
                    $this->htmlEditor = new $class($options);
                }
                if ($inLoop > 1) {
                    $this->htmlEditor = null;
                }
            }
        }

        $inLoop = 0;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        if ($this->htmlEditor && is_object($this->htmlEditor)) {
            if (!isset($this->htmlEditor->isEnabled) || $this->htmlEditor->isEnabled) {
                return $this->htmlEditor->render();
            }
        }

        return APIFormRenderer::getInstance()->get()->renderFormDhtmlTextArea($this);
    }

    /**
     * APIFormDhtmlTextArea::renderValidationJS()
     *
     * @return bool|string
     */
    public function renderValidationJS()
    {
        if ($this->htmlEditor && is_object($this->htmlEditor) && method_exists($this->htmlEditor, 'renderValidationJS')) {
            if (!isset($this->htmlEditor->isEnabled) || $this->htmlEditor->isEnabled) {
                return $this->htmlEditor->renderValidationJS();
            }
        }

        return parent::renderValidationJS();
    }
}
