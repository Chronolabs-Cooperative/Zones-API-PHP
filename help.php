<?php
/**
 * DNS Zone Propogation REST Services API
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
 * @package         zones-api
 * @since           1.0.3
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         1.0.3
 * @description		A REST API for the creation and management of emails/forwarders and domain name parks for email
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/Emails-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 * 
 */

if (isset($_COOKIE['authkey']))
    $authkey = $_COOKIE['authkey'];
elseif (isset($_SESSION['authkey']))
    $authkey = $_SESSION['authkey'];
else
    $authkey = md5(NULL);
    
$result = $GLOBALS['APIDB']->queryF("SELECT md5(concat(`id`, '" . API_URL . "', 'domain')) as `key` FROM `domains` ORDER BY RAND() LIMIT 1");
if ($row = $GLOBALS['APIDB']->fetchArray($result))
    $domainkey = $row['key'];
else 
    $domainkey = md5(NULL.'domain');
    
$result = $GLOBALS['APIDB']->queryF("SELECT md5(concat(`id`, '" . API_URL . "', 'record')) as `key` FROM `records` ORDER BY RAND() LIMIT 1");
if ($row = $GLOBALS['APIDB']->fetchArray($result))
    $zonekey = $row['key'];
else
    $zonekey = md5(NULL.'record');

    
$result = $GLOBALS['APIDB']->queryF("SELECT md5(concat(`id`, '" . API_URL . "', 'supermaster')) as `key` FROM `supermasters` ORDER BY RAND() LIMIT 1");
if ($row = $GLOBALS['APIDB']->fetchArray($result))
    $masterkey = $row['key'];
else
    $masterkey = md5(NULL.'supermaster');
    
    
$result = $GLOBALS['APIDB']->queryF("SELECT md5(concat(`uid`, '" . API_URL . "', 'user')) as `key` FROM `users` ORDER BY RAND() LIMIT 1");
if ($row = $GLOBALS['APIDB']->fetchArray($result))
    $userkey = $row['key'];
