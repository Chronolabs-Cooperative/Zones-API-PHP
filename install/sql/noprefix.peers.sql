
# Table structure for table `peers`
#

CREATE TABLE peers (
  pid mediumint(32) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default 0,
  company varchar(100) NOT NULL default '',
  license varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  protocol varchar(10) NOT NULL default '',
  host varchar(100) NOT NULL default '',
  path varchar(100) NOT NULL default '',
  version varchar(60) NOT NULL default '',
  type varchar(20) NOT NULL default '',
  PRIMARY KEY  (pid),
  KEY company (company),
  KEY license (license),
  KEY protocolhostpathversion (protocol,host,path,version),
  KEY type (type)
) ENGINE=INNODB;


