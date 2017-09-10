CREATE TABLE `availability_calendar` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `date` DATE NOT NULL ,
  `start_time` TIME NULL ,
  `end_time` TIME NULL ,
  `is_available` TINYINT NULL ,
  PRIMARY KEY (`id`)  );

ALTER TABLE `availability_calendar`
ADD INDEX `fk_availability_calendar_user_id_idx` (`user_id` ASC)  ;
ALTER TABLE `availability_calendar`
ADD CONSTRAINT `fk_availability_calendar_user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;



ALTER TABLE `availability`
DROP FOREIGN KEY `fk_availability_user_id`;
ALTER TABLE `availability`
ADD CONSTRAINT `fk_availability_user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `ci_sessions`
CHANGE COLUMN `user_data` `user_data` LONGTEXT NOT NULL  ;

   ALTER TABLE `conversation`
DROP FOREIGN KEY `fk_conversation_counselor_id`,
DROP FOREIGN KEY `fk_conversation_customer_id`;
ALTER TABLE `conversation`
ADD CONSTRAINT `fk_conversation_counselor_id`
  FOREIGN KEY (`counselor_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_conversation_customer_id`
  FOREIGN KEY (`customer_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `conversation`
ADD INDEX `idx_conversation_deleted` (`deleted` ASC)  ;

DROP TABLE `counselor_schedule`;

ALTER TABLE `diary`
ADD INDEX `idx_diary_deleted` (`deleted` ASC)  ;

ALTER TABLE `diary`
DROP FOREIGN KEY `fk_diary_user_id`;
ALTER TABLE `diary`
ADD CONSTRAINT `fk_diary_user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

  ALTER TABLE `event`
ADD INDEX `idx_event_deleted` (`deleted` ASC)  ;

ALTER TABLE `event`
DROP FOREIGN KEY `fk_event_user_id`;
ALTER TABLE `event`
ADD CONSTRAINT `fk_event_user_id`
  FOREIGN KEY (`customer_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `event`
ADD INDEX `fk_event_counselor_id_idx` (`counselor_id` ASC)  ;
ALTER TABLE `event`
ADD CONSTRAINT `fk_event_counselor_id`
  FOREIGN KEY (`counselor_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

  ALTER TABLE `message`
ADD INDEX `idx_message_deleted` (`deleted` ASC)  ;

ALTER TABLE `message`
DROP FOREIGN KEY `fk_message_conversation_id`,
DROP FOREIGN KEY `fk_message_recipient_id`,
DROP FOREIGN KEY `fk_message_sender_id`;
ALTER TABLE `message`
ADD CONSTRAINT `fk_message_conversation_id`
  FOREIGN KEY (`conversation_id`)
  REFERENCES `conversation` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_message_recipient_id`
  FOREIGN KEY (`recipient_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_message_sender_id`
  FOREIGN KEY (`sender_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

  ALTER TABLE `note`
ADD INDEX `idx_note_deleted` (`deleted` ASC)  ;

ALTER TABLE `note`
DROP FOREIGN KEY `fk_note_counselor_id`,
DROP FOREIGN KEY `fk_note_customer_id`;
ALTER TABLE `note`
ADD CONSTRAINT `fk_note_counselor_id`
  FOREIGN KEY (`counselor_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `fk_note_customer_id`
  FOREIGN KEY (`customer_id`)
  REFERENCES `user` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


ALTER TABLE `availability_calendar` 
ADD COLUMN `is_all_day` TINYINT NULL  AFTER `is_available`;




