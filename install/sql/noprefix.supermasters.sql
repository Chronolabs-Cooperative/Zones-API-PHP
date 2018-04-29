
# Table structure for table `supermasters`
#

CREATE TABLE `supermasters` (
	`ip` VARCHAR(128) NOT NULL,
	`nameserver` VARCHAR(255) NOT NULL,
	`account` VARCHAR(40) DEFAULT NULL,
	key `search` (`ip`(16),`nameserver`(32),`account`(10))
);
