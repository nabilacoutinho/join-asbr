CREATE TABLE `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `unities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` int(10) unsigned NOT NULL,
  `has_custom_score` tinyint(1) NOT NULL DEFAULT '0',
  `custom_score` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unities_region_id_foreign` (`region_id`),
  CONSTRAINT `unities_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`)
);

CREATE TABLE `prospects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` datetime NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` int(10) unsigned DEFAULT NULL,
  `unity_id` int(10) unsigned DEFAULT NULL,
  `total_score` int(11) NOT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prospects_unity_id_foreign` (`unity_id`),
  KEY `prospects_region_id_foreign` (`region_id`),
  CONSTRAINT `prospects_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`),
  CONSTRAINT `prospects_unity_id_foreign` FOREIGN KEY (`unity_id`) REFERENCES `unities` (`id`)
);


INSERT INTO `asbr_solution`.`regions` (`name`, `score`, `created_at`, `updated_at`) VALUES ('Sul', '-2', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`regions` (`name`, `score`, `created_at`, `updated_at`) VALUES ('Sudeste', '-1', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`regions` (`name`, `score`, `created_at`, `updated_at`) VALUES ('Centro-Oeste', '-3', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`regions` (`name`, `score`, `created_at`, `updated_at`) VALUES ('Nordeste', '-4', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`regions` (`name`, `score`, `created_at`, `updated_at`) VALUES ('Norte', '-5', '2017-02-23 07:55:00', '2017-02-23 07:55:00');

INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `created_at`, `updated_at`) VALUES ('Porto Alegre', '1', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `created_at`, `updated_at`) VALUES ('Curitiba', '1', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `custom_score`, `created_at`, `updated_at`) VALUES ('São Paulo', '2', '1', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `created_at`, `updated_at`) VALUES ('Rio de Janeiro', '2', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `created_at`, `updated_at`) VALUES ('Belo Horizonte', '2', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `created_at`, `updated_at`) VALUES ('Brasília', '3', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `created_at`, `updated_at`) VALUES ('Salvador', '4', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');
INSERT INTO `asbr_solution`.`unities` (`name`, `region_id`, `has_custom_score`, `created_at`, `updated_at`) VALUES ('Recife', '4', '0', '2017-02-23 07:55:00', '2017-02-23 07:55:00');

