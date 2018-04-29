
# Table structure for table `users`
#

CREATE TABLE users (
  uid mediumint(8) unsigned NOT NULL auto_increment,
  typal enum('admin','client','other') NOT NULL default 'client',
  name varchar(60) NOT NULL default '',
  uname varchar(25) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  api_avatar varchar(30) NOT NULL default 'blank.gif',
  api_regdate int(10) unsigned NOT NULL default '0',
  api_from varchar(100) NOT NULL default '',
  api_sig tinytext,
  actkey varchar(8) NOT NULL default '',
  pass varchar(255) NOT NULL default '',
  hits mediumint(8) unsigned NOT NULL default '0',
  attachsig tinyint(1) unsigned NOT NULL default '0',
  timezone varchar(150) NOT NULL default '',
  last_online int(10) unsigned NOT NULL default '0',
  last_login int(10) unsigned NOT NULL default '0',
  api_mailok tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (uid),
  KEY uname (uname),
  KEY email (email),
  KEY uiduname (uid,uname)
) ENGINE=INNODB;
