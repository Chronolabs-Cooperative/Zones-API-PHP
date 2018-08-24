DROP TABLE `comments`;

CREATE TABLE `comments` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `domain_id` int(11) NOT NULL,
 `name` varchar(255) NOT NULL,
 `type` varchar(10) NOT NULL,
 `modified_at` int(11) NOT NULL,
 `account` varchar(40) CHARACTER SET utf8 NOT NULL,
 `comment` text CHARACTER SET utf8 NOT NULL,
 PRIMARY KEY (`id`),
 KEY `comments_domain_id_idx` (`domain_id`),
 KEY `comments_name_type_idx` (`name`,`type`),
 KEY `comments_order_idx` (`domain_id`,`modified_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

