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


	/**
	 * API Server Class Factory
	 *
	 * @author     Simon Roberts <meshy@labs.coop>
	 * @package    whois
	 * @subpackage api
	 */
	class apiserver {
		
	    var $_emails = array();
	    
	    var $_urls = array();
	    
	    var $_domains = array();
	    
		/**
		 *  __construct()
		 *  Constructor
		 */
		function __construct() {
			if (strlen(session_id())==0) {
				session_name(__CLASS__);
				session_id(md5(sha1($this->getIP())));
				session_start();
			}
		}
		
		
		/**
		 *  __destruct()
		 *  Destructor
		 */
		function __destruct() {
			session_commit();
		}
		/**
		 * parseToArray()
		 * Parses Whois Services Text/Data into an array
		 * 
		 * @param string $data
		 * @param string $item
		 * @param string $function
		 * @param string $class
		 * @param string $output
		 * @return array
		 */
		function parseToArray($data, $item, $function, $class, $output = 'html') {
		    
		    $this->_emails = $this->_urls = $this->_domains = $response = $legal = array();
	
		    $parts = explode("\n", $data);
		    foreach($parts as $line => $result) {
		        foreach (array('..'=>'.', '  '=>' ', '--'=>'-', '::'=>':', ': '=>':', '. '=>':') as $search => $replace)
		            if (strpos(' '.$result, $search))
		            while(strpos(' '.$result, $search)) {
		                $result = str_replace($search, $replace, $result);
		            }
	            $result = str_replace('State/Province', 'State', $result);
	            $gdata = preg_split("/[:]+/", $result);
		        if (count($gdata) <> 2)
		        {
		            $legal[] = $gdata;
		        } elseif (count($gdata) == 2)
		        {
		            $fields = preg_split("/[\s-]+/", strtolower($gdata[0]));
		            if (is_array($fields) && count($fields) > 0) {
		                unset($gdata[0]);
		                $val = implode(" ", $gdata);
		                try {
		                    $response = $this->addToArray($fields, $val, $response);
		                } catch (Exception $e) {
		                    echo "$e<br/>";
		                }
		            } elseif (count($fields) == 0) {
		                $response[strtolower($gdata[0])] = $gdata[1];
		            }
	                    
		        }
		    }
		    $response['emails'] = $this->_emails;
		    $response['urls'] = $this->_urls;
		    $response['domains'] = $this->_domains;
		    $response['legal'] = $legal; 
		    return $response;
		}
		
		/**
		 * 
		 * @param unknown $fields
		 * @param unknown $val
		 * @param unknown $response
		 * @return unknown|unknown[]
		 */
		function addToArray($fields, $val, $response)
		{
		   if (!is_array($response))
		      $response = array();
		    
           if (isset($fields[0]))
		      $zero = $fields[0];
		   if (isset($fields[1]))
		      $one = $fields[1];
		   if (isset($fields[2]))
		      $two = $fields[2];
		   if (isset($fields[4]))
		      $three = $fields[3];
		   if (isset($fields[4]))
		      $four = $fields[4];

		   $url = '';
		   if (checkEmail($val)) {
		       $this->_emails[$zero] = $val;
		       $parts = explode("@", $val);
		       $this->_domains[$zero] = $this->getBaseDomain($parts[1]);
		   } elseif(strpos($val, 'http')>0) {
		       $parts = preg_split("/[\s-]+/", $val);
		       foreach($parts as $key => $value)
		           if (substr($value, 0, 4) == 'http')
		           {
		               $url = $value;
		               $this->_domains[$zero] = $this->getBaseDomain(parse_url($value, PHP_URL_HOST));
		               unset($parts[$key]);
		           }
		       $val = trim(implode(" ", $parts));
		   }
	       
	       if (isset($four) && !empty($four) && !empty($val))
	           @$response[$zero][$one][$two][$three][$four] = $val;
           elseif (isset($three) && !empty($three) && !empty($val))
	           @$response[$zero][$one][$two][$three] = $val;
           elseif (isset($two) && !empty($two) && !empty($val))
	           @$response[$zero][$one.'-'.$two] = $val;
           elseif (isset($one) && !empty($one) && !empty($val))
	           @$response[$zero][$one] = $val;
           elseif (isset($zero) && !empty($zero) && !empty($val))
	           @$response[$zero] = $val;
	        	           
           if (isset($four) && !empty($four) && !empty($url))
                @$response[$zero][$one][$two][$three][$four.'-url'] = $url;
           elseif (isset($three) && !empty($three) && !empty($url))
                @$response[$zero][$one][$two][$three.'-url'] = $url;
           elseif (isset($two) && !empty($two) && !empty($url))
                @$response[$zero][$one.'-'.$two.'-url'] = $url;
           elseif (isset($one) && !empty($one) && !empty($url))
                @$response[$zero][$one.'-url'] = $url;
           elseif (isset($zero) && !empty($zero) && !empty($url))
                @$response[$zero.'-url'] = $url;
               
	        return $response;
		}
		
		/**
		 * getIP()
		 * Gets Users IP Address
		 *
		 * @return string
		 */
		function getIP() {
			$ip = $_SERVER['REMOTE_ADDR'];
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			return $ip;
		}
		
		/**
		 * extractURLS()
		 * Pulls URLs from String Data
		 * 
		 * @param string $data
		 * @param array $ret
		 * @return array
		 */
		function extractURLS($data, $ret) {
			$valid_chars = "a-z0-9\/\-_+=.~!%@?#&;:\,$\|";
			$end_chars   = "a-z0-9\/\-_+=~!%@?#&;:\,$\|";
			$ret = $this->findWebsites($ret, $ret, '');
			$patterns   = array();
			$patterns[]     = "/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([{$valid_chars}]+[{$end_chars}])/ei";
			$patterns[]     = "/(^|[^]_a-z0-9-=\"'\/:\.])www\.((([a-zA-Z0-9\-]*\.){1,}){1}([a-zA-Z]{2,6}){1})((\/([a-zA-Z0-9\-\._\?\,\'\/\\+&%\$#\=~])*)*)/ei";
			$patterns[]     = "/((([a-zA-Z0-9\-]*\.){1,}){1}([a-zA-Z]{2,6}){1})((\/([a-zA-Z0-9\-\._\?\,\'\/\\+&%\$#\=~])*)*)/ei";
			$patterns[]     = "/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([{$valid_chars}]+[{$end_chars}])/ei";
			$match = array();
			foreach($patterns as $id => $pattern) {
       			preg_match($pattern, $data, $result);
       			$match[$id] = $result;
       		}
       		foreach($match as $id => $urls) {
       		    if (isset($urls[0]) && is_string($urls[0])) {
	       			$ret['urls'][$this->getBaseDomain($urls[0])]['url-'.(sizeof($ret['urls'][$this->getBaseDomain($urls[0])])+1)] = $urls[0];
	       			$ret['domain'][$this->getBaseDomain($urls[0])] = $this->getBaseDomain($urls[0]);
       			}
       		}
       		return $ret;		
		}
		
		/**
		 * extractEmails()
		 * Pulls Emails from String Data
		 * 
		 * @param string $data
		 * @param array $ret
		 * @return array
		 */
		function extractEmails($data, $ret) {
			$valid_chars = "a-z0-9\/\-_+=.~!%@?#&;:,$\|";
			$end_chars   = "a-z0-9\/\-_+=~!%@?#&;:,$\|";
			$ret = $this->findEmails($ret, $ret, '');		
			$patterns   = array();
			$patterns[]     = "/(^|[^]_a-z0-9-=\"'\/:\.]+)([-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+)@((?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?)/ei";
			$patterns[]		= "^[_a-zA-Z0-9-\-_]+(\.[_a-zA-Z0-9-\-_]+)*@[a-zA-Z0-9-\-]+(\.[a-zA-Z0-9-\-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))$";
       		$match = array();
       		foreach($patterns as $id => $pattern) {
       			preg_match($pattern, $data, $result);
       			$match[$id] = $result;
       		}
       		foreach($match as $id => $email) {
       			if (isset($email[0])) {
	       			$ret['email'][$email[3]][$email[2]] = trim($email[0]);
	       			$ret['domains'][$email[3]] = $email[3];
       			}
       		}
       		return $ret;		
		}
		
		/**
		 * extractEmails()
		 * Pulls Emails from String Data
		 *
		 * @param string $data
		 * @param array $ret
		 * @return array
		 */
		function sortArray($data = array(), $dir = SORT_DESC) {
		    $string = $array = false;
		    foreach($data as $key => $values) {
		        if (is_string($values)) {
		            $string = true;
		        } elseif (is_array($values)) {
		            $array = true;
		        }
		    }
		    if ($array==true)
		    {
		        foreach($data as $key => $values) {
		            if (is_array($values)) {
		                $data[$key] = self::sortArray($values, $dir);
		            }
		        }
		    }
		    if ($string==true)
		    {
		        $ret = $quekeys = $queue = array();
		        foreach($data as $key => $values) {
		            if (is_string($values)) {
		                $queue[$key] = $values;
		                $quekeys[$key] = $key;
		                unset($data[$key]);
		            }
		        }
		        foreach(array_keys($data) as $key)
		            $quekeys[$key] = $key;
		        sort($quekeys, $dir);
		        
		        foreach($quekeys as $key) {
		            $ret[$key] = (isset($queue[$key])?$queue[$key]:$data[$key]);
		        }
		        return $ret;
		    }
		    return $data;
		}
		
		/**
		 * extractEmails()
		 * Pulls Emails from String Data
		 *
		 * @param string $data
		 * @param array $ret
		 * @return array
		 */
		function cleanEmails($data = array(), &$ret = array()) {
		    foreach($data as $key => $values) {
		        if (is_string($values)) {
        		    if (strpos($values, ' ')) {
        		       foreach(explode(" ", $values) as $value)
        		       {
        		           if (checkEmail($value) == $value)
        		               return $value;
        		       }
        		    } elseif (checkEmail($values) == $values) {
        		       return $values;
        		    }
    		    } elseif (is_array($values)) {
    		        $ret[$key] = $data[$key] = self::cleanEmails($values, $ret);
    		    }
		    }
            return $data;
		}
		/**
		 * findEmails()
		 * Extracts Email Addresses from Array Data
		 * 
		 * @param array $ret
		 * @param string $where
		 * @param string $key
		 * @return array
		 */
		function findEmails($ret, $where, $key) {
			if ($key == 'email'&& !is_array($where)) {
				$address = explode('@', $where);
				$ret['email'][$address[1]][$address[0]] = $where;
			} elseif (is_array($where)) {
				foreach($where as $key => $value) {
					$ret = $this->findEmails($ret, $where[$key], $key);
				}
			}
			return $ret;
		}
		/**
		 * findWebsites()
		 * Extracts Websites from Array Data
		 * 
		 * @param array $ret
		 * @param string $where
		 * @param string $key
		 * @return array
		 */
		function findWebsites($ret, $where, $key) {
			if (in_array($key, array('website','domain','site')) && !is_array($where)) {
				$ret['urls'][$this->getBaseDomain($where)]['url-'.(sizeof($ret['urls'][$this->getBaseDomain($where)])+1)] = $where;
	       		$ret['domains'][$this->getBaseDomain($where)] = $this->getBaseDomain($where);
			} elseif (is_array($where)) {
				foreach($where as $key => $value) {
					$ret = $this->findWebsites($ret, $where[$key], $key);
				}
			}
			return $ret;
		}
		
		/**
		 * validateEmail()
		 * Validates an Email Address
		 * 
		 * @param string $email
		 * @return boolean
		 */
		function validateEmail($email) {
			if(preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))$", $email)) {
				return true;
			} else {
				return false;
			}
		}
		/**
		 * validateDomain()
		 * Validates a Domain Name
		 *
		 * @param string $domain
		 * @return boolean
		 */
		static function validateDomain($domain) {
			if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) {
				return false;
			}
			return $domain;
		}
		
		/**
		 * validateIPv4()
		 * Validates and IPv6 Address
		 * 
		 * @param string $ip
		 * @return boolean
		 */
		static function validateIPv4($ip) {
			if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === FALSE) // returns IP is valid
			{
				return false;
			} else {
				return true;
			}
		}
		
		/**
		 * validateIPv6()
		 * Validates and IPv6 Address
		 * 
		 * @param string $ip
		 * @return boolean
		 */
		static function validateIPv6($ip) {
			if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) // returns IP is valid
			{
				return false;
			} else {
				return true;
			}
		}
		
		/**
		 * getBaseDomain()
		 * Removes Subdomains from Domain String
		 * 
		 * @param string $url
		 * @param boolean $debug
		 * @return string
		 */
		function getBaseDomain($realm = '')
		{
	        
	        if (!is_array($fallout = APICache::read('networking-fallout-nodes')) || count($fallout) == 0)
	        {
	            $fallout = array_keys(eval('?>'.getURIData(API_STRATA_API_URL."/v2/fallout/raw.api", 120, 120).'<?php'));
	            APICache::write('networking-fallout-nodes', $fallout, 3600 * 24 * 7 * mt_rand(2, 9) * mt_rand(2, 9));
	        }
	        
	        if (!is_array($classes = APICache::read('networking-strata-nodes')) || count($classes) == 0)
	        {
	            $classes = array_keys(eval('?>'.getURIData(API_STRATA_API_URL."/v2/strata/raw.api", 120, 120).'<?php'));
	            foreach($classes as $key => $value)
	                if (in_array($value, $fallout))
	                    unset($classes[$key]);
	            APICache::write('networking-strata-nodes', $classes, 3600 * 24 * 7 * mt_rand(2, 9) * mt_rand(2, 9));
	        }
	        
	        // Get Full Hostname
	        if (!filter_var($realm, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 || FILTER_FLAG_IPV4) === false)
	            return $realm;
		    
            // break up domain, reverse
            $elements = explode('.', $realm);
            $elements = array_reverse($elements);
            
            // Returns Base Domain
            if (in_array($elements[0], $classes))
                return $elements[1] . '.' . $elements[0];
            elseif (in_array($elements[0], $fallout) && in_array($elements[1], $classes))
                return $elements[2] . '.' . $elements[1] . '.' . $elements[0];
            elseif (in_array($elements[0], $fallout))
                return  $elements[1] . '.' . $elements[0];
            else
                return  $elements[1] . '.' . $elements[0];
                    
            return $realm;
		}
		
		/**
		 * getBaseDomain()
		 * Removes Subdomains from Domain String
		 *
		 * @param string $url
		 * @param boolean $debug
		 * @return string
		 */
		function getBaseClass($realm = '')
		{
		    		        
	        static $fallout, $classes;
	        
	        if (!is_array($fallout = APICache::read('networking-fallout-nodes')) || count($fallout) == 0)
	        {
	            $fallout = array_keys(eval('?>'.getURIData(API_STRATA_API_URL."/v2/fallout/raw.api", 120, 120).'<?php'));
	            APICache::write('networking-fallout-nodes', $fallout, 3600 * 24 * 7 * mt_rand(2, 9) * mt_rand(2, 9));
	        }
	        
	        if (!is_array($classes = APICache::read('networking-strata-nodes')) || count($classes) == 0)
	        {
	            $classes = array_keys(eval('?>'.getURIData(API_STRATA_API_URL."/v2/strata/raw.api", 120, 120).'<?php'));
	            foreach($classes as $key => $value)
	                if (in_array($value, $fallout))
	                    unset($classes[$key]);
	            APICache::write('networking-strata-nodes', $classes, 3600 * 24 * 7 * mt_rand(2, 9) * mt_rand(2, 9));
	        }
	        
	        // Get Full Hostname
	        if (!filter_var($realm, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 || FILTER_FLAG_IPV4) === false)
	            return $realm;
	            
            // break up domain, reverse
            $elements = explode('.', $realm);
            $elements = array_reverse($elements);
	            
		            // Returns Base Domain
            if (in_array($elements[0], $classes))
                return $elements[0];
            elseif (in_array($elements[0], $fallout) && in_array($elements[1], $classes))
                return $elements[1] . '.' . $elements[0];
            elseif (in_array($elements[0], $fallout))
                return  $elements[0];
            else
                return  $elements[0];
                
		    return 'com';
		}
	}
