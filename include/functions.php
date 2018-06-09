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



if (!function_exists("getAuthKey")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getAuthKey($username, $password, $format = 'json')
    {
        $return = array();
        $sql = "SELECT `uid`, `email`, `last_login` FROM `users` WHERE `uname` LIKE '$username' AND (`pass` LIKE '$password' OR `pass` LIKE MD5('$password'))";
        list($uid, $email, $last_login) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($uid != $last_login && $uid <> 0)
        {
            $time = time();
            if ($last_login < $time - 3600) {
                $GLOBALS['APIDB']->queryF("UPDATE `users` SET `last_login` = '$time', `hits` = `hits` + 1 WHERE `uid` = '$uid'");
                $last_login = $time;
            }
            $sql = "SELECT md5(concat(`uid`, `uname`, `email`, `last_login`)) FROM `users` WHERE `uid` = '$uid'";
            list($authkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            $_SESSION['authkey'] = $authkey;
            setcookie('authkey', $_SESSION['authkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 201, 'authkey' => $_SESSION['authkey'], 'errors' => array());
        } else {
            $_SESSION['authkey'] = md5(NULL);
            setcookie('authkey', $_SESSION['authkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'authkey' => $_SESSION['authkey'] = md5(NULL), 'errors' => array('101' => 'Username and/Or Password Mismatch'));
        }
        return $return;
    }
}

if (!function_exists("getDomainID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getDomainID($domainkey = '')
    {
        $sql = "SELECT `id` FROM `domains` WHERE '$domainkey' LIKE md5(concat(`id`, '".API_URL."', 'domain'))";
        list($id) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($id <> 0)
        {
            return $id;
        } else {
            $_SESSION['domainkey'] = md5(NULL.'domain');
            setcookie('domainkey', $_SESSION['domainkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('102' => 'Domain Key is not valid!'));
        }
        return $return;
    }
}

if (!function_exists("getRecordID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getRecordID($recordkey = '')
    {
        $sql = "SELECT `id` FROM `records` WHERE '$recordkey' LIKE md5(concat(`id`, '".API_URL."', 'record'))";
        list($id) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($id <> 0)
        {
            return $id;
        } else {
            $_SESSION['recordkey'] = md5(NULL.'record');
            setcookie('recordkey', $_SESSION['recordkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('104' => 'Record Key is not valid!'));
        }
        return $return;
    }
}


if (!function_exists("getSupermasterID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getSupermasterID($masterkey = '')
    {
        $sql = "SELECT `id` FROM `supermasters` WHERE '$masterkey' LIKE md5(concat(`id`, '".API_URL."', 'supermaster'))";
        list($id) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($id <> 0)
        {
            return $id;
        } else {
            $_SESSION['masterkey'] = md5(NULL.'supermaster');
            setcookie('masterkey', $_SESSION['masterkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('105' => 'Supermaster Key is not valid!'));
        }
        return $return;
    }
}

if (!function_exists("getUserID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getUserID($userkey = '')
    {
        $sql = "SELECT `uid` FROM `users` WHERE '$userkey' LIKE md5(concat(`uid`, '".API_URL."', 'user'))";
        list($uid) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($uid <> 0)
        {
            return $uid;
        } else {
            $_SESSION['userkey'] = md5(NULL.'user');
            setcookie('userkey', $_SESSION['userkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('105' => 'User Key is not valid!'));
        }
        return $return;
    }
}
if (!function_exists("checkAuthKey")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function checkAuthKey($authkey = '')
    {
        $sql = "SELECT `uid`, `uname` FROM `users` WHERE '$authkey' LIKE md5(concat(`uid`, `uname`, `email`, `last_login`))";
        list($uid, $uname) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($uid <> 0 && !empty($uname))
        {
            $GLOBALS['account'] = $uname;
            $time = time();
            $GLOBALS['APIDB']->queryF("UPDATE `users` SET `last_online` = '$time', `hits` = `hits` + 1 WHERE `uid` = '$uid'");
            $return = array();
        } else {
            $_SESSION['authkey'] = md5(NULL);
            setcookie('authkey', $_SESSION['authkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('102' => 'AuthKey is not valid!'));
        }
        return $return;
    }
}

if (!function_exists("addSupermaster")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function addSupermaster($authkey, $ip = '', $nameserver = '', $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $sql = "SELECT COUNT(*) FROM `supermasters` WHERE `ip` LIKE '$ip' AND `nameserver` LIKE '$nameserver'";
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count==0)
            {
                $sql = "INSERT INTO `supermasters` (`ip`, `nameserver`, `account`) VALUES ('$ip', '$nameserver', '" . $GLOBALS['account'] . "')";
                if ($GLOBALS['APIDB']->queryF($sql))
                {
                    $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'supermaster')) FROM `supermasters` WHERE `id` = '".$GLOBALS['APIDB']->getInsertId()."'";
                    list($masterkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                    $_SESSION['masterkey'] = $masterkey;
                    setcookie('masterkey', $_SESSION['masterkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
                    $return = array('code' => 201, 'masterkey' => $_SESSION['masterkey'], 'errors' => array());
                } else {
                    $return = array('code' => 501, 'masterkey' => md5(NULL. 'supermaster'), 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                }
            } else {
                $return = array('code' => 501, 'masterkey' => md5(NULL. 'supermaster'), 'errors' => array('103' => 'Record Already Exists!!!'));
            }
        }
        return $return;
    }
}

if (!function_exists("addDomains")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function addDomains($authkey, $name = '', $master = '', $type = '', $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $sql = "SELECT COUNT(*) FROM `domains` WHERE (`name` LIKE '$name' AND `master` LIKE '$master' AND `type` LIKE '$type')";
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count==0)
            {
                $sql = "INSERT INTO `domains` (`name`, `master`, `type`, `account`) VALUES ('$name', '$master', '$type', '" . $GLOBALS['account'] . "')";
                if ($GLOBALS['APIDB']->queryF($sql))
                {
                    $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'domain')) FROM `domains` WHERE `id` = '".$GLOBALS['APIDB']->getInsertId()."'";
                    list($domainkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                    $_SESSION['domainkey'] = $domainkey;
                    setcookie('domainkey', $_SESSION['masterkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
                    $return = array('code' => 201, 'domainkey' => $_SESSION['domainkey'], 'errors' => array());
                } else {
                    $return = array('code' => 501, 'domainkey' => md5(NULL. 'domainkey'), 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                }
            } else {
                $return = array('code' => 501, 'domainkey' => md5(NULL. 'domainkey'), 'errors' => array('103' => 'Record Already Exists!!!'));
            }
        }
        return $return;
    }
}

if (!function_exists("addZones")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function addZones($authkey, $domainkey, $type = '', $name = '', $content = '', $ttl = 19200, $prio = 0, $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $domainid = getDomainID($domainkey);
            if (!empty($domainid) && is_array($domainid))
                return $domainid;
            
            if (in_array($type, array('A', 'AAAA', 'AFSDB', 'ALIAS', 'CAA', 'CERT', 'CDNSKEY', 'CDS', 'CNAME', 'DNSKEY', 'DNAME', 'DS', 'HINFO', 'KEY', 'LOC', 'MX', 'NAPTR', 'NS', 'NSEC', 'NSEC3', 'NSEC3PARAM', 'OPENPGPKEY', 'PTR', 'RP', 'RRSIG', 'SOA', 'SPF', 'SSHFP', 'SRV', 'TKEY', 'TSIG', 'TLSA', 'SMIMEA', 'TXT', 'URI')))
            {
                $sql = "SELECT COUNT(*) FROM `records` WHERE `domain_id` = '$domainid' AND (`name` LIKE '" .$GLOBALS['APIDB']->escape($name). "' AND `content` LIKE '" .$GLOBALS['APIDB']->escape($content). "' AND `type` LIKE '$type')";
                list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                if ($count==0)
                {
                    $sql = "INSERT INTO `records` (`domain_id`, `name`, `content`, `type`, `ttl`, `prio`, `change_date`) VALUES ('$domainid', '" .$GLOBALS['APIDB']->escape($name). "', '" .$GLOBALS['APIDB']->escape($content). "', '$type', '$ttl', '$prio', UNIX_TIMESTAMP())";
                    if ($GLOBALS['APIDB']->queryF($sql))
                    {
                        $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'record')) FROM `records` WHERE `id` = '".$GLOBALS['APIDB']->getInsertId()."'";
                        list($recordkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                        $_SESSION['recordkey'] = $recordkey;
                        setcookie('recordkey', $_SESSION['recordkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
                        $return = array('code' => 201, 'recordkey' => $_SESSION['recordkey'], 'errors' => array());
                    } else {
                        $return = array('code' => 501, 'recordkey' => md5(NULL. 'record'), 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                    }
                } else {
                    $return = array('code' => 501, 'recordkey' => md5(NULL. 'record'), 'errors' => array('107' => 'Zone Record Already Exists!!!'));
                }
            } else {
                $return = array('code' => 501, 'errors' => array('106' => 'Record Type: ' . $type . ' is not supported only supporting:~ ' . implode(', ', array('A', 'AAAA', 'AFSDB', 'ALIAS', 'CAA', 'CERT', 'CDNSKEY', 'CDS', 'CNAME', 'DNSKEY', 'DNAME', 'DS', 'HINFO', 'KEY', 'LOC', 'MX', 'NAPTR', 'NS', 'NSEC', 'NSEC3', 'NSEC3PARAM', 'OPENPGPKEY', 'PTR', 'RP', 'RRSIG', 'SOA', 'SPF', 'SSHFP', 'SRV', 'TKEY', 'TSIG', 'TLSA', 'SMIMEA', 'TXT', 'URI'))));
            }
        }
        return $return;
    }
}


if (!function_exists("addUser")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function addUser($authkey, $uname, $email = '', $pass = '', $vpass = '', $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        { 
            if (!checkEmail($email))
                return array('code' => 501, 'errors' => array('109' => 'e-Mail format isn\'t valid!!!'));
            
            if (!empty($pass) && !empty($vpass) && $pass != $vpass)
                return array('code' => 501, 'errors' => array('108' => 'Password & verify password do not match!!!'));
            
            $sql = "SELECT COUNT(*) FROM `users` WHERE (`uname` LIKE '" .$GLOBALS['APIDB']->escape($uname). "') OR (`uname` LIKE '" .$GLOBALS['APIDB']->escape($uname). "' AND `email` LIKE '" .$GLOBALS['APIDB']->escape($email). "')";
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count==0)
            {
                $sql = "INSERT INTO `users` (`uname`, `email`, `pass`) VALUES ('" .$GLOBALS['APIDB']->escape($uname). "', '" .$GLOBALS['APIDB']->escape($email). "', md5('" .$GLOBALS['APIDB']->escape($pass). "'))";
                if ($GLOBALS['APIDB']->queryF($sql))
                {
                    $sql = "SELECT md5(concat(`uid`, '" . API_URL . "', 'user')) FROM `users` WHERE `uid` = '".$GLOBALS['APIDB']->getInsertId()."'";
                    list($userkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                    $_SESSION['userkey'] = $userkey;
                    setcookie('userkey', $_SESSION['userkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
                    $return = array('code' => 201, 'userkey' => $_SESSION['userkey'], 'errors' => array());
                    
                    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'apimailer.php';
                    
                    $mail = new APIMailer(API_LICENSE_EMAIL, API_LICENSE_COMPANY);
                    $body = file_get_contents(__DIR__  . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'new_user_emailtemplate.html' );
                    $body = str_replace('%apilogo', API_URL . '/assets/images/logo_350x350.png', $body);
                    $body = str_replace('%apiurl', API_URL, $body);
                    $body = str_replace('%companyname', API_LICENSE_COMPANY, $body);
                    $body = str_replace('%account', $GLOBALS['account'], $body);
                    $body = str_replace('%uname', $uname, $body);
                    $body = str_replace('%pass', $pass, $body);
                    $body = str_replace('%email', $email, $body);
                    $mail->sendMail($email, array(), array(), "Zone API Creditials as established by: " . $GLOBALS['account'], $body, array(), "", true);
                    
                } else {
                    $return = array('code' => 501, 'recordkey' => md5(NULL. 'user'), 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                }
            } else {
                $return = array('code' => 501, 'recordkey' => md5(NULL. 'user'), 'errors' => array('107' => 'User Record Already Exists!!!'));
            }
        }
        return $return;
    }
}


if (!function_exists("editRecord")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function editRecord($table, $authkey, $id, $vars = array(), $fields = array(), $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            if (!empty($id) && is_array($id))
                return $id;
            
            foreach($vars as $key => $value)
                if (!in_array($key, $fields))
                    unset($vars[$key]);
            
            if (count($vars) == 0)
                return array('code' => 501, 'errors' => array('110' => 'No records fields specified for edit this supports: '.implode(', ', $fields).'!!!'));
            switch ($table)
            {
                case 'users':
                    if (isset($vars['email']) && !empty($vars['email']) && !checkEmail($vars['email']))
                        return array('code' => 501, 'errors' => array('109' => 'e-Mail format isn\'t valid!!!'));
                    if (!empty($vars['pass']) && !empty($vars['vpass']) && $vars['pass'] != $vars['vpass'])
                        return array('code' => 501, 'errors' => array('108' => 'Password & verify password do not match!!!'));
                    elseif (!empty($vars['pass']) && !empty($vars['vpass']) && $vars['pass'] == $vars['vpass']) {
                        $vars['pass'] = md5($vars['pass']);
                        unset($vars['vpass']);
                    } else {
                        unset($vars['pass']);
                        unset($vars['vpass']);
                    }
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `uid` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`uname` LIKE '" .$GLOBALS['APIDB']->escape($vars['uname']). "') OR (`email` LIKE '" .$GLOBALS['APIDB']->escape($vars['email']). "'))";
                    break;
                case 'records':
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `id` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`name` LIKE '" .$GLOBALS['APIDB']->escape($vars['name']). "' AND `content` LIKE '" .$GLOBALS['APIDB']->escape($vars['content']). "' AND `type` LIKE '" . $old['type'] . "'))";
                    break;
                case 'domains':
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `id` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`name` LIKE '" .$GLOBALS['APIDB']->escape($vars['name']). "' AND `type` LIKE '" . $vars['type'] . "') OR (`master` LIKE '" .$GLOBALS['APIDB']->escape($vars['master']). "' AND `type` LIKE '" . $vars['type'] . "'))";
                    break;
                case 'supermasters':
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `id` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`ip` LIKE '" .$GLOBALS['APIDB']->escape($vars['ip']). "' AND `nameserver` LIKE '" .$GLOBALS['APIDB']->escape($vars['nameserver']). "'))";
                    break;
            }
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count==0)
            {
                $sql = "UPDATE `$table` SET ";
                $u=0;
                foreach($vars as $key => $value)
                {
                    $u++;
                    $sql .= "`$key` = '" . $GLOBALS['APIDB']->escape($value) . ($u < count($vars)?"', ":"' ");
                }
                switch ($table)
                {
                    case 'users':
                        $sql .= "WHERE `uid` = '$id'";
                        break;
                    default:
                        $sql .= "WHERE `id` = '$id'";
                        break;
                }
                if ($GLOBALS['APIDB']->queryF($sql))
                {
                    $return = array('code' => 201, 'affected' =>$GLOBALS['APIDB']->getAffectedRows(), 'errors' => array());
                } else {
                    $return = array('code' => 501, 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                }
            } else {
                $return = array('code' => 501, 'errors' => array('107' => 'User Record Already Exists!!!'));
            }
        }
        return $return;
    }
}

if (!function_exists("deleteRecord")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function deleteRecord($table, $authkey, $id)
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            if (!empty($id) && is_array($id))
                return $id;
            
            $sql = "DELETE FROM `$table` ";
            switch ($table)
            {
                case 'users':
                    $sql .= "WHERE `uid` = '$id'";
                    break;
                default:
                    $sql .= "WHERE `id` = '$id'";
                    break;
            }
            if ($GLOBALS['APIDB']->queryF($sql))
            {
                $return = array('code' => 201, 'affected' =>$GLOBALS['APIDB']->getAffectedRows(), 'errors' => array());
            } else {
                $return = array('code' => 501, 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
            }
        } else {
            $return = array('code' => 501, 'errors' => array('107' => 'User Record Already Exists!!!'));
        }
        return $return;
    }
}

if (!function_exists("getDomains")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getDomains($authkey, $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $return['code'] = 201;
            $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'domain')) as `domainkey`, `name`, `master`, `type` FROM `domains` ORDER BY `name` ASC, `master` ASC, `type` DESC";
            $result = $GLOBALS['APIDB']->queryF($sql);
            while($domain = $GLOBALS['APIDB']->fetchArray($result))
                $return['domains'][] = $domain;
        }
        return $return;
    }
}


if (!function_exists("getSupermasters")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getSupermasters($authkey, $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $return['code'] = 201;
            $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'supermaster')) as `masterkey`, `ip`, `nameserver` FROM `supermasters` ORDER BY `ip` ASC, `nameserver` ASC";
            $result = $GLOBALS['APIDB']->queryF($sql);
            while($domain = $GLOBALS['APIDB']->fetchArray($result))
                $return['supermasters'][] = $domain;
        }
        return $return;
    }
}


if (!function_exists("getZones")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getZones($authkey, $domainkey, $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $domainid = getDomainID($domainkey);
            if (!empty($domainid) && is_array($domainid))
                return $domainid;
            
            $return['code'] = 201;
            $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'record')) as `recordkey`, md5(concat(`domain_id`, '" . API_URL . "', 'domain')) as `domainkey`, `name`, `content`, `ttl`, `prio`, `type` FROM `records` WHERE `domain_id` = '$domainid' ORDER BY `type` ASC, `name` ASC, `ttl` ASC";
            $result = $GLOBALS['APIDB']->queryF($sql);
            while($domain = $GLOBALS['APIDB']->fetchArray($result))
                $return['zones'][] = $domain;
        }
        return $return;
    }
}


if (!function_exists("getUsers")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getUsers($authkey, $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $return['code'] = 201;
            $sql = "SELECT md5(concat(`uid`, '" . API_URL . "', 'user')) as `userkey`, `uname`, `email`, `hits`, `last_online`, `last_login` FROM `users` ORDER BY `uname` ASC, `email` ASC, `hits` DESC";
            $result = $GLOBALS['APIDB']->queryF($sql);
            while($user = $GLOBALS['APIDB']->fetchArray($result))
                $return['users'][] = $user;
        }
        return $return;
    }
}


if (!function_exists("checkEmail")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function checkEmail($email, $antispam = false)
    {
        if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            return false;
        }
        $email_array      = explode('@', $email);
        $local_array      = explode('.', $email_array[0]);
        $local_arrayCount = count($local_array);
        for ($i = 0; $i < $local_arrayCount; ++$i) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode('.', $email_array[1]);
            if (count($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < count($domain_array); ++$i) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        if ($antispam) {
            $email = str_replace('@', ' at ', $email);
            $email = str_replace('.', ' dot ', $email);
        }
        
        return $email;
    }
}

if (!function_exists("writeRawFile")) {
    /**
     *
     * @param string $file
     * @param string $data
     */
    function writeRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            mkdir(dirname($file), 0777, true);
            if (is_file($file))
                unlink($file);
                $data = str_replace("\n", $lineBreak, $data);
                $ff = fopen($file, 'w');
                fwrite($ff, $data, strlen($data));
                fclose($ff);
    }
}

if (!function_exists("getCompleteFilesListAsArray")) {
	function getCompleteFilesListAsArray($dirname, $result = array())
	{
		foreach(getCompleteDirListAsArray($dirname) as $path)
			foreach(getFileListAsArray($path) as $file)
				$result[$path.DIRECTORY_SEPARATOR.$file] = $path.DIRECTORY_SEPARATOR.$file;
				return $result;
	}

}


if (!function_exists("getCompleteDirListAsArray")) {
	function getCompleteDirListAsArray($dirname, $result = array())
	{
		$result[$dirname] = $dirname;
		foreach(getDirListAsArray($dirname) as $path)
		{
			$result[$dirname . DIRECTORY_SEPARATOR . $path] = $dirname . DIRECTORY_SEPARATOR . $path;
			$result = getCompleteDirListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
		}
		return $result;
	}

}

if (!function_exists("getCompleteHistoryListAsArray")) {
	function getCompleteHistoryListAsArray($dirname, $result = array())
	{
		foreach(getCompleteDirListAsArray($dirname) as $path)
		{
			foreach(getHistoryListAsArray($path) as $file=>$values)
				$result[$path][sha1_file($path . DIRECTORY_SEPARATOR . $values['file'])] = array_merge(array('fullpath'=>$path . DIRECTORY_SEPARATOR . $values['file']), $values);
		}
		return $result;
	}
}

if (!function_exists("getDirListAsArray")) {
	function getDirListAsArray($dirname)
	{
		$ignored = array(
				'cvs' ,
				'_darcs');
		$list = array();
		if (substr($dirname, - 1) != '/') {
			$dirname .= '/';
		}
		if ($handle = opendir($dirname)) {
			while ($file = readdir($handle)) {
				if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
					continue;
					if (is_dir($dirname . $file)) {
						$list[$file] = $file;
					}
			}
			closedir($handle);
			asort($list);
			reset($list);
		}

		return $list;
	}
}

if (!function_exists("getFileListAsArray")) {
	function getFileListAsArray($dirname, $prefix = '')
	{
		$filelist = array();
		if (substr($dirname, - 1) == '/') {
			$dirname = substr($dirname, 0, - 1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
					$file = $prefix . $file;
					$filelist[$file] = $file;
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}

		return $filelist;
	}
}

if (!function_exists("getHistoryListAsArray")) {
	function getHistoryListAsArray($dirname, $prefix = '')
	{
		$formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'history-formats.diz'));
		$filelist = array();

		if ($handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				foreach($formats as $format)
					if (substr(strtolower($file), strlen($file)-strlen(".".$format)) == strtolower(".".$format)) {
						$file = $prefix . $file;
						$filelist[$file] = array('file'=>$file, 'type'=>$format, 'sha1' => sha1_file($dirname . DIRECTORY_SEPARATOR . $file));
					}
			}
			closedir($handle);
		}
		return $filelist;
	}
}


