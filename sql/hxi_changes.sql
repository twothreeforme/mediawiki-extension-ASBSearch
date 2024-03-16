-- ---------------------------------------------------------------------------
-- Horizon XI Wiki Changes
-- ---------------------------------------------------------------------------

-- HENM Default `dropId` value starts at 20000
SET @HENM_lvl = 255;        -- HENM Default `maxLevel` and `minLevel` value to help ParserHelper tag it

SET @HXI = 1;
SET @NUANCE = 2;

-- Start with cleanup
DELETE FROM `mob_groups` WHERE groupid>=20000 AND groupid<30000 ;
DELETE FROM `mob_droplist` WHERE dropId>=20000 AND dropId<30000 ;
DELETE FROM `item_basic` WHERE itemid>=50000 AND itemid<60000;


-- ---------------------------------------------------------------------------
-- Format: (bcnmId,groupId,groupRate,itemId,itemRate)
SELECT 'hxi_bcnm_crate_list' AS' ';
LOCK TABLE `hxi_bcnm_crate_list` WRITE;	
ALTER TABLE `hxi_bcnm_crate_list`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `itemRate`;
	
UPDATE hxi_bcnm_crate_list SET changes_tag='1', itemId='18852' WHERE itemId='17440' AND bcnmId='79'; -- Replace Kraken Club(17440) with Octave Club(18852)

UNLOCK TABLES;



-- ---------------------------------------------------------------------------
-- Format: (groupid,poolid,zoneid,name,respawntime,spawntype,dropid,HP,MP,minLevel,maxLevel,allegiance,changes_tag)
SELECT 'mob_groups' AS' ';
LOCK TABLE `mob_groups` WRITE;	
ALTER TABLE `mob_groups`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `allegiance`;

INSERT INTO `mob_groups` (`groupid`,`poolid`,`zoneid`,`name`,`respawntime`,`spawntype`,`dropid`,`HP`,`MP`,`minLevel`,`maxLevel`,`allegiance`,`changes_tag`) VALUES 
    
    -- Rolanberry_Fields (Zone: 110)
    (20000,0,110,'Ruinous_Rocs',0,0,20000,0,0,@HENM_lvl,@HENM_lvl,0,@HXI),

    -- Sauromugue_Champaign (Zone: 120)
    (20000,0,120,'Sacred_Scorpions',0,0,20001,0,0,@HENM_lvl,@HENM_lvl,0,@HXI),
    (20001,0,120,'Young_Uragnite',0,0,2527,0,0,36,42,0,@HXI),

    -- Jugner_Forest (Zone: 104)
    (20000,0,104,'Despotic_Decapod',0,0,20002,0,0,@HENM_lvl,@HENM_lvl,0,@HXI),

    -- Cape_Terrigan (Zone: 113)
    (20000,0,113,'Arid_Lizard',0,0,221,0,0,76,78,0,@HXI),
    (20001,0,113,'Dust_Bat',0,0,234,0,0,76,79,0,@HXI),

    -- Batallia Downs (Zone: 105)
    (20000,0,105,'Downslime',0,0,567,0,0,41,44,0,@HXI),

    -- Lufaise_Meadows (Zone: 24)
    (20000,0,24,'Padfoot',0,0,20003,0,0,45,46,0,0),

    -- Grand_Palace_of_HuXzoi (Zone: 34)
    (20000,0,34,'Ixaern_Mnk',0,0,20004,0,0,80,80,0,0)
;
UNLOCK TABLES;



-- ---------------------------------------------------------------------------
-- Format: (dropId,dropType,groupId,groupRate,itemId,itemRate,changes_tag)
SELECT 'mob_droplist' AS' ';
-- Notes
-- HENMs: dropId default start value 20000

LOCK TABLE `mob_droplist` WRITE;	
ALTER TABLE `mob_droplist`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `itemRate`;
 
INSERT INTO `mob_droplist` (`dropId`, `dropType`, `groupId`, `groupRate`, `itemId`, `itemRate`, `changes_tag`) VALUES

