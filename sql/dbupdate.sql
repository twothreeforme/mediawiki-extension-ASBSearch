
-- ASB_Data updates:
-- usage from within sql folder: mysql -u -p db < dbupdate.sql

-- ************************
-- DROP TABLE IF EXISTS `search_counters`;
-- CREATE TABLE `search_counters` (
--   `page` varchar(25) NOT NULL,
--   `hitcount` int NOT NULL,
--   PRIMARY KEY (`page`)
-- ) DEFAULT CHARSET=utf8mb4 CHECKSUM=1;

-- INSERT INTO `search_counters` (`page`,`hitcount`) VALUES
-- ("droprate", 0),
-- ("fishing", 0),
-- ("recipes", 0),
-- ("equipment", 0);

-- ************************


\! echo zone_weather
DROP TABLE IF EXISTS `zone_weather`;
source zone_weather.sql;
\! echo zone_weather done

\! echo zone_settings
DROP TABLE IF EXISTS `zone_settings`;
source zone_settings.sql;
\! echo zone_settings done

\! echo mob_spawn_points
DROP TABLE IF EXISTS `mob_spawn_points`;
source mob_spawn_points.sql;
\! echo mob_spawn_points done

\! echo synth_recipes
DROP TABLE IF EXISTS `synth_recipes`;
source synth_recipes.sql;
\! echo synth_recipes done

\! echo synth_recipes_era
source synth_recipes_era.sql;
\! echo synth_recipes_era done


\! echo mob_droplist
DROP TABLE IF EXISTS `mob_droplist`;
source _mob_droplist.sql;
\! echo mob_droplist done


\! echo mob_groups
DROP TABLE IF EXISTS `mob_groups`;
source mob_groups.sql;
\! echo _mob_groups
source _mob_groups.sql;
\! echo mob_groups done


\! echo mob_pools
DROP TABLE IF EXISTS `mob_pools`;
source mob_pools.sql;
\! echo mob_pools done


\! echo hxi_bcnm_crate_list
DROP TABLE IF EXISTS `hxi_bcnm_crate_list`;
source hxi_bcnm_crate_list.sql;
\! echo hxi_bcnm_crate_list done

\! echo bcnm_info
DROP TABLE IF EXISTS `bcnm_info`;
source bcnm_info.sql;
\! echo bcnm_info done

\! echo item_basic
DROP TABLE IF EXISTS `item_basic`;
source item_basic.sql;
\! echo item_basic done

\! echo item_weapon
DROP TABLE IF EXISTS `item_weapon`;
source item_weapon.sql;
\! echo item_weapon done

\! echo item_equipment
DROP TABLE IF EXISTS `item_equipment`;
source item_equipment.sql;
\! echo item_equipment done

\! echo item_mods
DROP TABLE IF EXISTS `item_mods`;
source item_mods.sql;
\! echo item_mods done

\! echo mob_family_system
DROP TABLE IF EXISTS `mob_family_system`;
source mob_family_system.sql;
\! echo mob_family_system done

\! echo traits
DROP TABLE IF EXISTS `traits`;
source traits.sql;
\! echo traits done

\! echo skill_ranks
DROP TABLE IF EXISTS `skill_ranks`;
source skill_ranks.sql;
\! echo skill_ranks done

\! echo fishing_zone
DROP TABLE IF EXISTS `fishing_zone`;
source fishing_zone.sql;
\! echo fishing_zone done

\! echo fishing_area
DROP TABLE IF EXISTS `fishing_area`;
source fishing_area.sql;
\! echo fishing_area done

\! echo fishing_bait
DROP TABLE IF EXISTS `fishing_bait`;
source fishing_bait.sql;
\! echo fishing_bait done

\! echo fishing_fish
DROP TABLE IF EXISTS `fishing_fish`;
source fishing_fish.sql;
\! echo fishing_fish done

\! echo fishing_catch
DROP TABLE IF EXISTS `fishing_catch`;
source fishing_catch.sql;
\! echo fishing_catch done

\! echo fishing_group
DROP TABLE IF EXISTS `fishing_group`;
source fishing_group.sql;
\! echo fishing_group done

\! echo fishing_bait_affinity
DROP TABLE IF EXISTS `fishing_bait_affinity`;
source fishing_bait_affinity.sql;
\! echo fishing_bait_affinity done

\! echo hxi_changes
source hxi_changes.sql;
\! echo hxi_changes done



