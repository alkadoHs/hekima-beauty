ALTER TABLE `hekima_beauty`.`orders` 
ADD COLUMN `payment_method` VARCHAR(45) NULL DEFAULT 'CASH' AFTER `amountPaid`;


ALTER TABLE `hekima_beauty`.`expense` 
ADD COLUMN `account` VARCHAR(45) NULL DEFAULT 'CASH' AFTER `amount`;


ALTER TABLE `hekima_beauty`.`order_item` 
DROP FOREIGN KEY `orderitem_product_fkey`;
ALTER TABLE `hekima_beauty`.`order_item` 
ADD CONSTRAINT `orderitem_product_fkey`
  FOREIGN KEY (`product_id`)
  REFERENCES `hekima_beauty`.`product` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;



ALTER TABLE `hekima_beauty`.`cart_item` 
DROP FOREIGN KEY `cartitem_product_fkey`;
ALTER TABLE `hekima_beauty`.`cart_item` 
ADD CONSTRAINT `cartitem_product_fkey`
  FOREIGN KEY (`product_id`)
  REFERENCES `hekima_beauty`.`product` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;