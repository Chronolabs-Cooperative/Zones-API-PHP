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
 * Replaces banned words in a string with their replacements or terminate current request
 *
 * @param   string $text
 * @return  string
 *
 */
class MytsCensor extends MyTextSanitizerExtension
{
    /**
     * @param $ts
     * @param $text
     *
     * @return mixed|string
     */
    public function load($ts, $text)
    {
        static $censorConf;
        if (!isset($censorConf)) {
            /* @var $config_handler APIConfigHandler  */
            $config_handler = api_getHandler('config');
            $censorConf     = $config_handler->getConfigsByCat(API_CONF_CENSOR);
            $config         = parent::loadConfig(__DIR__);
            //merge and allow config override
            $censorConf = array_merge($censorConf, $config);
        }

        if (empty($censorConf['censor_enable'])) {
            return $text;
        }

        if (empty($censorConf['censor_words'])) {
            return $text;
        }

        if (empty($censorConf['censor_admin']) && $GLOBALS['apiUserIsAdmin']) {
            return $text;
        }

        $replacement = $censorConf['censor_replace'];
        foreach ($censorConf['censor_words'] as $bad) {
            $bad = trim($bad);
            if (!empty($bad)) {
                if (false === strpos($text, $bad)) {
                    continue;
                }
                if (!empty($censorConf['censor_terminate'])) {
                    trigger_error('Censor words found', E_USER_ERROR);
                    $text = '';

                    return $text;
                }
                $patterns[]     = "/(^|[^0-9a-z_]){$bad}([^0-9a-z_]|$)/siU";
                $replacements[] = "\\1{$replacement}\\2";
                $text           = preg_replace($patterns, $replacements, $text);
            }
        }

        return $text;
    }
}
