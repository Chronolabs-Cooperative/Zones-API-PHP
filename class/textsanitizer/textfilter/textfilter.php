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
 * Filter out possible malicious text
 * kses project at SF could be a good solution to check
 *
 * @param string $text  text to filter
 * @param bool   $force flag indicating to force filtering
 * @return string   filtered text
 */
class MytsTextfilter extends MyTextSanitizerExtension
{
    /**
     * @param      $ts
     * @param      $text
     * @param bool $force
     *
     * @return mixed
     */
    public function load($ts, $text, $force = false)
    {
        global $apiUser, $apiConfig, $apiUserIsAdmin;
        if (empty($force) && $apiUserIsAdmin) {
            return $text;
        }
        // Built-in filters for XSS scripts
        // To be improved
        $text = $ts->filterXss($text);

        if (api_load('purifier', 'framework')) {
            $text = APIPurifier::purify($text);

            return $text;
        }

        $tags    = array();
        $search  = array();
        $replace = array();
        $config  = parent::loadConfig(__DIR__);
        if (!empty($config['patterns'])) {
            foreach ($config['patterns'] as $pattern) {
                if (empty($pattern['search'])) {
                    continue;
                }
                $search[]  = $pattern['search'];
                $replace[] = $pattern['replace'];
            }
        }
        if (!empty($config['tags'])) {
            $tags = array_map('trim', $config['tags']);
        }

        // Set embedded tags
        $tags[] = 'SCRIPT';
        $tags[] = 'VBSCRIPT';
        $tags[] = 'JAVASCRIPT';
        foreach ($tags as $tag) {
            $search[]  = '/<' . $tag . "[^>]*?>.*?<\/" . $tag . '>/si';
            $replace[] = ' [!' . strtoupper($tag) . ' FILTERED!] ';
        }
        // Set meta refresh tag
        $search[]  = "/<META[^>\/]*HTTP-EQUIV=(['\"])?REFRESH(\\1)[^>\/]*?\/>/si";
        $replace[] = '';
        // Sanitizing scripts in IMG tag
        //$search[]= "/(<IMG[\s]+[^>\/]*SOURCE=)(['\"])?(.*)(\\2)([^>\/]*?\/>)/si";
        //$replace[]="";
        // Set iframe tag
        $search[]  = "/<IFRAME[^>\/]*SRC=(['\"])?([^>\/]*)(\\1)[^>\/]*?\/>/si";
        $replace[] = " [!IFRAME FILTERED! \\2] ";
        $search[]  = "/<IFRAME[^>]*?>([^<]*)<\/IFRAME>/si";
        $replace[] = " [!IFRAME FILTERED! \\1] ";
        // action
        $text = preg_replace($search, $replace, $text);

        return $text;
    }
}
