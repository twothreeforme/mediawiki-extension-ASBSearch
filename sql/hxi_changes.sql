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
DELETE FROM `item_basic` WHERE itemid>=50000 AND itemid<60000 AND itemid=0;


-- ---------------------------------------------------------------------------
-- Format: (bcnmId,groupId,groupRate,itemId,itemRate,gilAmount)
SELECT 'hxi_bcnm_crate_list' AS' ';
LOCK TABLE `hxi_bcnm_crate_list` WRITE;	
ALTER TABLE `hxi_bcnm_crate_list`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `gilAmount`;
	
UPDATE hxi_bcnm_crate_list SET changes_tag='1', itemId='18852' WHERE itemId='17440' AND bcnmId='79'; -- Replace Kraken Club(17440) with Octave Club(18852)
UPDATE hxi_bcnm_crate_list SET changes_tag='1', itemRate='500', groupId='11' WHERE itemId='1527' AND bcnmId='11'; -- Update BEHEMOTH_TONGUE drop rate to 50%... needed new Group to do so 
UPDATE hxi_bcnm_crate_list SET changes_tag='1', itemRate='500', groupId='11' WHERE itemId='1526' AND bcnmId='107'; -- Update WYRM_BEARD drop rate to 50%... needed new Group to do so 
UPDATE hxi_bcnm_crate_list SET changes_tag='1', itemRate='500', groupId='11' WHERE itemId='1525' AND bcnmId='76'; -- Update WYRM_BEARD drop rate to 50%... needed new Group to do so 

UNLOCK TABLES;



-- ---------------------------------------------------------------------------
-- Format: (groupid,poolid,zoneid,name,respawntime,spawntype,dropid,HP,MP,minLevel,maxLevel,allegiance,changes_tag)
SELECT 'mob_groups' AS' ';
LOCK TABLE `mob_groups` WRITE;	
ALTER TABLE `mob_groups`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `allegiance`;

INSERT INTO `mob_groups` (`groupid`,`poolid`,`zoneid`,`name`,`respawntime`,`spawntype`,`dropid`,`HP`,`MP`,`minLevel`,`maxLevel`,`allegiance`,`changes_tag`) VALUES 
    
    -- Rolanberry_Fields (Zone: 110)
    (20000,25000,110,'Ruinous_Rocs',0,0,20000,0,0,@HENM_lvl,@HENM_lvl,0,@HXI),

    -- Sauromugue_Champaign (Zone: 120)
    (20000,25001,120,'Sacred_Scorpions',0,0,20001,0,0,@HENM_lvl,@HENM_lvl,0,@HXI),
    (20001,25002,120,'Young_Uragnite',0,0,2527,0,0,36,42,0,@HXI),

    -- Jugner_Forest (Zone: 104)
    (20000,25003,104,'Despotic_Decapod',0,0,20002,0,0,@HENM_lvl,@HENM_lvl,0,@HXI),

    -- Cape_Terrigan (Zone: 113)
    (20000,25004,113,'Arid_Lizard',0,0,221,0,0,76,78,0,@HXI),
    (20001,25005,113,'Dust_Bat',0,0,234,0,0,76,79,0,@HXI),

    -- Batallia Downs (Zone: 105)
    (20000,25006,105,'Downslime',0,0,567,0,0,41,44,0,@HXI),

    -- Lufaise_Meadows (Zone: 24)
    (20000,3083,24,'Padfoot',0,0,20003,0,0,45,46,0,0),  

    -- Grand_Palace_of_HuXzoi (Zone: 34)
    (20000,4661,34,'Ixaern_Mnk',0,0,20004,0,0,80,80,0,0)
;
UNLOCK TABLES;


-- ---------------------------------------------------------------------------
-- Format: (poolid,name,packet_name,familyid,modelid,mJob,sJob,cmbSkill,cmbDelay,cmbDmgMult,behavior,aggro,true_detection,
--  links,mobType,immunity,name_prefix,flag,entityFlags,animationsub,hasSpellScript,spellList,namevis,roamflag,skill_list_id,
--  resist_id)
SELECT 'mob_pools' AS' ';