-- Variables
-- SET @ALWAYS = 1000;  -- Always, 100%
-- SET @VCOMMON = 240;  -- Very common, 24%
-- SET @COMMON = 150;   -- Common, 15%
-- SET @UNCOMMON = 100; -- Uncommon, 10%
-- SET @RARE = 50;      -- Rare, 5%
-- SET @VRARE = 10;     -- Very rare, 1%
-- SET @SRARE = 5;      -- Super Rare, 0.5%
-- SET @URARE = 1;      -- Ultra rare, 0.1%

    -- Ruinous_Rocs (Tier 1 HENM)
    (20000,0,0,0,15736,100,@HXI), -- Trotter Boots 
    (20000,0,0,0,50000,100,@HXI), -- Rucke's Ring (Horizon Exclusive)
    (20000,0,0,0,50001,100,@HXI), -- Vaulter's Ring (Horizon Exclusive)
    (20000,0,0,0,50002,100,@HXI), -- Luftpause Mark (Horizon Exclusive)
    (20000,0,0,0,658,150,@HXI), -- Damascus Ingot
    (20000,0,0,0,4655,100,@HXI), -- Scroll of Protectra V

    -- Sacred_Scorpions (Tier 1 HENM)
    (20001,0,0,0,50003,0,@HXI), -- Horus's Helm (Horizon Exclusive)
    (20001,0,0,0,50004,100,@HXI), -- Dilation Ring (Horizon Exclusive)
    (20001,0,0,0,50005,0,@HXI), -- Carapace bullet (Horizon Exclusive)
    (20001,0,0,0,50006,100,@HXI), -- Opuntia hoop (Horizon Exclusive)

    -- Despotic_Decapod (Tier 1 HENM)
    (20002,0,0,0,50007,100,@HXI), -- Overlord's Ring (Horizon Exclusive)
    (20002,0,0,0,50008,100,@HXI), -- Sprinter's Belt (Horizon Exclusive)
    (20002,0,0,0,50009,100,@HXI), -- Deflecting Band (Horizon Exclusive)
    (20002,0,0,0,50010,100,@HXI), -- Duality Loop (Horizon Exclusive)
    (20002,0,0,0,836,150,@HXI), -- Damascene Cloth

    -- Padfoot 
    (20003,1,1,1000,14676,750,@NUANCE), -- Assailant's Ring (Group 1 - 75%)
    (20003,1,1,1000,14782,250,@NUANCE), -- Astral Earring (Group 1 - 25%)
    (20003,0,0,1000,531,500,@NUANCE), -- Lanolin Cube (xi.drop_rate.GUARANTEED)
    (20003,0,0,1000,505,1000,@NUANCE), -- Sheepskin (xi.drop_rate.VERY_COMMON)

    -- Ixaern_Mnk 
    (20004,1,1,1000,1851,750,@NUANCE), -- Deed of Placidity (Group 1 - 75%)
    (20004,1,1,1000,1901,250,@NUANCE) -- Vice of Antipathy (Group 1 - 25%)

    -- -- All Coffer Keys drop rate increase to 10%
    -- (4,0,0,1000,1048,@UNCOMMON, @HXI),     -- Zvahl Coffer Key (Rare, 5%)
    -- (32,0,0,1000,1048,@UNCOMMON, @HXI),    -- Zvahl Coffer Key (Rare, 5%)
    -- (88,0,0,1000,1043,@UNCOMMON, @HXI),     -- Beadeaux Coffer Key (Rare, 5%)
    -- (124,0,0,1000,1054,@UNCOMMON, @HXI),     -- Quicksand Coffer Key (Rare, 5%)
    -- (131,0,0,1000,1054,@UNCOMMON, @HXI),     -- Quicksand Coffer Key (Rare, 5%)
    -- (137,0,0,1000,1054,@UNCOMMON, @HXI),      -- Quicksand Coffer Key (Rare, 5%)
    -- (163,0,0,1000,1048,@UNCOMMON, @HXI),     -- Zvahl Coffer Key (Rare, 5%)
    -- (167,0,0,1000,1049,@UNCOMMON, @HXI),     -- Uggalepih Coffer Key (Rare, 5%)
    -- (227,0,0,1000,1052,@UNCOMMON, @HXI),  -- Boyahda Coffer Key (Rare, 5%)
    -- (270,0,0,1000,1050,@UNCOMMON, @HXI),      -- Rancor Den Coffer Key (Rare, 5%)
    -- (310,0,0,1000,1048,@UNCOMMON, @HXI),     -- Zvahl Coffer Key (Rare, 5%)
    -- (316,0,0,1000,1059,@UNCOMMON, @HXI),   -- Grotto Coffer Key (Rare, 5%)
    -- (329,0,0,1000,1059,@UNCOMMON, @HXI),   -- Grotto Coffer Key (Rare, 5%)
    -- (342,0,0,1000,1052,@UNCOMMON, @HXI),  -- Boyahda Coffer Key (Rare, 5%)
    -- (344,0,0,1000,1057,@UNCOMMON, @HXI),   -- Toraimarai Coffer Key (Very Rare, 1%)
    -- (374,0,0,1000,1063,@UNCOMMON, @HXI),     -- Newton Coffer Key (Rare, 5%)
    -- (380,0,0,1000,1063,@UNCOMMON, @HXI),     -- Newton Coffer Key (Rare, 5%)
    -- (399,0,0,1000,1052,@UNCOMMON, @HXI),    -- Boyahda Coffer Key (Rare, 5%)
    -- (439,0,0,1000,1051,@UNCOMMON, @HXI),    -- Kuftal Coffer Key (Rare, 5%)
    -- (531,0,0,1000,1045,@UNCOMMON, @HXI),  -- Nest Coffer Key (Rare, 5%)
    -- (550,0,0,1000,1050,@UNCOMMON, @HXI),    -- Rancor Den Coffer Key (Rare, 5%) 
    -- (551,0,0,1000,1057,@UNCOMMON, @HXI),  -- Toraimarai Coffer Key (Very Rare, 1%)
    -- (566,0,0,1000,1043,@UNCOMMON, @HXI),     -- Beadeaux Coffer Key (Rare, 5%)
    -- (582,0,0,1000,1052,@UNCOMMON, @HXI),     -- Boyahda Coffer Key (Rare, 5%)
    -- (601,0,0,1000,1051,@UNCOMMON, @HXI), -- Kuftal Coffer Key (Very Rare, 1%)
    -- (639,0,0,1000,1060,@UNCOMMON, @HXI), -- Velugannon Coffer Key (Rare, 5%)
    -- (663,0,0,1000,1057,@UNCOMMON, @HXI),   -- Toraimarai Coffer Key (Rare, 5%)
    -- (672,0,0,1000,1053,@UNCOMMON, @HXI),     -- Cauldron Coffer Key (Rare, 5%)
    -- (674,0,0,1000,1048,@UNCOMMON, @HXI),     -- Zvahl Coffer Key (Rare, 5%)
    -- (679,0,0,1000,1057,@UNCOMMON, @HXI),   -- Toraimarai Coffer Key (Rare, 5%)
    -- (685,0,0,1000,1057,@UNCOMMON, @HXI),   -- Toraimarai Coffer Key (Very Rare, 1%)
    -- (724,0,0,1000,1060,@UNCOMMON, @HXI),     -- Velugannon Coffer Key (Rare, 5%)
    -- (812,0,0,1000,1047,@UNCOMMON, @HXI),   -- Garlaige Coffer Key (Rare, 5%)
    -- (813,0,0,1000,1047,@UNCOMMON, @HXI), -- Garlaige Coffer Key (Rare, 5%)
    -- (843,0,0,1000,1058,@UNCOMMON, @HXI),     -- Ruaun Coffer Key (Rare, 5%)
    -- (845,0,0,1000,1057,@UNCOMMON, @HXI),  -- Toraimarai Coffer Key (Rare, 5%)
    -- (1001,0,0,1000,1057,@UNCOMMON, @HXI),  -- Toraimarai Coffer Key (Rare, 5%)
    -- (1096,0,0,1000,1063,@UNCOMMON, @HXI),    -- Newton Coffer Key (Rare, 5%)
    -- (1167,0,0,1000,1063,@UNCOMMON, @HXI), -- Newton Coffer Key (6.0%)
    -- (1242,0,0,1000,1058,@UNCOMMON, @HXI),    -- Ruaun Coffer Key (Rare, 5%)
    -- (1282,0,0,1000,1051,@UNCOMMON, @HXI),     -- Kuftal Coffer Key (Rare, 5%)
    -- (1283,0,0,1000,1046,@UNCOMMON, @HXI),   -- Eldieme Coffer Key (Rare, 5%)
    -- (1295,0,0,1000,1047,@UNCOMMON, @HXI),   -- Garlaige Coffer Key (Rare, 5%)
    -- (1298,0,0,1000,1045,@UNCOMMON, @HXI),   -- Nest Coffer Key (Rare, 5%)
    -- (1299,0,0,1000,1054,@UNCOMMON, @HXI),    -- Quicksand Coffer Key (Rare, 5%)
    -- (1331,0,0,1000,1049,@UNCOMMON, @HXI),     -- Uggalepih Coffer Key (Rare, 5%)
    -- (1361,0,0,1000,1057,@UNCOMMON, @HXI),   -- Toraimarai Coffer Key (Very Rare, 1%)
    -- (1377,0,0,1000,1049,@UNCOMMON, @HXI),  -- Uggalepih Coffer Key (Rare, 5%)
    -- (1456,0,0,1000,1052,@UNCOMMON, @HXI),  -- Boyahda Coffer Key (Rare, 5%)
    -- (1457,0,0,1000,1045,@UNCOMMON, @HXI),    -- Nest Coffer Key (Rare, 5%)
    -- (1566,0,0,1000,1047,@UNCOMMON, @HXI),     -- Garlaige Coffer Key (Rare, 5%)
    -- (1636,0,0,1000,1059,@UNCOMMON, @HXI),   -- Grotto Coffer Key (Rare, 5%)
    -- (1695,0,0,1000,1057,@UNCOMMON, @HXI),    -- Toraimarai Coffer Key (Rare, 5%)
    -- (1703,0,0,1000,1063,@UNCOMMON, @HXI),     -- Newton Coffer Key (Rare, 5%)
    -- (1717,0,0,1000,1063,@UNCOMMON, @HXI),     -- Newton Coffer Key (Rare, 5%)
    -- (1742,0,0,1000,1052,@UNCOMMON, @HXI),   -- Boyahda Coffer Key (Rare, 5%)
    -- (1746,0,0,1000,1052,@UNCOMMON, @HXI),   -- Boyahda Coffer Key (Rare, 5%)
    -- (1749,0,0,1000,1050,@UNCOMMON, @HXI),  -- Rancor Den Coffer Key (Rare, 5%)
    -- (1751,0,0,1000,1057,@UNCOMMON, @HXI),   -- Toraimarai Coffer Key (Rare, 5%)
    -- (1764,0,0,1000,1060,@UNCOMMON, @HXI), -- Velugannon Coffer Key (Rare, 5%)
    -- (1782,0,0,1000,1063,@UNCOMMON, @HXI),     -- Newton Coffer Key (Rare, 5%)
    -- (1846,0,0,1000,1053,@UNCOMMON, @HXI),     -- Cauldron Coffer Key (Rare, 5%)
    -- (1882,0,0,1000,1042,@UNCOMMON, @HXI),    -- Davoi Coffer Key (Very Rare, 1%)
    -- (1883,0,0,1000,1042,@UNCOMMON, @HXI),    -- Davoi Coffer Key (Very Rare, 1%)
    -- (1891,0,0,1000,1042,@UNCOMMON, @HXI),    -- Davoi Coffer Key (Very Rare, 1%)
    -- (1892,0,0,1000,1042,@UNCOMMON, @HXI),     -- Davoi Coffer Key (Rare, 5%)
    -- (1893,0,0,1000,1042,@UNCOMMON, @HXI),     -- Davoi Coffer Key (Rare, 5%)
    -- (1894,0,0,1000,1042,@UNCOMMON, @HXI),     -- Davoi Coffer Key (Rare, 5%)
    -- (1896,0,0,1000,1042,@UNCOMMON, @HXI),     -- Davoi Coffer Key (Rare, 5%)
    -- (1897,0,0,1000,1042,@UNCOMMON, @HXI),     -- Davoi Coffer Key (Rare, 5%)
    -- (1957,0,0,1000,1060,@UNCOMMON, @HXI), -- Velugannon Coffer Key (Rare, 5%)
    -- (1968,0,0,1000,1047,@UNCOMMON, @HXI), -- Garlaige Coffer Key (Rare, 5%)
    -- (2041,0,0,1000,1063,@UNCOMMON, @HXI),    -- Newton Coffer Key (Rare, 5%)
    -- (2080,0,0,1000,1059,@UNCOMMON, @HXI),    -- Grotto Coffer Key (Rare, 5%)
    -- (2084,0,0,1000,1051,@UNCOMMON, @HXI),  -- Kuftal Coffer Key (Rare, 5%)
    -- (2109,0,0,1000,1052,@UNCOMMON, @HXI),    -- Boyahda Coffer Key (Rare, 5%)
    -- (2110,0,0,1000,1051,@UNCOMMON, @HXI),    -- Kuftal Coffer Key (Rare, 5%)
    -- (2113,0,0,1000,1059,@UNCOMMON, @HXI),    -- Grotto Coffer Key (Rare, 5%)
    -- (2124,0,0,1000,1057,@UNCOMMON, @HXI),     -- Toraimarai Coffer Key (Rare, 5%)
    -- (2141,0,0,1000,1051,@UNCOMMON, @HXI),     -- Kuftal Coffer Key (Rare, 5%)
    -- (2149,0,0,1000,1054,@UNCOMMON, @HXI),   -- Quicksand Coffer Key (Very Rare, 1%)
    -- (2153,0,0,1000,1051,@UNCOMMON, @HXI),     -- Kuftal Coffer Key (Rare, 5%)
    -- (2154,0,0,1000,1054,@UNCOMMON, @HXI),     -- Quicksand Coffer Key (Rare, 5%)
    -- (2156,0,0,1000,1054,@UNCOMMON, @HXI), -- Quicksand Coffer Key (Very Rare, 1%)
    -- (2158,0,0,1000,1043,@UNCOMMON, @HXI),    -- Beadeaux Coffer Key (Very Rare, 1%)
    -- (2177,0,0,1000,1057,@UNCOMMON, @HXI),    -- Toraimarai Coffer Key (Rare, 5%)
    -- (2178,0,0,1000,1051,@UNCOMMON, @HXI),     -- Kuftal Coffer Key (Rare, 5%)
    -- (2311,0,0,1000,1046,@UNCOMMON, @HXI), -- Eldieme Coffer Key (Rare, 5%)
    -- (2313,0,0,1000,1058,@UNCOMMON, @HXI),     -- Ruaun Coffer Key (Rare, 5%)
    -- (2325,0,0,1000,1057,@UNCOMMON, @HXI),   -- Toraimarai Coffer Key (Very Rare, 1%)
    -- (2355,0,0,1000,1052,@UNCOMMON, @HXI), -- Boyahda Coffer Key (Rare, 5%)
    -- (2356,0,0,1000,1051,@UNCOMMON, @HXI), -- Kuftal Coffer Key (Rare, 5%)
    -- (2358,0,0,1000,1050,@UNCOMMON, @HXI),   -- Rancor Den Coffer Key (Rare, 5%)
    -- (2365,0,0,1000,1059,@UNCOMMON, @HXI),  -- Grotto Coffer Key (Very Rare, 1%)
    -- (2372,0,0,1000,1047,@UNCOMMON, @HXI),  -- Garlaige Coffer Key (Rare, 5%)
    -- (2424,0,0,1000,1046,@UNCOMMON, @HXI),   -- Eldieme Coffer Key (Rare, 5%)
    -- (2425,0,0,1000,1046,@UNCOMMON, @HXI),  -- Eldieme Coffer Key (Rare, 5%)
    -- (2429,0,0,1000,1050,@UNCOMMON, @HXI),     -- Rancor Den Coffer Key (Rare, 5%)
    -- (2434,0,0,1000,1049,@UNCOMMON, @HXI),     -- Uggalepih Coffer Key (Rare, 5%)
    -- (2442,0,0,1000,1049,@UNCOMMON, @HXI),     -- Uggalepih Coffer Key (Rare, 5%)
    -- (2444,0,0,1000,1049,@UNCOMMON, @HXI),     -- Uggalepih Coffer Key (Rare, 5%)
    -- (2446,0,0,1000,1050,@UNCOMMON, @HXI),     -- Rancor Den Coffer Key (Rare, 5%)
    -- (2447,0,0,1000,1049,@UNCOMMON, @HXI),     -- Uggalepih Coffer Key (Rare, 5%)
    -- (2450,0,0,1000,1050,@UNCOMMON, @HXI),   -- Rancor Den Coffer Key (Very Rare, 1%)
    -- (2566,0,0,1000,1047,@UNCOMMON, @HXI), -- Garlaige Coffer Key (Rare, 5%)
    -- (2586,0,0,1000,1053,@UNCOMMON, @HXI),     -- Cauldron Coffer Key (Rare, 5%)
    -- (2587,0,0,1000,1053,@UNCOMMON, @HXI),   -- Cauldron Coffer Key (Rare, 5%)
    -- (2675,0,0,1000,1047,@UNCOMMON, @HXI),   -- Garlaige Coffer Key (Rare, 5%)
    -- (2695,0,0,1000,1044,@UNCOMMON, @HXI),    -- Oztroja Coffer Key (Very Rare, 1%)
    -- (2748,0,0,1000,1044,@UNCOMMON, @HXI),    -- Oztroja Coffer Key (Very Rare, 1%)
    -- --          (2827,0,0,1000,1044,@COMMON); -- Oztroja Coffer Key (Common, 15%)
    -- (2833,0,0,1000,1044,@UNCOMMON, @HXI),     -- Oztroja Coffer Key (Rare, 5%)
    -- (2834,0,0,1000,1044,@UNCOMMON, @HXI),    -- Oztroja Coffer Key (Very Rare, 1%)
    -- (2915,0,0,1000,1043,@UNCOMMON, @HXI),   -- Beadeaux Coffer Key (Very Rare, 1%)
    -- (6045,0,0,1000,1063,@UNCOMMON, @HXI),    -- Newton Coffer Key (Very Rare, 1%)
    -- (6046,0,0,1000,1063,@UNCOMMON, @HXI),    -- Newton Coffer Key (Very Rare, 1%)
    -- (6051,0,0,1000,1054,@UNCOMMON, @HXI),     -- Quicksand Coffer Key (Rare, 5%)
    -- (6057,0,0,1000,1059,@UNCOMMON, @HXI),     -- Grotto Coffer Key (Rare, 5%)
    -- (6058,0,0,1000,1050,@UNCOMMON, @HXI),     -- Rancor Den Coffer Key (Rare, 5%)
    -- (6059,0,0,1000,1050,@UNCOMMON, @HXI),     -- Rancor Den Coffer Key (Rare, 5%)
    -- (6060,0,0,1000,1053,@UNCOMMON, @HXI),   -- Cauldron Coffer Key (Rare, 5%)
    -- (6062,0,0,1000,1049,@UNCOMMON, @HXI)     -- Uggalepih Coffer Key (Rare, 5%)

