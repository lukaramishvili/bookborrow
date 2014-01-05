
CREATE  TABLE `borrows` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` TEXT NULL ,
  `friend_id` TEXT NULL ,
  `friend_name` TEXT NULL ,
  `book_id` TEXT NULL ,
  `book_name` TEXT NULL ,
  `from` INT NULL ,
  `to` INT NULL ,
  PRIMARY KEY (`id`) );
