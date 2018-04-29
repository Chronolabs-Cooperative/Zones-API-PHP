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
 * Date and time selection field
 *
 * @author         Kazumi Ono <onokazu@api.org>
 * @package        kernel
 * @subpackage     form
 */
class APIFormDateTime extends APIFormElementTray
{
    const SHOW_BOTH = 1;
    const SHOW_DATE = 0;
    const SHOW_TIME = 2;

    /**
     * APIFormDateTime::APIFormDateTime()
     *
     * @param mixed   $caption  form field caption
     * @param mixed   $name     form variable name
     * @param integer $size     size of date select
     * @param integer $value    unix timestamp, defaults to now
     * @param mixed   $showtime control display of date and time elements
     *                           SHOW_BOTH, true  - show both date and time selectors
     *                           SHOW_DATE, false - only show date selector
     *                           SHOW_TIME        - only show time selector
     */
    public function __construct($caption, $name, $size = 15, $value = 0, $showtime = true)
    {
        parent::__construct($caption, '&nbsp;');
        switch ((int) $showtime) {
            case static::SHOW_DATE:
                $displayDate = true;
                $displayTime = false;
                break;
            case static::SHOW_TIME:
                $displayDate = false;
                $displayTime = true;
                break;
            default:
                $displayDate = true;
                $displayTime = true;
                break;
        }
        $value    = (int)$value;
        $value    = ($value > 0) ? $value : time();
        $datetime = getdate($value);
        if ($displayDate) {
            $this->addElement(new APIFormTextDateSelect('', $name . '[date]', $size, $value));
        } else {
            $value = !is_numeric($value) ? time() : (int)$value;
            $value = ($value == 0) ? time() : $value;
            $displayValue = date(_SHORTDATESTRING, $value);
            $this->addElement(new APIFormHidden($name . '[date]', $displayValue));
        }

        if ($displayTime) {
            $timearray = array();
            for ($i = 0; $i < 24; ++$i) {
                for ($j = 0; $j < 60; $j += 10) {
                    $key = ($i * 3600) + ($j * 60);
                    $timearray[$key] = ($j != 0) ? $i . ':' . $j : $i . ':0' . $j;
                }
            }
            ksort($timearray);

            $timeselect = new APIFormSelect('', $name . '[time]', $datetime['hours'] * 3600 + 600 * ceil($datetime['minutes'] / 10));
            $timeselect->addOptionArray($timearray);
            $this->addElement($timeselect);
        } else {
            $this->addElement(new APIFormHidden($name . '[time]', 0));
        }
    }
}
