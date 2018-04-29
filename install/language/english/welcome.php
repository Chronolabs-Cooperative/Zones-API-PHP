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


$content .= '
<p>
    <abbr title="REST API">API</abbr> is an open-source
    api that is application programable interface that requires installation and configuration for the API
    to work and function within the normal operating environmentals!
</p>
<p>
    API is released under the terms of the
    <a href="http://www.gnu.org/licenses/gpl-2.0.html" rel="external">GNU General Public License (GPL)</a>
    version 2 or greater, and is free to use and modify.
    It is free to redistribute as long as you abide by the distribution terms of the GPL.
</p>
<h3>Requirements</h3>
<ul>
    <li>WWW Server (<a href="http://www.apache.org/" rel="external">Apache</a>, <a href="https://www.nginx.com/" rel="external">NGINX</a>, IIS, etc)</li>
    <li><a href="http://www.php.net/" rel="external">PHP</a> 5.3.7 or higher, 5.6+ recommended</li>
    <li><a href="http://www.mysql.com/" rel="external">MySQL</a> 5.5 or higher, 5.6+ recommended </li>
</ul>
<h3>Before you install</h3>
<ol>
    <li>Setup WWW server, PHP and tmpbase server properly.</li>
    <li>Prepare a tmpbase for your API site.</li>
    <li>Prepare user account and grant the user the access to the tmpbase.</li>
    <li>Make these directories and files writable: %s</li>
    <li>For security considerations, you are strongly advised to move the two directories below out of <a href="http://phpsec.org/projects/guide/3.html" rel="external">document root</a> and change the folder names: %s</li>
    <li>Create (if not already present) and make these directories writable: %s</li>
    <li>Turn cookie and JavaScript of your browser on.</li>
</ol>
';