else
    $userkey = md5(NULL.'supermaster');
            
            
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo API_VERSION; ?>"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/assets/images/logo_500x500.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo API_VERSION; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="author" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo API_VERSION; ?> || <?php echo API_LICENSE_COMPANY; ?></title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'Chronolabs'},
		{'service': 'twitter', 'id': 'JohnRingwould'},
		{'service': 'twitter', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'Cipherhouse'},
		{'service': 'twitter', 'id': 'OpenRend'},
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/style.css" type="text/css" />
<!-- Custom Fonts -->
<link href="<?php echo API_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/gradients.php" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>
<body>
<div class="main">
	<img style="float: right; margin: 11px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/assets/images/logo_350x350.png" />
    <h1><?php echo API_VERSION; ?> -- <?php echo API_LICENSE_COMPANY; ?></h1>
    <p>This API uses PowerDNS to propogate the basis of zone delegation for the domains installed, you have to generate an authkey for them to fire which is currently <strong><?php echo $authkey; ?></strong>.</p>
    <p>You can propogate DNS and zone delegation with this api and call and respond and generate zone on the fly.</p>
	<h2>Code API Documentation</h2>
    <p>You can find the phpDocumentor code API documentation at the following path :: <a href="<?php echo API_URL . '/'; ?>docs/" target="_blank"><?php echo API_URL . '/'; ?>docs/</a>. These should outline the source code core functions and classes for the API to function!</p>
    <h2>AUTHKEY Document Output</h2>
    <p>This is done with the <em>authkey.api</em> extension at the end of the url, you replace the example address with either a domain!</p>
    <blockquote>
        <?php echo getHTMLForm('authkey'); ?>
    </blockquote>
    <h3>This the HTML Code surrounding the api call</h3>
    <pre style="max-height: 300px; overflow: scroll;">
    <?php echo htmlspecialchars(getHTMLForm('authkey')); ?>
    </pre>
    <h2>SUPERMASTER Document Output</h2>
    <p>This is done with the <em>supermaster.api</em> extension at the end of the url, you replace the example address with either a domain!</p>
    <blockquote>
        <?php echo getHTMLForm('newsupermaster', $authkey); ?>
    </blockquote>
    <h3>This the HTML Code surrounding the api call</h3>
    <pre style="max-height: 300px; overflow: scroll;">
    <?php echo htmlspecialchars(getHTMLForm('newsupermaster', $authkey)); ?>
    </pre>
    <h2>DOMAIN Document Output</h2>
    <p>This is done with the <em>domain.api</em> extension at the end of the url, you replace the example address with either a domain!</p>
    <blockquote>
        <?php echo getHTMLForm('newdomain', $authkey); ?>
    </blockquote>
    <h3>This the HTML Code surrounding the api call</h3>
    <pre style="max-height: 300px; overflow: scroll;">
    <?php echo htmlspecialchars(getHTMLForm('newdomain', $authkey)); ?>
    </pre>
    <h2>ZONE Document Output</h2>
    <p>This is done with the <em>zone.api</em> extension at the end of the url, you replace the example address with either a domain!</p>
    <blockquote>
        <?php echo getHTMLForm('newrecord', $authkey); ?>
    </blockquote>
    <h3>This the HTML Code surrounding the api call</h3>
    <pre style="max-height: 300px; overflow: scroll;">
    <?php echo htmlspecialchars(getHTMLForm('newrecord', $authkey)); ?>
    </pre>
    <h2>RAW Document Output</h2>
    <p>This is done with the <em>raw.api</em> extension at the end of the url, you replace the example address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This provides a list and keys of defined domains on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/raw.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined super-masters on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/raw.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined users on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/raw.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined zones of a domain per the domain key</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/raw.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'domain.api' for an edit of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/raw.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'zones.api' for an edit of a zone record!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/raw.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'supermasters.api' for an edit of a super master!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/raw.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'users.api' for an edit of a user!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/raw.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'domain.api' for a deletion of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/raw.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'zones.api' for a deletion of a zone record by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/raw.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'supermasters.api' for a deletion of a super master by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/raw.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'users.api' for a deletion of a user by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/raw.api</a></font><br /><br />
    </blockquote>
    <h2>Serialisation Document Output</h2>
    <p>This is done with the <em>serial.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This provides a list and keys of defined domains on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/serial.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined super-masters on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/serial.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined users on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/serial.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined zones of a domain per the domain key</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/serial.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'domain.api' for an edit of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/serial.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'zones.api' for an edit of a zone record!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/serial.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'supermasters.api' for an edit of a super master!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/serial.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'users.api' for an edit of a user!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/serial.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'domain.api' for a deletion of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/serial.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'zones.api' for a deletion of a zone record by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/serial.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'supermasters.api' for a deletion of a super master by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/serial.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'users.api' for a deletion of a user by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/serial.api</a></font><br /><br />
    </blockquote>
    <h2>JSON Document Output</h2>
    <p>This is done with the <em>json.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This provides a list and keys of defined domains on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/json.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined super-masters on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/json.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined users on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/json.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined zones of a domain per the domain key</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/json.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'domain.api' for an edit of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/json.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'zones.api' for an edit of a zone record!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/json.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'supermasters.api' for an edit of a super master!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/json.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'users.api' for an edit of a user!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/json.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'domain.api' for a deletion of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/json.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'zones.api' for a deletion of a zone record by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/json.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'supermasters.api' for a deletion of a super master by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/json.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'users.api' for a deletion of a user by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/json.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/json.api</a></font><br /><br />
    </blockquote>
    <h2>XML Document Output</h2>
    <p>This is done with the <em>xml.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This provides a list and keys of defined domains on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/domains/xml.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined super-masters on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/masters/xml.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined users on the service</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/users/xml.api</a></font><br /><br />
        <font class="help-title-text">This provides a list and keys of defined zones of a domain per the domain key</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/zones/xml.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'domain.api' for an edit of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/edit/domain/xml.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'zones.api' for an edit of a zone record!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/edit/zones/xml.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'supermasters.api' for an edit of a super master!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/edit/master/xml.api</a></font><br /><br />
        <font class="help-title-text">This URL is for passing the fields as per the 'users.api' for an edit of a user!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/edit/user/xml.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'domain.api' for a deletion of a domain!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $domainkey; ?>/delete/domain/xml.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'zones.api' for a deletion of a zone record by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $zonekey; ?>/delete/zones/xml.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'supermasters.api' for a deletion of a super master by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $masterkey; ?>/delete/master/xml.api</a></font><br /><br />
        <font class="help-title-text">No fields passing the fields as per the 'users.api' for a deletion of a user by specifying key!</font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v1/<?php echo $authkey; ?>/<?php echo $userkey; ?>/delete/user/xml.api</a></font><br /><br />
    </blockquote>
    <?php if (file_exists(API_FILE_IO_FOOTER)) {
    	readfile(API_FILE_IO_FOOTER);
    }?>	
    <?php if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) { ?>
    <h2>Limits</h2>
    <p>There is a limit of <?php echo MAXIMUM_QUERIES; ?> queries per hour. You can add yourself to the whitelist by using the following form API <a href="http://whitelist.<?php echo domain; ?>/">Whitelisting form (whitelist.<?php echo domain; ?>)</a>. This is only so this service isn't abused!!</p>
    <?php } ?>
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2013 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>
<?php 
