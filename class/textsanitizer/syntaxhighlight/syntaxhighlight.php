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
 * Class MytsSyntaxhighlight
 */
class MytsSyntaxhighlight extends MyTextSanitizerExtension
{
    /**
     * @param $ts
     * @param $source
     * @param $language
     *
     * @return bool|mixed|string
     */
    public function load($ts, $source, $language)
    {
        $config = parent::loadConfig(__DIR__);
        if (empty($config['highlight'])) {
            return "<pre>{$source}</pre>";
        }
        $source = $ts->undoHtmlSpecialChars($source);
        $source = stripslashes($source);
        $source = MytsSyntaxhighlight::php($source);

        return $source;
    }

    /**
     * @param $text
     *
     * @return mixed|string
     */
    public function php($text)
    {
        $text          = trim($text);
        $addedtag_open = 0;
        if (!strpos($text, '<?php') && (substr($text, 0, 5) !== '<?php')) {
            $text          = '<?php ' . $text;
            $addedtag_open = 1;
        }
        $addedtag_close = 0;
        if (!strpos($text, '?>')) {
            $text .= '?>';
            $addedtag_close = 1;
        }
        $oldlevel = error_reporting(0);

        //There is a bug in the highlight function(php < 5.3) that it doesn't render
        //backslashes properly like in \s. So here we replace any backslashes
        $text = str_replace("\\", 'XxxX', $text);

        $buffer = highlight_string($text, true); // Require PHP 4.20+

        //Placing backspaces back again
        $buffer = str_replace('XxxX', "\\", $buffer);

        error_reporting($oldlevel);
        $pos_open = $pos_close = 0;
        if ($addedtag_open) {
            $pos_open = strpos($buffer, '&lt;?php&nbsp;');
        }
        if ($addedtag_close) {
            $pos_close = strrpos($buffer, '?&gt;');
        }

        $str_open  = $addedtag_open ? substr($buffer, 0, $pos_open) : '';
        $str_close = $pos_close ? substr($buffer, $pos_close + 5) : '';

        $length_open  = $addedtag_open ? $pos_open + 14 : 0;
        $length_text  = $pos_close ? $pos_close - $length_open : 0;
        $str_internal = $length_text ? substr($buffer, $length_open, $length_text) : substr($buffer, $length_open);

        $buffer = $str_open . $str_internal . $str_close;

        return $buffer;
    }
}
