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
    <p>This is an API Service for conducting a whois on both IPv4, IPv6 and domain names. It provides a range of document standards for you to access the API inclusing JSON, XML, Serialisation, HTML and RAW outputs.</p>
    <p>You can access the API currently without a key or system it is an open api and was written in response to the many API Services that charge ridiculous amounts for querying such a simple base. The following instructions are how to access the api I hope you enjoy this api as I have writting it with the help of net registry.</p>
	<h2>Code API Documentation</h2>
    <p>You can find the phpDocumentor code API documentation at the following path :: <a href="<?php echo API_URL . '/'; ?>docs/" target="_blank"><?php echo API_URL . '/'; ?>docs/</a>. These should outline the source code core functions and classes for the API to function!</p>
    <h2>RAW Document Output</h2>
    <p>This is done with the <em>raw.api</em> extension at the end of the url, you replace the example address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This is for a domain of <em>'example.com'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/example.com/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v2/example.com/raw.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv4 address of <em>'125.23.45.111'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/125.23.45.111/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v2/125.23.45.111/raw.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv6 address of <em>'2001:0:9d38:953c:1052:39d8:8355:2880'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/raw.api" target="_blank"><?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/raw.api</a></font><br /><br />
    </blockquote>
    <h2>HTML Document Output</h2>
    <p>This is done with the <em>html.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This is for a domain of <em>'example.com'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/example.com/html.api" target="_blank"><?php echo API_URL . '/'; ?>v2/example.com/html.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv4 address of <em>'125.23.45.111'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/125.23.45.111/html.api" target="_blank"><?php echo API_URL . '/'; ?>v2/125.23.45.111/html.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv6 address of <em>'2001:0:9d38:953c:1052:39d8:8355:2880'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/html.api" target="_blank"><?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/html.api</a></font><br /><br />
    </blockquote>
    <h2>Serialisation Document Output</h2>
    <p>This is done with the <em>serial.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This is for a domain of <em>'example.com'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/example.com/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v2/example.com/serial.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv4 address  of <em>'125.23.45.111'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/125.23.45.111/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v2/125.23.45.111/serial.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv6 address of <em>'2001:0:9d38:953c:1052:39d8:8355:2880'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/serial.api" target="_blank"><?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/serial.api</a></font><br /><br />
    </blockquote>
    <h2>JSON Document Output</h2>
    <p>This is done with the <em>json.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This is for a domain of <em>'example.com'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/example.com/json.api" target="_blank"><?php echo API_URL . '/'; ?>v2/example.com/json.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv4 address  of <em>'125.23.45.111'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/125.23.45.111/json.api" target="_blank"><?php echo API_URL . '/'; ?>v2/125.23.45.111/json.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv6 address of <em>'2001:0:9d38:953c:1052:39d8:8355:2880'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/json.api" target="_blank"><?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/json.api</a></font><br /><br />
    </blockquote>
    <h2>XML Document Output</h2>
    <p>This is done with the <em>xml.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
        <font class="help-title-text">This is for a domain of <em>'example.com'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/example.com/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v2/example.com/xml.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv4 address of <em>'125.23.45.111'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/125.23.45.111/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v2/125.23.45.111/xml.api</a></font><br /><br />
        <font class="help-title-text">This is for a IPv6 address of <em>'2001:0:9d38:953c:1052:39d8:8355:2880'</em></font><br/>
        <font class="help-url-example"><a href="<?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/xml.api" target="_blank"><?php echo API_URL . '/'; ?>v2/2001:0:9d38:953c:1052:39d8:8355:2880/xml.api</a></font><br /><br />
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
