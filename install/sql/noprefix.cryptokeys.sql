DROP TABLE `cryptokeys`;

CREATE TABLE `cryptokeys` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `domain_id` int(11) NOT NULL,
 `flags` int(11) NOT NULL,
 `active` tinyint(1) DEFAULT NULL,
 `content` text,
 PRIMARY KEY (`id`),
 KEY `domainidindex` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

