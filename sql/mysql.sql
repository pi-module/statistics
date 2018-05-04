CREATE TABLE `{log}` (
  `id`          INT(10) UNSIGNED                       NOT NULL AUTO_INCREMENT,
  `section`     ENUM ('front', 'admin', 'feed', 'api') NOT NULL DEFAULT 'front',
  `module`      VARCHAR(50)                            NOT NULL DEFAULT '',
  `entity`      VARCHAR(50)                            NOT NULL DEFAULT '',
  `entity_id`   INT(10) UNSIGNED                       NOT NULL DEFAULT '0',
  `action`      VARCHAR(20)                            NOT NULL DEFAULT '',
  `time_create` INT(10) UNSIGNED                       NOT NULL DEFAULT '0',
  `uid`         INT(10) UNSIGNED                       NOT NULL DEFAULT '0',
  `ip`          VARCHAR(16)                            NOT NULL DEFAULT '',
  `source`      VARCHAR(10)                            NOT NULL DEFAULT '',
  `session`     VARCHAR(32)                            NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `module` (`module`, `entity`, `entity_id`, `action`, `time_create`)
);

CREATE TABLE `{total_hourly}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date`        DATE                      DEFAULT NULL,
  `hour`        TINYINT(2) UNSIGNED       DEFAULT NULL,
  `total_count` INT(11) UNSIGNED          DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{total_daily}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date`        DATE                      DEFAULT NULL,
  `total_count` INT(11) UNSIGNED          DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{total_monthly}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date`        DATE                      DEFAULT NULL,
  `total_count` INT(11) UNSIGNED          DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{module_daily}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date`        DATE                      DEFAULT NULL,
  `total_count` INT(11) UNSIGNED          DEFAULT NULL,
  `module`      VARCHAR(50)      NOT NULL DEFAULT '',
  `entity`      VARCHAR(50)      NOT NULL DEFAULT '',
  `entity_id`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `action`      VARCHAR(20)      NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);

CREATE TABLE `{module_monthly}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date`        DATE                      DEFAULT NULL,
  `total_count` INT(11) UNSIGNED          DEFAULT NULL,
  `module`      VARCHAR(50)      NOT NULL DEFAULT '',
  `entity`      VARCHAR(50)      NOT NULL DEFAULT '',
  `entity_id`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `action`      VARCHAR(20)      NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);