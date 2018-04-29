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
 * Class MytsWiki
 */
class MytsWiki extends MyTextSanitizerExtension
{
    /**
     * @param $textarea_id
     *
     * @return array
     */
    public function encode($textarea_id)
    {
        $config     = parent::loadConfig(__DIR__);
        $code = "<button type='button' class='btn btn-default btn-sm' onclick='apiCodeWiki(\"{$textarea_id}\",\""
            . htmlspecialchars(_API_FORM_ENTERWIKITERM, ENT_QUOTES)
            . "\");' onmouseover='style.cursor=\"hand\"' title='" . _API_FORM_ALTWIKI
            . "'><span class='fa fa-fw fa-globe' aria-hidden='true'></span></button>";

        $javascript = <<<EOH
            function apiCodeWiki(id, enterWikiPhrase)
            {
                if (enterWikiPhrase == null) {
                    enterWikiPhrase = "Enter the word to be linked to Wiki:";
                }
                var selection = apiGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterWikiPhrase, "");
                }
                var domobj = apiGetElementById(id);
                if (text != null && text != "") {
                    var result = "[[" + text + "]]";
                    apiInsertText(domobj, result);
                }
                domobj.focus();
            }
EOH;

        return array(
            $code,
            $javascript);
    }

    /**
     * @param $match
     *
     * @return string
     */
    public static function myCallback($match)
    {
        return self::decode($match[1],0 ,0);
    }

    /**
     * @param $ts
     */
    public function load($ts)
    {
        //        $ts->patterns[] = "/\[\[([^\]]*)\]\]/esU";
        //        $ts->replacements[] = __CLASS__ . "::decode( '\\1' )";
        //mb------------------------------
        $ts->callbackPatterns[] = "/\[\[([^\]]*)\]\]/sU";
        $ts->callbacks[]        = __CLASS__ . '::myCallback';
        //mb------------------------------
    }

    /**
     * @param $text
     *
     * @return string
     */
    public static function decode($text, $width, $height)
    {
        $config = parent::loadConfig(__DIR__);
        if (empty($text) || empty($config['link'])) {
            return $text;
        }
        $charset = !empty($config['charset']) ? $config['charset'] : 'UTF-8';
        api_load('APILocal');
        $ret = "<a href='" . sprintf($config['link'], urlencode(APILocal::convert_encoding($text, $charset))) . "' rel='external' title=''>{$text}</a>";

        return $ret;
    }
}
