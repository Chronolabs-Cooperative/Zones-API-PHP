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

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'apimailer.php';

$start = time();
if ($staters = APICache::read('import-domain-records'))
{
    $staters[] = $start;
    sort($staters, SORT_ASC);
    if (count($starters)>50)
        unset($starters[0]);
    sort($staters, SORT_ASC);
    APICache::write('import-generated-keys', $staters, 3600 * 24 * 7 * 4 * 6);
    $keys = array_key($starters);
    $avg = array();
    foreach($starters as $key => $starting) {
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
    APICache::write('import-domain-records', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}


$nameservices = explode("\n", file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'name-servers.diz'));

$result = $GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE 1 = 1 ORDER BY RAND()");
while($domain = $GLOBALS['APIDB']->fetchArray($result)) {
    foreach($nameservices as $record)
    {
        list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = 'NS' AND `name` LIKE '" . $domain['name'] . "' AND `content` LIKE '" . $record . "'"));
        if ($count == 0) {
            @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', 'NS', '" . $domain['name'] . "', '" . $record . "',  '" . 3600 . "',  '" . 0 . "'");
        }
    }
    foreach(dns_get_record($domain['name'], DNS_NS) as $ns)
    {
        if (!in_array($ns['target'], $nameservices)) {
            foreach(dns_get_record($domain['name'], DNS_A) as $record)
            {
                list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = '" . $record['type'] . "' AND `name` LIKE '" . $record['host'] . "' AND `content` LIKE '" . $record['target'] . "'"));
                if ($count == 0) {
                    @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', '" . $record['type'] . "', '" . $record['host'] . "', '" . $record['target'] . "',  '" . $record['ttl'] . "',  '" . $record['pri'] . "'");
                }
            }
            foreach(dns_get_record($domain['name'], DNS_AAAA) as $record)
            {
                list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = '" . $record['type'] . "' AND `name` LIKE '" . $record['host'] . "' AND `content` LIKE '" . $record['target'] . "'"));
                if ($count == 0) {
                    @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', '" . $record['type'] . "', '" . $record['host'] . "', '" . $record['target'] . "',  '" . $record['ttl'] . "',  '" . $record['pri'] . "'");
                }
            }
            foreach(dns_get_record($domain['name'], DNS_MX) as $record)
            {
                list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = '" . $record['type'] . "' AND `name` LIKE '" . $record['host'] . "' AND `content` LIKE '" . $record['target'] . "'"));
                if ($count == 0) {
                    @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', '" . $record['type'] . "', '" . $record['host'] . "', '" . $record['target'] . "',  '" . $record['ttl'] . "',  '" . $record['pri'] . "'");
                }
            }
            foreach(dns_get_record($domain['name'], DNS_TXT) as $record)
            {
                list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = '" . $record['type'] . "' AND `name` LIKE '" . $record['host'] . "' AND `content` LIKE '" . $record['target'] . "'"));
                if ($count == 0) {
                    @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', '" . $record['type'] . "', '" . $record['host'] . "', '" . $record['target'] . "',  '" . $record['ttl'] . "',  '" . $record['pri'] . "'");
                }
            }
            foreach(dns_get_record($domain['name'], DNS_CNAME) as $record)
            {
                list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = '" . $record['type'] . "' AND `name` LIKE '" . $record['host'] . "' AND `content` LIKE '" . $record['target'] . "'"));
                if ($count == 0) {
                    @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', '" . $record['type'] . "', '" . $record['host'] . "', '" . $record['target'] . "',  '" . $record['ttl'] . "',  '" . $record['pri'] . "'");
                }
            }
            foreach(dns_get_record($domain['name'], DNS_SOA) as $record)
            {
                list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = '" . $record['type'] . "' AND `name` LIKE '" . $record['host'] . "'"));
                if ($count == 0) {
                    $parts = explode(" ", $record['target']);
                    unset($parts[0]);
                    unset($parts[1]);
                    @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', '" . $record['type'] . "', '" . $record['host'] . "', '" . sprintf(file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'soa-record.diz'), implode(" ", $parts)) . "',  '" . $record['ttl'] . "',  '" . $record['pri'] . "'");
                }
            }
            foreach(dns_get_record($domain['name'], DNS_SRV) as $record)
            {
                list($count) =  $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT count(*) FROM `" . $GLOBALS['APIDB']->prefix('records') . "` WHERE `domain_id` = '" . $domain['id'] . "' AND `type` = '" . $record['type'] . "' AND `name` LIKE '" . $record['host'] . "' AND `content` LIKE '" . $record['target'] . "'"));
                if ($count == 0) {
                    @$GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('records') . "`(`domain_id`, `type`, `name`, `content`, `ttl`, `prio`) VALUES('" . $domain['id'] . "', '" . $record['type'] . "', '" . $record['host'] . "', '" . $record['target'] . "',  '" . $record['ttl'] . "',  '" . $record['pri'] . "'");
                }
            }
            continue;
        }
    }
}
