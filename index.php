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


	$parts = explode(".", microtime(true));
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	$salter = ((float)(mt_rand(0,1)==1?'':'-').$parts[1].'.'.$parts[0]) / sqrt((float)$parts[1].'.'.intval(cosh($parts[0])))*tanh($parts[1]) * mt_rand(1, intval($parts[0] / $parts[1]));
	header('Blowfish-salt: '. $salter);
	
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'apiconfig.php';
	
	$odds = $inner = array();
	foreach($_GET as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values)?$values:md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	foreach($_POST as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values)?$values:md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	foreach(parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?')?'&':'?').$_SERVER['QUERY_STRING'], PHP_URL_QUERY) as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values)?$values:md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	//die(print_r($inner, true));
	switch ($inner['mode'])
	{
	    default:
	        $help = true;
	        break;
	    case 'authkey':
	        $data = getAuthKey($inner['username'], $inner['password'], $inner['format']);
	        break;
	    case 'newsupermaster':
	    case 'supermaster':
	        $data = addSupermaster($inner['authkey'], $inner['ip'], $inner['nameserver'], $inner['format']);
	        break;
	    case 'domains':
	        if (!empty($inner['name']) || !empty($inner['master']) &&  !empty($inner['type']))
	           $data = addDomains($inner['authkey'], $inner['name'], $inner['master'], $inner['type'], $inner['format']);
	        else 
	            $data = getDomains($inner['authkey'], $inner['format']);
	        break;
	    case 'masters':
	        $data = getSupermasters($inner['authkey'], $inner['format']);
	        break;
	    case 'newzone':
	    case 'zones':
	        if (!empty($inner['domain']) && !empty($inner['type']) && !empty($inner['name']))
	           $data = addZones($inner['authkey'], $inner['domain'], $inner['type'], $inner['name'], $inner['content'], $inner['ttl'], $inner['prio'], $inner['format']);
	        elseif (!empty($inner['authkey']) && !empty($inner['key']))
	            $data = getZones($inner['authkey'], $inner['key']);
	        break;
	    case 'users':
	        if (!empty($inner['uname']) && !empty($inner['email']) && !empty($inner['pass']) && !empty($inner['vpass']))
	            $data = addUser($inner['authkey'], $inner['uname'], $inner['email'], $inner['pass'], $inner['vpass'], $inner['format']);
	        else
    	        $data = getUsers($inner['authkey'], $inner['format']);
	        break;
	    case 'edit':
	        switch ($inner['type'])
	        {
	            case 'zone':
	                $data = editRecord('records', $inner['authkey'], getRecordID($inner['key']), $inner, array('name', 'content', 'ttl', 'prio'), $inner['format']);
	                break;
	            case 'domain':
	                $data = editRecord('domains', $inner['authkey'], getDomainID($inner['key']), $inner, array('name', 'master', 'type'), $inner['format']);
                    break;
	            case 'master':
	                $data = editRecord('supermasters', $inner['authkey'], getSupermasterID($inner['key']), $inner, array('ip', 'nameserver'), $inner['format']);
	                break;
	            case 'user':
	                $data = editRecord('users', $inner['authkey'], getUserID($inner['key']), $inner, array('uname', 'email', 'pass', 'vpass'), $inner['format']);
	                break;

	        }
	        break;
	    case 'delete':
	        switch ($inner['type'])
	        {
	            case 'zone':
	                $data = deleteRecord('records', $inner['authkey'], getRecordID($inner['key']), $inner['format']);
	                break;
	            case 'domain':
	                $data = deleteRecord('domains', $inner['authkey'], getDomainID($inner['key']), $inner['format']);
	                break;
	            case 'master':
	                $data = deleteRecord('supermasters', $inner['authkey'], getSupermasterID($inner['key']), $inner['format']);
	                break;
	            case 'user':
	                $data = deleteRecord('users', $inner['authkey'], getUserID($inner['key']), $inner['format']);
	                break;
	                
	        }
	        break;
	}
	
	/**
	 * Buffers Help
	 */
	if ($help==true) {
		if (function_exists("http_response_code"))
			http_response_code(400);
		include dirname(__FILE__).'/help.php';
		exit;
	}
	
	/**
	 * Calculates Whitelist
	 */
	if (function_exists('whitelistGetIP') && function_exists('whitelistGetIPAddy') && defined('MAXIMUM_QUERIES'))
	{
		session_start();
		if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) {
			if (isset($_SESSION['reset']) && $_SESSION['reset']<microtime(true))
				$_SESSION['hits'] = 0;
			if ($_SESSION['hits']<=MAXIMUM_QUERIES) {
				if (!isset($_SESSION['hits']) || $_SESSION['hits'] = 0)
					$_SESSION['reset'] = microtime(true) + 3600;
				$_SESSION['hits']++;
			} else {
				header("HTTP/1.0 404 Not Found");
				if (function_exists("http_response_code"))
					http_response_code(404);
				exit;
			}
		}
	}
	
	/**
	 * Commences Execution of API Functions
	 */
	if (function_exists("http_response_code"))
	    http_response_code((isset($data['code'])?$data['code']:200));
    if (isset($data['code']))
        unset($data['code']);
    
	switch ($inner['format']) {
		default:
			echo '<pre style="font-family: \'Courier New\', Courier, Terminal; font-size: 0.77em;">';
			echo var_dump($data, true);
			echo '</pre>';
			break;
		case 'raw':
			echo "<?php\n\n return " . var_export($data, true) . ";\n\n?>";
			break;
		case 'json':
			header('Content-type: application/json');
			echo json_encode($data);
			break;
		case 'serial':
			header('Content-type: text/html');
			echo serialize($data);
			break;
		case 'xml':
			header('Content-type: application/xml');
			$dom = new XmlDomConstruct('1.0', 'utf-8');
			$dom->fromMixed(array('root'=>$data));
 			echo $dom->saveXML();
			break;
	}
	exit(0);
?>
