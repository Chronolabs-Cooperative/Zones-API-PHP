<?php
/**
 * NTP.SNAILS.EMAIL - Pinging Cron
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

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'cache'. DIRECTORY_SEPARATOR . 'apicache.php';

$start = time();
if ($staters = APICache::read('map-dns-services'))
{
    $staters[] = $start;
    sort($staters, SORT_ASC);
    if (count($starters)>50)
        unset($starters[0]);
    sort($staters, SORT_ASC);
    APICache::write('map-dns-services', $staters, 3600 * 24 * 7 * 4 * 6);
    $keys = array_key(array_reverse($starters));
    $avg = array();
    foreach(array_reverse($starters) as $key => $starting) {
        if (isset($keys[$key - 1])) {
            $avg[] = abs($starting - $starters[$keys[$key - 1]]);
        }
    }
    if (count($avg) > 0 ) {
        foreach($avg as $average)
            $seconds += $average;
        $seconds = $seconds / count($avg);
    } else 
        $seconds = 1800;
} else {
    APICache::write('map-dns-services', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

if (!$ipseg = json_decode(file_get_contents(__DIR__ . DS . 'ip-segments.json'), true))
    $ipseg = array("a" => 1, "b" => 0, "c" => 0, "d" => 0);

foreach($ipseg as $key => $value)
    ${$key} = $value;

$d++;
if ($d == 256) {
    $d = 0;
    $c++;
}
if ($c == 256) {
    $c = 0;
    $b++;
}
if ($b == 256) {
    $b = 0;
    $a++;
}
if ($a == 256) {
    $a = 1;
}

foreach($ipseg as $key => $value)
    $ipseg[$key] = ${$key};

file_put_contents(__DIR__ . DS . 'ip-segments.json', json_encode($ipseg));
exec("host example.com " . ($ip = $ipseg['a'] . '.' . $ipseg['b'] . '.' . $ipseg['c'] . '.' . $ipseg['d']), $return, $output);

$active = true;
foreach($output as $line)
    if (strpos($line, "no servers could be reached"));
        $active = false;
        
if ($active == true) {
       $hostname = gethostbyaddr($ip);
       if ($ip != $hostname) {
           $ipv4 = dns_get_record($hostname, DNS_A);
           $ipv6 = dns_get_record($hostname, DNS_AAAA);
       }
       if (!empty($ipv4) && $ipv4 != $hostname) {
           list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . ('supermaster') . "` WHERE `ip` = '$ipv4' AND `nameserver` = '$hostname'"));
           if ($count==0)
               if ($GLOBALS['APIDB']->queryF($sql = "INSERT INTO `" . ('supermaster') . "` (`ip`, `nameserver`, `account`) VALUES('$ipv4', '$hostname', 'cron')"))
                   echo("\n\nSQL Success: $sql");
               else 
                   echo("\n\nSQL Failed: $sql");
       } elseif (!empty($ip) && $ip != $hostname) {
           list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . ('supermaster') . "` WHERE `ip` = '$ip' AND `nameserver` = '$hostname'"));
           if ($count==0)
               if ($GLOBALS['APIDB']->queryF($sql = "INSERT INTO `" . ('supermaster') . "` (`ip`, `nameserver`, `account`) VALUES('$ip', '$hostname', 'cron')"))
                   echo("\n\nSQL Success: $sql");
               else
                   echo("\n\nSQL Failed: $sql");
       } elseif (!empty($ip) && $ip == $hostname) {
           list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . ('supermaster') . "` WHERE `ip` = '$ip'"));
           if ($count==0)
               if ($GLOBALS['APIDB']->queryF($sql = "INSERT INTO `" . ('supermaster') . "` (`ip`, `account`) VALUES('$ip', 'cron')"))
                   echo("\n\nSQL Success: $sql");
               else
                   echo("\n\nSQL Failed: $sql");
       }
       if (!empty($ipv6) && $ipv6 != $hostname) {
           list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . ('supermaster') . "` WHERE `ip` = '$ipv6' AND `nameserver` = '$hostname'"));
           if ($count==0)
               if ($GLOBALS['APIDB']->queryF($sql = "INSERT INTO `" . ('supermaster') . "` (`ip`, `nameserver`, `account`) VALUES('$ipv6', '$hostname', 'cron')"))
                   echo("\n\nSQL Success: $sql");
               else
                   echo("\n\nSQL Failed: $sql");
       }
} else 
    die("\n\nNo DNS Found at $ip");
?>