if (!function_exists("cleanWhitespaces")) {
	/**
	 *
	 * @param array $array
	 */
	function cleanWhitespaces($array = array())
	{
		foreach($array as $key => $value)
		{
			if (is_array($value))
				$array[$key] = cleanWhitespaces($value);
				else {
					$array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
				}
		}
		return $array;
	}
}


if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIPAddy()
	 * 
	 * 	provides an associative array of whitelisted IP Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @return 		array
	 */
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/* function whitelistGetNetBIOSIP()
	 *
	 * 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		array
	 */
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		} 
		return $ret;
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIP()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @param		boolean		$asString	Whether to return an address or network long integer
	 * 
	 * @return 		mixed
	 */
	function whitelistGetIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}


if (!function_exists("getURIData")) {
    
    /* function yonkURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function getURIData($uri = '', $timeout = 25, $connectout = 25, $post = array(), $headers = array())
    {
        if (!function_exists("curl_init"))
        {
            die("Install PHP Curl Extension ie: $ sudo apt-get install php-curl -y");
        }
        $GLOBALS['php-curl'][md5($uri)] = array();
        if (!$btt = curl_init($uri)) {
            return false;
        }
        if (count($post)==0 || empty($post))
            curl_setopt($btt, CURLOPT_POST, false);
            else {
                $uploadfile = false;
                foreach($post as $field => $value)
                    if (substr($value , 0, 1) == '@' && !file_exists(substr($value , 1, strlen($value) - 1)))
                        unset($post[$field]);
                    else
                        $uploadfile = true;
                curl_setopt($btt, CURLOPT_POST, true);
                curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post));
                
                if (!empty($headers))
                    foreach($headers as $key => $value)
                        if ($uploadfile==true && substr($value, 0, strlen('Content-Type:')) == 'Content-Type:')
                            unset($headers[$key]);
                if ($uploadfile==true)
                    $headers[]  = 'Content-Type: multipart/form-data';
            }
            if (count($headers)==0 || empty($headers))
                curl_setopt($btt, CURLOPT_HEADER, false);
                else {
                    curl_setopt($btt, CURLOPT_HEADER, true);
                    curl_setopt($btt, CURLOPT_HTTPHEADER, $headers);
                }
                curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
                curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($btt, CURLOPT_VERBOSE, false);
                curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
                $data = curl_exec($btt);
                $GLOBALS['php-curl'][md5($uri)]['http']['posts'] = $post;
                $GLOBALS['php-curl'][md5($uri)]['http']['headers'] = $headers;
                $GLOBALS['php-curl'][md5($uri)]['http']['code'] = curl_getinfo($btt, CURLINFO_HTTP_CODE);
                $GLOBALS['php-curl'][md5($uri)]['header']['size'] = curl_getinfo($btt, CURLINFO_HEADER_SIZE);
                $GLOBALS['php-curl'][md5($uri)]['header']['value'] = curl_getinfo($btt, CURLINFO_HEADER_OUT);
                $GLOBALS['php-curl'][md5($uri)]['size']['download'] = curl_getinfo($btt, CURLINFO_SIZE_DOWNLOAD);
                $GLOBALS['php-curl'][md5($uri)]['size']['upload'] = curl_getinfo($btt, CURLINFO_SIZE_UPLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['length']['download'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['length']['upload'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_UPLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['type'] = curl_getinfo($btt, CURLINFO_CONTENT_TYPE);
                curl_close($btt);
                return $data;
    }
}

if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 * 
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {
	
		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 * 
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {
	
			$domElement = is_null($domElement) ? $this : $domElement;
	
			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {
	
					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}
					 
					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}
					 
					$this->fromMixed($mixedElement, $node);
					 
				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}
			 
		}
		 
	}
}


function getHTMLForm($mode = '', $authkey = '')
{
    if (empty($authkey) && isset($_COOKIE['authkey']))
        $authkey = $_COOKIE['authkey'];
    elseif (empty($authkey) && isset($_SESSION['authkey']))
        $authkey = $_SESSION['authkey'];
    elseif (empty($authkey)) 
        $authkey = md5(NULL);
    
    $form = array();
    switch ($mode)
    {
        case "authkey":
            $form[] = "<form name='auth-key' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/authkey.api">';
            $form[] = "\t<table class='auth-key' id='auth-key' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='username'>Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='username' id='username' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='password'>Password:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='password' name='password' id='password' size='41' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='authkey' name='mode'>";
            $form[] = "\t\t\t\t<input type='submit' value='Get URL Auth-key' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
        case "newdomain":
            $form[] = "<form name='new-domain' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/' . $authkey . '/domains.api">';
            $form[] = "\t<table class='new-domain' id='auth-domain' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='name'>Domain Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='name' id='name' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='master'>Domain Master:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='master' id='master' size='41' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='type'>Domain Type:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='type' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='NATIVE'>Native Domain</option>";
            $form[] = "\t\t\t\t\t<option value='MASTER'>Master Domain</option>";
            $form[] = "\t\t\t\t\t<option value='SLAVE'>Slave Domain</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='newdomain' name='newdomain'>";
            $form[] = "\t\t\t\t<input type='submit' value='Create New Domain' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
        case "newsupermaster":
            $form[] = "<form name='new-supermaster' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/' . $authkey . '/supermaster.api">';
            $form[] = "\t<table class='new-supermaster' id='auth-supermaster' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='ip'>IP:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='ip' id='ip' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='nameserver'>Name Server:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='nameserver' id='nameserver' size='41' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='newsupermaster' name='mode'>";
            $form[] = "\t\t\t\t<input type='submit' value='Create New Supermaster' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
            
        case "newrecord":
            $form[] = "<form name='new-record' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/' . $authkey . '/zones.api">';
            $form[] = "\t<table class='new-record' id='auth-record' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='domain'>Domain:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='domain' id='format'/>";
            $result = $GLOBALS['APIDB']->queryF("SELECT md5(concat(`id`, '" . API_URL . "', 'domain')) as `key`, `name`, `master` FROM `domains` ORDER BY `name` ASC, `master` ASC");
            while($row = $GLOBALS['APIDB']->fetchArray($result))
                $form[] = "\t\t\t\t\t<option value='".$row['key']."'>".(isset($row['name'])?$row['name']:$row['master'])."</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='type'>Record Type:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='type' id='type'/>";
            foreach(array('A', 'AAAA', 'AFSDB', 'ALIAS', 'CAA', 'CERT', 'CDNSKEY', 'CDS', 'CNAME', 'DNSKEY', 'DNAME', 'DS', 'HINFO', 'KEY', 'LOC', 'MX', 'NAPTR', 'NS', 'NSEC', 'NSEC3', 'NSEC3PARAM', 'OPENPGPKEY', 'PTR', 'RP', 'RRSIG', 'SOA', 'SPF', 'SSHFP', 'SRV', 'TKEY', 'TSIG', 'TLSA', 'SMIMEA', 'TXT', 'URI') as $type)
                $form[] = "\t\t\t\t\t<option value='".$type."'>". $type." Record</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='name'>Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='name' id='name' size='41' maxlen='255'/>&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='content'>Record:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<textarea name='content' id='content' col='41' row='7'></textarea><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='ttl'>Time-to-live:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='ttl' id='ttl' value='6000' size='8' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='prio'>Pirority:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='content' name='prio' id='prio' value='0' size='8' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='newzone' name='mode'>";
            $form[] = "\t\t\t\t<input type='submit' value='Create New Zone' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
        }
    return implode("\n", $form);
           
}
?>
