CREATE TABLE IF NOT EXISTS `wf3_gamer`.`platforms` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `wf3_gamer`.`games` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `url_img` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `published_at` DATETIME NULL,
  `game_time` INT UNSIGNED NULL,
  `is_available` TINYINT(1) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `platform_id` INT UNSIGNED NOT NULL,
  `owner_user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`, `platform_id`, `owner_user_id`),
  INDEX `fk_games_platforms_idx` (`platform_id` ASC),
  INDEX `fk_games_users1_idx` (`owner_user_id` ASC))
ENGINE = InnoDB;

