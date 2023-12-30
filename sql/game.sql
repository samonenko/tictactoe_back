-- Adminer 4.8.1 MySQL 8.1.0 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` int NOT NULL AUTO_INCREMENT,
  `json` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `game` (`id`, `json`) VALUES
(10,	'{\"board\": {\"data\": [[\"_\", \"_\", \"_\"], [\"_\", \"_\", \"_\"], [\"_\", \"_\", \"_\"]], \"size\": \"3\"}, \"score\": {\"O\": 0, \"X\": 0}, \"current_player\": \"X\"}');

-- 2023-12-30 09:15:53
