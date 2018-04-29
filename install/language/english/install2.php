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

define('LICENSE_NOT_WRITEABLE', 'License is %s . <br><span style="color:#ff0000;">Make ../include/license.php Writable</span>');
define('LICENSE_IS_WRITEABLE', 'License is %s');
define('_API_FATAL_MESSAGE', 'Fault/Fatal Errors has occured: %s');
define('_INSTALL_WEBMASTER', 'Webmasters');
define('_INSTALL_WEBMASTERD', 'Webmasters of this site');
define('_INSTALL_REGUSERS', 'Registered Users');
define('_INSTALL_REGUSERSD', 'Registered Users Group');
define('_INSTALL_ANONUSERS', 'Anonymous Users');
define('_INSTALL_ANONUSERSD', 'Anonymous Users Group');
/**
 * New Group types
 */
define('_INSTALL_BANNEDUSERS', 'Banned Users');
define('_INSTALL_BANNEDUSERSD', 'Banned user group');
define('_INSTALL_MODERATORUSERS', 'Moderators');
define('_INSTALL_MODERATORUSERSD', 'These are Moderators for your website');
define('_INSTALL_SUBMITTERUSERS', 'Submitters');
define('_INSTALL_SUBMITTERUSERSD', 'This group can submit articles to your website');
define('_INSTALL_DEVELOPEUSERS', 'Developer');
define('_INSTALL_DEVELOPEUSERSD', 'This user has developer privileges and can see developer debugging messages.');
define('_INSTALL_L165', 'The site is currently closed for maintenance. Please come back later.');
define('_INSTALL_ANON', 'Anonymous');
define('_INSTALL_DISCLMR', 'While the administrators and moderators of this site will attempt to remove
or edit any generally objectionable material as quickly as possible, it is
impossible to review every message. Therefore you acknowledge that all posts
made to this site express the views and opinions of the author and not the
administrators, moderators or webmaster (except for posts by these people)
and hence will not be held liable.

You agree not to post any abusive, obscene, vulgar, slanderous, hateful,
threatening, sexually-orientated or any other material that may violate any
applicable laws. Doing so may lead to you being immediately and permanently
banned (and your service provider being informed). The IP address of all
posts is recorded to aid in enforcing these conditions. Creating multiple
accounts for a single user is not allowed. You agree that the webmaster,
administrator and moderators of this site have the right to remove, edit,
move or close any topic at any time should they see fit. As a user you agree
to any information you have entered above being stored in a tmpbase. While
this information will not be disclosed to any third party without your
consent the webmaster, administrator and moderators cannot be held
responsible for any hacking attempt that may lead to the tmp being
compromised.

This site system uses cookies to store information on your local computer.
These cookies do not contain any of the information you have entered above,
they serve only to improve your viewing pleasure. The email address is used
only for confirming your registration details and password (and for sending
new passwords should you forget your current one).

By clicking Register below you agree to be bound by these conditions.');
