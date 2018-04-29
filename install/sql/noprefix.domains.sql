
# Table structure for table `domains`
#

CREATE TABLE `domains` (
	`id` INT auto_increment,
	`name` VARCHAR(255) NOT NULL,
	`master` VARCHAR(128) DEFAULT NULL,
	`last_check` INT DEFAULT NULL,
	`type` VARCHAR(6) NOT NULL,
	`notified`_serial INT DEFAULT NULL,
	`account` VARCHAR(40) DEFAULT NULL,
	primary key (id),
	key `search` (`name`(24),`master`(12),`type`(6),`account`(10))
);

CREATE UNIQUE INDEX `name_index` ON domains(`name`);