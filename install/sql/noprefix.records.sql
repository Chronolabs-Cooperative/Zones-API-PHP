CREATE TABLE `records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('A','AAAA','AFSDB','ALIAS','CAA','CERT','CDNSKEY','CDS','CNAME','DNSKEY','DNAME','DS','HINFO','KEY','LOC','MX','NAPTR','NS','NSEC','NSEC3','NSEC3PARAM','OPENPGPKEY','PTR','RP','RRSIG','SOA','SPF','SSHFP','SRV','TKEY','TSIG','TLSA','SMIMEA','TXT','URI') DEFAULT NULL,
  `content` tinytext,
  `ttl` int(11) DEFAULT NULL,
  `prio` int(11) DEFAULT NULL,
  `change_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rec_name_index` (`name`),
  KEY `nametype_index` (`name`,`type`),
  KEY `domain_id` (`domain_id`),
  KEY `search` (`domain_id`,`name`,`type`,`prio`,`ttl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

