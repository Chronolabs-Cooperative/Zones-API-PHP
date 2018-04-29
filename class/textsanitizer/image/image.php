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
global $apiConfig;
include_once $GLOBALS['api']->path('language/' . $apiConfig['language'] . '/misc.php');

/**
 * Class MytsImage
 */
class MytsImage extends MyTextSanitizerExtension
{
    /**
     * @param $ts
     *
     * @return bool
     */
    public function load($ts)
    {
        static $jsLoaded;

        $config         = $this->loadConfig(__DIR__);
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 width=(['\"]?)(\d*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img width=(['\"]?)(\d*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 id=(['\"]?)(\d*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img id=(['\"]?)(\d*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

        if (empty($ts->config['allowimage'])) {
            $ts->replacements[] = '<a href="\\5" rel="external">\\5</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\1" rel="external">\\1</a>';

            $ts->replacements[] = '<a href="' . API_URL . '/image.php?id=\\4" rel="external" title="\\5">\\5</a>';
            $ts->replacements[] = '<a href="' . API_URL . '/image.php?id=\\2" rel="external" title="\\3">\\3</a>';
        } else {
            if (!empty($config['resize']) && empty($config['clickable']) && !empty($config['max_width']) && !empty($GLOBALS['xoTheme'])) {
                if (!$jsLoaded) {
                    $jsLoaded = true;
                    $GLOBALS['xoTheme']->addScript('/class/textsanitizer/image/image.js', array(
                        'type' => 'text/javascript'));
                }
                $ts->replacements[] = "<img src='\\5' class='\\2' alt='" . _MSC_RESIZED_IMAGE . "' border='0' onload=\"JavaScript:if(this.width>\\4)this.width=\\4\" />";
                $ts->replacements[] = "<img src='\\3' class='\\2' alt='" . _MSC_RESIZED_IMAGE . "' border='0'" . ($config['resize'] ? "onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : '') . '/>';
                $ts->replacements[] = "<img src='\\3' alt='" . _MSC_RESIZED_IMAGE . "' border='0' onload=\"if(this.width>\\2)this.width=\\2\" /><br>";
                $ts->replacements[] = "<img src='\\1' alt='" . _MSC_RESIZED_IMAGE . "' border='0'" . ($config['resize'] ? " onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : '') . '/>';
            } elseif (!empty($config['clickable']) && !empty($config['max_width']) && !empty($GLOBALS['xoTheme'])) {
                if (!$jsLoaded) {
                    $jsLoaded = true;
                    $GLOBALS['xoTheme']->addScript('/class/textsanitizer/image/image.js', array(
                        'type' => 'text/javascript'));
                }
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\5\");'><img src='\\5' class='\\2' alt='" . _MSC_CLICK_TO_OPEN_IMAGE . "' border='0' onload=\"if(this.width>\\4)this.width=\\4\" /></a>";
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\3\");'><img src='\\3' class='\\2' alt='" . _MSC_CLICK_TO_OPEN_IMAGE . "' border='0' " . ($config['resize'] ? "onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : '') . '/></a>';
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\3\");'><img src='\\3' alt='" . _MSC_CLICK_TO_OPEN_IMAGE . "' border='0' onload=\"if(this.width>\\2)this.width=\\2\" /></a><br>";
                $ts->replacements[] = "<a href='javascript:loadImage(\"\\1\");'><img src='\\1' alt='" . _MSC_CLICK_TO_OPEN_IMAGE . "' border='0' title='" . _MSC_CLICK_TO_OPEN_IMAGE . "'" . ($config['resize'] ? " onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : '') . '/></a>';
            } else {
                $ts->replacements[] = "<img src='\\5' class='\\2' border='0' alt='" . _MSC_ORIGINAL_IMAGE . "' onload=\"JavaScript:if(this.width>\\4) this.width=\\4\" />";
                $ts->replacements[] = "<img src='\\3' class='\\2' border='0' alt='" . _MSC_ORIGINAL_IMAGE . "' " . ($config['resize'] ? "onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : '') . '/>';
                $ts->replacements[] = "<img src='\\3' border='0' alt='" . _MSC_ORIGINAL_IMAGE . "' onload=\"JavaScript:if(this.width>\\2) this.width=\\2\" />";
                $ts->replacements[] = "<img src='\\1' border='0' alt='" . _MSC_ORIGINAL_IMAGE . "' " . ($config['resize'] ? " onload=\"javascript:resizeImage(this, " . $config['max_width'] . ")\"" : '') . '/>';
            }
            $ts->replacements[] = '<img src="' . API_URL . '/image.php?id=\\4" class="\\2" title="\\5" />';
            $ts->replacements[] = '<img src="' . API_URL . '/image.php?id=\\2" title="\\3" />';
        }

        return true;
    }
}
