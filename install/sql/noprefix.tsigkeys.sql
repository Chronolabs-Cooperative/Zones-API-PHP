DROP TABLE `tsigkeys`;

CREATE TABLE `tsigkeys` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) DEFAULT NULL,
 `algorithm` varchar(50) DEFAULT NULL,
 `secret` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `namealgoindex` (`name`,`algorithm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

