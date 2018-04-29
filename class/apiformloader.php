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


api_load('APIForm');
api_load('APIThemeForm');
api_load('APISimpleForm');
api_load('APIFormElement');
api_load('APIFormElementTray');
api_load('APIFormLabel');
api_load('APIFormCheckBox');
api_load('APIFormPassword');
api_load('APIFormButton');
api_load('APIFormButtonTray'); // To be cleaned
api_load('APIFormHidden');
api_load('APIFormFile');
api_load('APIFormRadio');
api_load('APIFormRadioYN');
api_load('APIFormSelect');
api_load('APIFormSelectGroup');
api_load('APIFormSelectCheckGroup'); // To be cleaned
api_load('APIFormSelectUser');
api_load('APIFormSelectTheme');
api_load('APIFormSelectMatchOption');
api_load('APIFormSelectCountry');
api_load('APIFormSelectTimeZone');
api_load('APIFormSelectLang');
api_load('APIFormSelectEditor');
api_load('APIFormText');
api_load('APIFormTextArea');
api_load('APIFormTextDateSelect');
api_load('APIFormDhtmlTextArea');
api_load('APIFormDateTime');
api_load('APIFormHiddenToken');
api_load('APIFormColorPicker');
api_load('APIFormCaptcha');
api_load('APIFormEditor');
