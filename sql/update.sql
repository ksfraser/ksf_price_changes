ALTER TABLE `0_prices` ADD `last_updated` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last time this row was updated' AFTER `price`;