;

-- UPDATE mob_droplist set itemRate = 50, groupId =  3, changes_tag = 1 WHERE itemId = 16555; -- ZoneID: 154 - Fafnir -- Ridill 1% -> 5%; move to new group with Andvaranauts
-- UPDATE mob_droplist set groupId =  3, changes_tag = 1 WHERE itemId = 14075; -- ZoneID: 154 - Fafnir -- Andvaranauts move to new group with Ridill

UNLOCK TABLES;

-- ---------------------------------------------------------------------------
-- Format: (itemid,subid,name,sortname,stackSize,flags,aH,NoSale,BaseSell,changes_tag)
SELECT 'item_basic' AS' ';

LOCK TABLE `item_basic` WRITE;	
ALTER TABLE `item_basic`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `BaseSell`;

INSERT INTO `item_basic` (`itemid`,`subid`,`name`,`sortname`,`stackSize`,`flags`,`aH`,`NoSale`,`BaseSell`,`changes_tag`) VALUES

    -- Ruinous_Rocs (Tier 1 HENM)
    (50000,0,'ruckes_ring','ruckes_ring',1,63552,0,1,0,@HXI), -- Rucke's Ring
    (50001,0,'vaulters_ring','vaulters_ring',1,63552,0,1,0,@HXI), -- Vaulter's Ring
    (50002,0,'luftpause_mark','luftpause_mark',1,63552,0,1,0,@HXI), -- Luftpause Mark

    -- Sacred_Scorpions (Tier 1 HENM)
    (50003,0,'horuss_helm','horuss_helm',1,63552,0,1,0,@HXI), -- Horus's Helm 
    (50004,0,'dilation_ring','dilation_ring',1,63552,0,1,0,@HXI), -- Dilation Ring
    (50005,0,'carapace_bullet','carapace_bullet',1,63552,0,1,0,@HXI), -- Carapace bullet
    (50006,0,'opuntia_hoop','opuntia_hoop',1,63552,0,1,0,@HXI), -- Opuntia hoop

    -- Despotic_Decapod (Tier 1 HENM)
    (50007,0,'overlords_ring','overlords_ring',1,63552,0,1,0,@HXI), -- Overlord's Ring 
    (50008,0,'sprinters_belt','sprinters_belt',1,63552,0,1,0,@HXI), -- Sprinter's Belt
    (50009,0,'deflecting_band','deflecting_band',1,63552,0,1,0,@HXI), -- Deflecting Band
    (50010,0,'duality_loop','duality_loop',1,63552,0,1,0,@HXI) -- Duality Loop

;


UNLOCK TABLES;
