
# Table structure for table `records`
#

CREATE TABLE `records` (
	`id` INT auto_increment,
	`domain_id` INT DEFAULT NULL,
	`name` VARCHAR(255) DEFAULT NULL,
	`type` VARCHAR(6) DEFAULT NULL,
	`content` VARCHAR(255) DEFAULT NULL,
	`ttl` INT DEFAULT NULL,
	`prio` INT DEFAULT NULL,
	`change_date` INT DEFAULT NULL,
	primary key(id),
	key `search` (`domain_id`,`name`(32),`type`(6),`content`(32)),
	key `numeric` (`ttl`,`prio`,`change_date`)
);

CREATE INDEX `rec_name_index` ON records(`name`);
CREATE INDEX `nametype_index` ON records(`name`,`type`);
CREATE INDEX `domain_id` ON records(`domain_id`);