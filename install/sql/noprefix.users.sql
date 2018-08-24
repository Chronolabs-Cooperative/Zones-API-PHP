DROP TABLE `users`;

CREATE TABLE `users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `uname` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `api_avatar` varchar(30) NOT NULL DEFAULT 'blank.gif',
  `api_regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `api_from` varchar(100) NOT NULL DEFAULT '',
  `api_sig` tinytext,
  `actkey` varchar(8) NOT NULL DEFAULT '',
  `pass` varchar(255) NOT NULL DEFAULT '',
  `hits` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attachsig` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timezone` varchar(150) NOT NULL DEFAULT '',
  `last_online` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `api_mailok` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`),
  KEY `uname` (`uname`),
  KEY `email` (`email`),
  KEY `uiduname` (`uid`,`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

