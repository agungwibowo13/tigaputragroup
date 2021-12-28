CREATE TABLE IF NOT EXISTS `tbl_user` (
	`user_id` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255),
	`password` VARCHAR(255),
	`email` VARCHAR(255),
	`firstname` VARCHAR(255),
	`lastname` VARCHAR(255),
	`status` TINYINT(1) DEFAULT 1 COMMENT '0=Inactive; 1=Active',
	`secret_key` VARCHAR(255),
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user` (`username`, `password`, `email`, `firstname`, `lastname`, `status`, `secret_key`, `created_on`, `created_by`, `updated_on`, `updated_by`, `is_deleted`) VALUES
('admin', 'HcEBwxcoG7CcLtkZMMSFwzAeylxqnXm5c.H9i2.zLuM-', 'admin@example.com', 'Super Admin', '', 1, '', '0000-00-00 00:00:00', 0, '2017-09-24 02:30:17', 1, 0);

CREATE TABLE IF NOT EXISTS `tbl_setting` (
	`setting_id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`identifier` VARCHAR(255),
	`value` TEXT,
	`remarks` TEXT,
	`input_type` VARCHAR(255),
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_setting` (`name`, `identifier`, `value`, `remarks`, `input_type`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
('Copyright Text', 'copyright-text', '', '', 'input', 'NOW()', '1', 'NOW()', 1);

CREATE TABLE IF NOT EXISTS `tbl_vendor` (
	`vendor_id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100),
	`phone` VARCHAR(20),
	`address` VARCHAR(255),
	`email` VARCHAR(255),
	`password` VARCHAR(255),
	`already_paid` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	`is_verified` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	`status` ENUM('Enable','Disable') DEFAULT 'Enable',
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbl_merchant` (
	`merchant_id` INT(11) NOT NULL AUTO_INCREMENT,
	`vendor_id` INT(11),
	`name` VARCHAR(100),
	`phone` VARCHAR(20),
	`whatsapp` VARCHAR(20),
	`address` VARCHAR(255),
	`email` VARCHAR(255),
	`url_key` VARCHAR(255),
	`image_url` VARCHAR(255),
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`merchant_id`),
	FOREIGN KEY(`vendor_id`) REFERENCES `tbl_vendor` (`vendor_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbl_category` (
	`category_id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100),
	`image_url` VARCHAR(255),
	`status` ENUM('Enable','Disable') DEFAULT 'Enable',
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbl_product` (
	`product_id` INT(11) NOT NULL AUTO_INCREMENT,
	`category` VARCHAR(255),
	`name` VARCHAR(255),
	`uom` VARCHAR(255),
	`hpp` VARCHAR(255),
	`price` TEXT,
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_category` ADD `url_key` VARCHAR(100) DEFAULT NULL AFTER `name`;

CREATE TABLE IF NOT EXISTS `tbl_vendor_api_login_history` (
	`api_login_history_id` INT(11) NOT NULL AUTO_INCREMENT,
	`vendor_id` INT(11),
	`ip_address` VARCHAR(255),
	`clock_in` DATETIME DEFAULT NULL,
	`login_expiry` DATETIME DEFAULT NULL,
	`clock_out` DATETIME DEFAULT NULL,
	PRIMARY KEY (`api_login_history_id`),
	FOREIGN KEY(`vendor_id`) REFERENCES `tbl_vendor` (`vendor_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_vendor` ADD `username` VARCHAR(100) DEFAULT NULL AFTER `vendor_id`;
ALTER TABLE `tbl_vendor` ADD `angkatan_bum_class` VARCHAR(100) DEFAULT NULL AFTER `password`;
ALTER TABLE `tbl_vendor` ADD `facebook_name` VARCHAR(255) DEFAULT NULL AFTER `angkatan_bum_class`;
ALTER TABLE `tbl_vendor` ADD `whatsapp_1` VARCHAR(100) DEFAULT NULL AFTER `phone`;
ALTER TABLE `tbl_vendor` ADD `whatsapp_2` VARCHAR(100) DEFAULT NULL AFTER `whatsapp_1`;
ALTER TABLE `tbl_vendor` ADD `id_card_url` VARCHAR(255) DEFAULT NULL AFTER `status`;

CREATE TABLE IF NOT EXISTS `tbl_page` (
	`page_id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255),
	`content` LONGTEXT,
	`image_url` VARCHAR(255),
	`url_key` VARCHAR(255),
	`status` TINYINT(1) DEFAULT 1 COMMENT '0=Inactive; 1=Active',
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_vendor` ADD `printed_id` VARCHAR(100) DEFAULT NULL AFTER `vendor_id`;
ALTER TABLE `tbl_vendor` ADD `printed_id_index` INT(11) DEFAULT 0 AFTER `printed_id`;
ALTER TABLE `tbl_vendor` ADD `city` VARCHAR(100) DEFAULT NULL AFTER `address`;
ALTER TABLE `tbl_merchant` ADD `city` VARCHAR(100) DEFAULT NULL AFTER `address`;

CREATE TABLE IF NOT EXISTS `tbl_product_image` (
	`product_image_id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11),
	`image_url` VARCHAR(255),
	PRIMARY KEY (`product_image_id`),
	FOREIGN KEY(`product_id`) REFERENCES `tbl_product` (`product_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbl_product_master` (
	`product_master_id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`hpp` DECIMAL(16,2),
	`price` DECIMAL(16,2),
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`product_master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_product_master` ADD `remarks` TEXT DEFAULT NULL AFTER `price`;

CREATE TABLE IF NOT EXISTS `tbl_invoice` (
	`invoice_id` INT(11) NOT NULL AUTO_INCREMENT,
	`invoice_number` INT(11),
	`invoice_date` DATETIME DEFAULT NULL,
	`total` DECIMAL(16,2),
	`created_on` DATETIME DEFAULT NULL,
	`created_by` INT(11) DEFAULT NULL,
	`updated_on` DATETIME DEFAULT NULL,
	`updated_by` INT(11) DEFAULT NULL,
	`is_deleted` TINYINT(1) DEFAULT 0 COMMENT '0=No; 1=Yes',
	PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbl_invoice_detail` (
	`invoice_detail_id` INT(11) NOT NULL AUTO_INCREMENT,
	`invoice_id` INT(11),
	`product_master_id` INT(11),
	`original_price` DECIMAL(16,2),
	`price` DECIMAL(16,2),
	`qty` DECIMAL(16,2),
	`profit` DECIMAL(16,2),
	PRIMARY KEY (`invoice_detail_id`),
	FOREIGN KEY(`invoice_id`) REFERENCES `tbl_invoice` (`invoice_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
	FOREIGN KEY(`product_master_id`) REFERENCES `tbl_product_master` (`product_master_id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_invoice` ADD `profit` DECIMAL(16,2) DEFAULT 0 AFTER `total`;

ALTER TABLE `tbl_product_master` ADD `rounded_price` INT(1) DEFAULT 0 AFTER `price`;

ALTER TABLE `tbl_product_master` ADD `rating` INT(11) DEFAULT 0 AFTER `remarks`;