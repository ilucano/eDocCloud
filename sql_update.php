ALTER TABLE `groups`
ADD `group_permission` mediumtext COLLATE 'latin1_swedish_ci' NULL,
COMMENT='';

ALTER TABLE `users`
ADD `group_id` int(11) unsigned NOT NULL,
COMMENT='';