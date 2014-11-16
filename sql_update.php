ALTER TABLE `groups`
ADD `group_permission` mediumtext COLLATE 'latin1_swedish_ci' NULL,
COMMENT='';

ALTER TABLE `users`
ADD `group_id` int(11) unsigned NOT NULL,
COMMENT='';


CREATE TABLE `activity_logs` (
  `row_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `parameters` mediumtext NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '0',
  `fk_empresa` int(11) NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`row_id`),
  KEY `fk_empresa` (`fk_empresa`),
  KEY `action` (`action`),
  KEY `create_date` (`create_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
