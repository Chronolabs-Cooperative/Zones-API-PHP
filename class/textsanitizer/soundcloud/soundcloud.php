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
 * Class MytsSoundcloud
 */
class MytsSoundcloud extends MyTextSanitizerExtension
{
    /**
     * @param $textarea_id
     *
     * @return array
     */
    public function encode($textarea_id)
    {
        $config = parent::loadConfig(__DIR__);

        $code = "<button type='button' class='btn btn-default btn-sm' onclick='apiCodeSoundCloud(\"{$textarea_id}\",\""
            . htmlspecialchars(_API_FORM_ENTER_SOUNDCLOUD_URL, ENT_QUOTES)
            . "\");' onmouseover='style.cursor=\"hand\"' title='" . _API_FORM_ALT_SOUNDCLOUD
            . "'><span class='fa fa-fw fa-soundcloud' aria-hidden='true'></span></button>";
        $javascript = <<<EOH
            function apiCodeSoundCloud(id, enterSoundCloud)
            {
                var selection = apiGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterSoundCloud, "");
                }

                var domobj = apiGetElementById(id);
                if (text.length > 0) {
                apiInsertText(domobj, "[soundcloud]"+text+"[/soundcloud]");
                }
                domobj.focus();
            }
EOH;

        return array($code, $javascript);
    }

    /**
     * @param $ts
     */
    public function load($ts)
    {
        $ts->callbackPatterns[] = "/\[soundcloud\](http[s]?:\/\/[^\"'<>]*)(.*)\[\/soundcloud\]/sU";
        $ts->callbacks[]        = __CLASS__ . '::myCallback';
    }

    /**
     * @param $match
     *
     * @return string
     */
    public static function myCallback($match)
    {
        $url    = $match[1] . $match[2];
        $config = parent::loadConfig(__DIR__);
        if (!preg_match("/^http[s]?:\/\/(www\.)?soundcloud\.com\/(.*)/i", $url, $matches)) {
            trigger_error("Not matched: {$url}", E_USER_WARNING);

            return '';
        }

        $code = '<object height="81" width="100%"><param name="movie" ' . 'value="http://player.soundcloud.com/player.swf?url=' . $url . '&amp;g=bb">' . '</param><param name="allowscriptaccess" value="always"></param>' . '<embed allowscriptaccess="always" height="81" ' . 'src="http://player.soundcloud.com/player.swf?url=' . $url . '&amp;g=bb" type="application/x-shockwave-flash" width="100%"></embed></object>' . '<a href="' . $url . '">' . $url . '</a>';

        return $code;
    }

    //   public static function decode($url1, $url2)
    //   {
    //   }
}
