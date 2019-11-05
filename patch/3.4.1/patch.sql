CREATE TABLE `time_block` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `shift_id` INT NOT NULL,
  PRIMARY KEY (`id`));


ALTER TABLE `time_block` 
ADD INDEX `index2` (`shift_id` ASC);

ALTER TABLE `time_block` 
ADD CONSTRAINT `fk_time_block_1`
  FOREIGN KEY (`shift_id`)
  REFERENCES `lvm_15oct2019`.`shift` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

insert into sf_guard_permission (name, description) values ('edit_time_block','Crear y editar bloques horarios');

