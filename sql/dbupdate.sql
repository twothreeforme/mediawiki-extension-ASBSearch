-- usage from within sql folder: mysql -u -p db < dbupdate.sql

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
\! echo synth_recipes_era
source synth_recipes_era.sql;
\! echo synth_recipes done


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


\! echo item_basic
DROP TABLE IF EXISTS `item_basic`;
source item_basic.sql;
\! echo item_basic done


\! echo hxi_bcnm_crate_list
DROP TABLE IF EXISTS `hxi_bcnm_crate_list`;
source hxi_bcnm_crate_list.sql;
\! echo hxi_bcnm_crate_list done


\! echo bcnm_info
DROP TABLE IF EXISTS `bcnm_info`;
source bcnm_info.sql;
\! echo bcnm_info done

\! echo item_equipment
DROP TABLE IF EXISTS `item_equipment`;
source item_equipment.sql;
\! echo item_equipment done

\! echo item_mods
DROP TABLE IF EXISTS `item_mods`;
source item_mods.sql;
\! echo item_mods done

\! echo hxi_changes
source hxi_changes.sql;
\! echo hxi_changes done



