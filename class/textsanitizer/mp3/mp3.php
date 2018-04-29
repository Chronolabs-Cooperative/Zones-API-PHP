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
 * Class MytsMp3
 */
class MytsMp3 extends MyTextSanitizerExtension
{
    /**
     * @param $textarea_id
     *
     * @return array
     */
    public function encode($textarea_id)
    {
        $code = "<button type='button' class='btn btn-default' onclick='apiCodeMp3(\"{$textarea_id}\",\""
            . htmlspecialchars(_API_FORM_ENTERMP3URL, ENT_QUOTES)
            . "\");' onmouseover='style.cursor=\"hand\"' title='" . _API_FORM_ALTMP3
            . "'><span class='fa fa-fw fa-music' aria-hidden='true'></span></button>";

        $javascript = <<<EOF
            function apiCodeMp3(id, enterMp3Phrase)
            {
                var selection = apiGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterMp3Phrase, "");
                }
                var domobj = apiGetElementById(id);
                if (text.length > 0) {
                    var result = "[mp3]" + text + "[/mp3]";
                    apiInsertText(domobj, result);
                }
                domobj.focus();
            }
EOF;

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
        return self::decode($match[1]);
    }

    /**
     * @param $ts
     *
     * @return bool
     */
    public function load($ts)
    {
        //        $ts->patterns[] = "/\[mp3\](.*?)\[\/mp3\]/es";
        //        $ts->replacements[] = __CLASS__ . "::decode( '\\1' )";
        //mb------------------------------
        $ts->callbackPatterns[] = "/\[mp3\](.*?)\[\/mp3\]/s";
        $ts->callbacks[]        = __CLASS__ . '::myCallback';

        //mb------------------------------
        return true;
    }

    /**
     * @param $url
     *
     * @return string
     */
    public static function decode($url, $width, $height)
    {
        $rp = "<embed flashvars=\"playerID=1&amp;bg=0xf8f8f8&amp;leftbg=0x3786b3&amp;lefticon=0x78bee3&amp;rightbg=0x3786b3&amp;rightbghover=0x78bee3&amp;righticon=0x78bee3&amp;righticonhover=0x3786b3&amp;text=0x666666&amp;slider=0x3786b3&amp;track=0xcccccc&amp;border=0x666666&amp;loader=0x78bee3&amp;loop=no&amp;soundFile={$url}\" quality='high' menu='false' wmode='transparent' pluginspage='http://www.macromedia.com/go/getflashplayer' src='" . API_URL . "/images/form/player.swf'  width=290 height=24 type='application/x-shockwave-flash'></embed>";

        return $rp;
    }
}
