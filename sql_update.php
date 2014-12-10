<?php
exit("...");
?>
ALTER TABLE `groups`
ADD `group_permission` mediumtext COLLATE 'latin1_swedish_ci' NULL,
COMMENT='';

ALTER TABLE `users`
ADD `group_id` int(11) unsigned NOT NULL,
COMMENT='';

CREATE TABLE `activity_logs` (
  `row_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `parameters` mediumtext NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`row_id`),
  KEY `action` (`module`),
  KEY `create_date` (`create_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

###2014-12-07

CREATE TABLE `file_marks` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT, `fk_empresa` smallint(4) unsigned NOT NULL, `label` varchar(250) DEFAULT NULL, `global` tinyint(1) NOT NULL DEFAULT '0', `create_date` datetime DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `files`
ADD `file_mark_id` mediumint unsigned NULL,
COMMENT='';
