ALTER TABLE `{prefix}content` 
ADD `valid_from` DATETIME NULL AFTER `link_to_language`, 
ADD `valid_until` DATETIME NULL AFTER `valid_from`;