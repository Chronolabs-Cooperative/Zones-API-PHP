DROP TABLE `domainmetadata`;

CREATE TABLE `domainmetadata` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `domain_id` int(11) NOT NULL,
 `kind` varchar(32) DEFAULT NULL,
 `content` text,
 PRIMARY KEY (`id`),
 KEY `domainmetadata_idx` (`domain_id`,`kind`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