LOCK TABLE `mob_pools` WRITE;	
ALTER TABLE `mob_pools`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `resist_id`;
 
INSERT INTO `mob_pools` (`poolid`,  `name`,     `packet_name`,              `familyid`,   `modelid`,`mJob`,`sJob`,`cmbSkill`,`cmbDelay`,`cmbDmgMult`,`behavior`,`aggro`,`true_detection`,`links`,`mobType`,`immunity`,`name_prefix`,`flag`,`entityFlags`,`animationsub`,`hasSpellScript`,`spellList`,`namevis`,`roamflag`,`skill_list_id`,`resist_id`,`changes_tag`) VALUES

                        (25000,     'Ruinous_Rocs',  'Ruinous_Rocs',            0,            0,      0,     0,      0,         0,          0,         0,         0,       0,               0,      2,         0,          0,          0,      0,              0,              0,              0,          0,      0,           0,             0,        @HXI),
                        (25001,     'Sacred_Scorpions',  'Sacred_Scorpions',    0,            0,      0,     0,      0,         0,          0,         0,         0,       0,               0,      2,         0,          0,          0,      0,              0,              0,              0,          0,      0,           0,             0,        @HXI),
                        (25002,     'Young_Uragnite',  'Young_Uragnite',        0,            0,      0,     0,      0,         0,          0,         0,         0,       0,               0,      0,         0,          0,          0,      0,              0,              0,              0,          0,      0,           0,             0,        @HXI),
                        (25003,     'Despotic_Decapod',  'Despotic_Decapod',    0,            0,      0,     0,      0,         0,          0,         0,         0,       0,               0,      0,         0,          0,          0,      0,              0,              0,              0,          0,      0,           0,             0,        @HXI),
                        (25004,     'Arid_Lizard',  'Arid_Lizard',              0,            0,      0,     0,      0,         0,          0,         0,         0,       0,               0,      0,         0,          0,          0,      0,              0,              0,              0,          0,      0,           0,             0,        @HXI),
                        (25005,     'Dust_Bat',  'Dust_Bat',                    0,            0,      0,     0,      0,         0,          0,         0,         0,       0,               0,      0,         0,          0,          0,      0,              0,              0,              0,          0,      0,           0,             0,        @HXI),
                        (25006,     'Downslime',  'Downslime',                  0,            0,      0,     0,      0,         0,          0,         0,         0,       0,               0,      0,         0,          0,          0,      0,              0,              0,              0,          0,      0,           0,             0,        @HXI)

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

    
;    
-- -- All Coffer Keys drop rate increase to 10%
UPDATE mob_droplist set itemRate = 100, changes_tag = 1 WHERE (dropId < 1674 OR dropId > 1692) AND itemRate < 101 AND itemId IN 
    (
    1048, -- Zvahl Coffer Key (Rare, 5%)
    1043,   -- Beadeaux Coffer Key (Rare, 5%)
    1054,   -- Quicksand Coffer Key (Rare, 5%)
    1049,   -- Uggalepih Coffer Key (Rare, 5%)
    1052,   -- Boyahda Coffer Key (Rare, 5%)
    1050,   -- Rancor Den Coffer Key (Rare, 5%)
    1059,   -- Grotto Coffer Key (Rare, 5%)
    1057,   -- Toraimarai Coffer Key (Very Rare, 1%)
    1063,   -- Newton Coffer Key (Rare, 5%)   
    1051,   -- Kuftal Coffer Key (Rare, 5%)
    1045,   -- Nest Coffer Key
    1060,   -- Velugannon Coffer Key
    1053,   -- Cauldron Coffer Key
    1047,   -- Garlaige Coffer Key
    1058,   -- Ruaun Coffer Key
    1046,   -- Eldieme Coffer Key
    1042,   -- Davoi Coffer Key
    1044    -- Oztroja Coffer Key
    ); 
    
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

    -- NOTHING - added to show 'Nothing' as a drop on the table
    (0,0,'nothing','nothing',1,0,0,1,0,0), -- Nothing

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
