DROP TABLE IF EXISTS `raw_data`;
CREATE TABLE `raw_data` (
  `id` int(10) UNSIGNED  NOT NULL,
  `name` varchar(64) NOT NULL,
  `attribute_1` int(10) UNSIGNED NOT NULL,
  `attribute_2` int(10) UNSIGNED NOT NULL,
  `attribute_3` int(10) UNSIGNED NOT NULL,
  `attribute_4` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `raw_data`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `raw_data` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `attribute_1_cache`;
CREATE TABLE `attribute_1_cache` (
  `attribute_2_filter` int(10) UNSIGNED NOT NULL,
  `attribute_3_filter` int(10) UNSIGNED NOT NULL,
  `attribute_4_filter` int(10) UNSIGNED NOT NULL,
  `attribute_1_value` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `attribute_1_cache`
  ADD KEY `attribute_1_filter_a_idx` (`attribute_2_filter`) USING BTREE,
  ADD KEY `attribute_1_filter_b_idx` (`attribute_3_filter`) USING BTREE,
  ADD KEY `attribute_1_filter_c_idx` (`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_1_filter_ab_idx` (`attribute_2_filter`,`attribute_3_filter`) USING BTREE,
  ADD KEY `attribute_1_filter_bc_idx` (`attribute_3_filter`,`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_1_filter_ac_idx` (`attribute_2_filter`,`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_1_filter_abc_idx` (`attribute_2_filter`,`attribute_3_filter`,`attribute_4_filter`) USING BTREE;

DROP TABLE IF EXISTS `attribute_2_cache`;
CREATE TABLE `attribute_2_cache` (
  `attribute_1_filter` int(10) UNSIGNED NOT NULL,
  `attribute_3_filter` int(10) UNSIGNED NOT NULL,
  `attribute_4_filter` int(10) UNSIGNED NOT NULL,
  `attribute_2_value` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `attribute_2_cache`
  ADD KEY `attribute_2_filter_a_idx` (`attribute_1_filter`) USING BTREE,
  ADD KEY `attribute_2_filter_b_idx` (`attribute_3_filter`) USING BTREE,
  ADD KEY `attribute_2_filter_c_idx` (`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_2_filter_ab_idx` (`attribute_1_filter`,`attribute_3_filter`) USING BTREE,
  ADD KEY `attribute_2_filter_bc_idx` (`attribute_3_filter`,`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_2_filter_ac_idx` (`attribute_1_filter`,`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_2_filter_abc_idx` (`attribute_1_filter`,`attribute_3_filter`,`attribute_4_filter`) USING BTREE;

DROP TABLE IF EXISTS `attribute_3_cache`;
CREATE TABLE `attribute_3_cache` (
  `attribute_1_filter` int(10) UNSIGNED NOT NULL,
  `attribute_2_filter` int(10) UNSIGNED NOT NULL,
  `attribute_4_filter` int(10) UNSIGNED NOT NULL,
  `attribute_3_value` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `attribute_3_cache`
  ADD KEY `attribute_3_filter_a_idx` (`attribute_1_filter`) USING BTREE,
  ADD KEY `attribute_3_filter_b_idx` (`attribute_2_filter`) USING BTREE,
  ADD KEY `attribute_3_filter_c_idx` (`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_3_filter_ab_idx` (`attribute_1_filter`,`attribute_2_filter`) USING BTREE,
  ADD KEY `attribute_3_filter_bc_idx` (`attribute_2_filter`,`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_3_filter_ac_idx` (`attribute_1_filter`,`attribute_4_filter`) USING BTREE,
  ADD KEY `attribute_3_filter_abc_idx` (`attribute_1_filter`,`attribute_2_filter`,`attribute_4_filter`) USING BTREE;

DROP TABLE IF EXISTS `attribute_4_cache`;
CREATE TABLE `attribute_4_cache` (
  `attribute_1_filter` int(10) UNSIGNED NOT NULL,
  `attribute_2_filter` int(10) UNSIGNED NOT NULL,
  `attribute_3_filter` int(10) UNSIGNED NOT NULL,
  `attribute_4_value` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `attribute_4_cache`
  ADD KEY `attribute_4_filter_a_idx` (`attribute_1_filter`) USING BTREE,
  ADD KEY `attribute_4_filter_b_idx` (`attribute_2_filter`) USING BTREE,
  ADD KEY `attribute_4_filter_c_idx` (`attribute_3_filter`) USING BTREE,
  ADD KEY `attribute_4_filter_ab_idx` (`attribute_1_filter`,`attribute_2_filter`) USING BTREE,
  ADD KEY `attribute_4_filter_bc_idx` (`attribute_2_filter`,`attribute_3_filter`) USING BTREE,
  ADD KEY `attribute_4_filter_ac_idx` (`attribute_1_filter`,`attribute_3_filter`) USING BTREE,
  ADD KEY `attribute_4_filter_abc_idx` (`attribute_1_filter`,`attribute_2_filter`,`attribute_3_filter`) USING BTREE;
