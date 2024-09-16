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
DELETE FROM `mob_droplist` WHERE dropId=3137 OR dropId=2908; -- Dyna-Tav: Diabolos NMs > lvl 90 should stay removed... lower level range has custom drop lists below


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
    (20001,25002,120,'Young_Uragnite',0,0,0,0,0,36,42,0,@HXI),

    -- Jugner_Forest (Zone: 104)
    (20000,25003,104,'Despotic_Decapod',0,0,20002,0,0,@HENM_lvl,@HENM_lvl,0,@HXI),

    -- Cape_Terrigan (Zone: 113)
    (20000,25004,113,'Arid_Lizard',0,0,0,0,0,76,78,0,@HXI),
    (20001,25005,113,'Dust_Bat',0,0,0,0,0,76,79,0,@HXI),

    -- Batallia Downs (Zone: 105)
    (20000,25006,105,'Downslime',0,0,0,0,0,41,44,0,@HXI),

    -- Lufaise_Meadows (Zone: 24)
    (20000,3083,24,'Padfoot',0,0,20003,0,0,45,46,0,0),  

    -- Grand_Palace_of_HuXzoi (Zone: 34)
    (20000,4661,34,'Ixaern_Mnk',0,0,20004,0,0,80,80,0,0)
;

UPDATE mob_groups SET changes_tag='1', dropid='644' WHERE poolid='6778' AND zoneid='113'; -- Devil Manta Fished - added drops list from Kuftal Tunnel to match Cape Terrigan

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
    (20004,1,1,1000,1901,250,@NUANCE), -- Vice of Antipathy (Group 1 - 25%)
    
    -- Dynamis-Tavnazia: Diabolos
    (2908,0,1,1000,1450,333,@HXI), -- Lungo-Nango Jadeshell (33.3%)
    (2908,0,1,1000,1453,333,@HXI), -- Montiont Silverpiece (33.3%)
    (2908,0,1,1000,1456,333,@HXI), -- One Hundred Byne Bill (33.3%)
    -- (2908,0,2,1000,0,700,@HXI), -- NOTHING (70 %)
    (2908,0,2,100,1450,333,@HXI), -- Lungo-Nango Jadeshell (Uncommon, 10%)
    (2908,0,2,100,1453,333,@HXI), -- Montiont Silverpiece (Uncommon, 10%)
    (2908,0,2,100,1456,333,@HXI), -- One Hundred Byne Bill (Uncommon, 10%)
    (2908,0,3,1000,15260,100,@HXI),    -- Hydra Beret (Uncommon, 10%)
    (2908,0,3,1000,14515,100,@HXI),    -- Hydra Doublet (Uncommon, 10%)
    (2908,0,3,1000,14924,100,@HXI),    -- Hydra Gloves (Uncommon, 10%)
    (2908,0,3,1000,15595,100,@HXI),    -- Hydra Brais (Uncommon, 10%)
    (2908,0,3,1000,15680,100,@HXI),    -- Hydra Gaiters (Uncommon, 10%)
    (2908,0,3,1000,15262,100,@HXI),    -- Hydra Salade (Uncommon, 10%)
    (2908,0,3,1000,14517,100,@HXI),    -- Hydra Haubert (Uncommon, 10%)
    (2908,0,3,1000,14926,100,@HXI),    -- Hydra Moufles (Uncommon, 10%)
    (2908,0,3,1000,15597,100,@HXI),    -- Hydra Brayettes (Uncommon, 10%)
    (2908,0,3,1000,15682,100,@HXI),    -- Hydra Sollerets (Uncommon, 10%)
    (2908,0,4,1000,0,700,@HXI),    -- NOTHING (70 %)
    (2908,0,4,1000,50018,300,@HXI),    -- Dream Collar (30%)
    -- (2908,0,5,240,0,620,@HXI),    -- NOTHING (62 %)
    (2908,0,5,240,50016,500,@HXI),    -- Sack of Dream Sand (Very Common, 24%)
    (2908,0,5,240,50017,500,@HXI)    -- Dream Ribbon (Very Common, 24%)

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

 -- https://discord.com/channels/933423693848260678/1128323171821563986/1235284032485593119
UPDATE mob_droplist set itemRate = 50, groupId =  3, changes_tag = @HXI WHERE itemId = 16555; -- ZoneID: 154 - Fafnir -- Ridill 1% -> 5%; move to new group with Andvaranauts
UPDATE mob_droplist set itemRate = 950, groupId =  3, changes_tag = @HXI WHERE itemId = 14075; -- ZoneID: 154 - Fafnir -- Andvaranauts move to new group with Ridill

UNLOCK TABLES;


