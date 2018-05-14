CREATE TABLE `supermasters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(128) NOT NULL,
  `nameserver` varchar(255) NOT NULL,
  `account` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `search` (`ip`,`nameserver`,`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

