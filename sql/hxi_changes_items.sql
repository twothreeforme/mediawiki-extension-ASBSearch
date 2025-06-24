-- HXI specific Item Modifiers consolidated

-- Koenig Schaller
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 11 WHERE `itemId` = 12421 AND `modId` = 14 AND `value` = 10; -- CHR+10 to AGI+10

-- Adaman Celata 
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 8 WHERE `itemId` = 12429 AND `modId` = 25;  -- ACC+5 to ACC+8  

-- Abyssal Earring 
INSERT INTO `item_mods` VALUES (14741,83,5,1); -- GSWORD: 5

-- Beastly Earring
INSERT INTO `item_mods` VALUES (14742,83,5,1); -- POLEARM: 5
INSERT INTO `item_mods` VALUES (14742,991,10,1);  -- PET_ACC_EVA: 10

-- Razor Axe
UPDATE `item_weapon` SET `changes_tag` = 1, `delay` = 280, `dmg` = 33 WHERE `itemId` = 16678;

-- Fighter's Calligae
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 2, `modId` = 9  WHERE `itemId` = 14089 AND `modId` = 11;  -- AGI+3 to DEX+2

-- Fighter's Lorica
INSERT INTO `item_mods` VALUES (12638,25,3,1);   -- ACC: 3

-- Temple Crown
INSERT INTO `item_mods` VALUES (12512,23,5,1);  -- ATT: 5

-- Temple Hose
INSERT INTO `item_mods` VALUES (14215,25,3,1);   -- ACC: 3

-- Spharai
UPDATE `item_weapon` SET `changes_tag` = 1, `dmg` = 24 WHERE `itemId` = 18264;

-- Blessed Hammer
UPDATE `item_weapon` SET `changes_tag` = 1, `dmg` = 31 WHERE `itemId` = 17422;
INSERT INTO `item_mods` VALUES (17422,71,3,1);  -- MPHEAL: 3
UPDATE `item_equipment` SET `changes_tag` = 1, `level` = 40 WHERE `itemId` = 17422 AND `level` = 41;  -- Lvl:41 to Lvl:40


-- Healers Duckbills
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 3, `modId` = 12  WHERE `itemId` = 14091 AND `modId` = 11;  -- AGI+3 to DEX+2
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 20  WHERE `itemId` = 14091 AND `modId` = 5 AND `value` = 10;  -- MP+10 to MP+20

-- Healers Bliault
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 25  WHERE `itemId` = 12640 AND `modId` = 5 AND `value` = 15;  -- MP+15 to MP+25

-- Healers Duckbills +1
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 20  WHERE `itemId` = 15354 AND `modId` = 5 AND `value` = 15;  -- MP+15 to MP+20

-- Mjollnir
UPDATE `item_weapon` SET `changes_tag` = 1, `dmg` = 58 WHERE `itemId` = 18324;
INSERT INTO `item_mods` VALUES (18324,369,1,1);     -- REFRESH: 1

-- Casting Wand
INSERT INTO `item_mods` VALUES (17423,12,5,1); -- INT+5
UPDATE `item_equipment` SET `changes_tag` = 1, `level` = 40 WHERE `itemId` = 17423 AND `level` = 41;  -- Lvl:41 to Lvl:40

-- Wizards Sabots
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 20  WHERE `itemId` = 14092 AND `modId` = 5 AND `value` = 10;  -- MP+10 to MP+20
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 1, `modId` = 12  WHERE `itemId` = 14092 AND `modId` = 11;  -- AGI+3 to INT+1

-- Wizards Sabots +1
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 20  WHERE `itemId` = 15355 AND `modId` = 5 AND `value` = 15;  -- MP+15 to MP+20
INSERT INTO `item_mods` VALUES (15355,12,1,1); -- INT+1

-- Claustrum
INSERT INTO `item_mods` VALUES (18648,28,20,1); -- MATT+20
INSERT INTO `item_mods` VALUES (18648,357,-5,1); -- BP_DELAY: -5
INSERT INTO `item_mods` VALUES (18648,990,25,1); -- PET_ATK_DEF: +25
-- Additional Effect: Prismatic - custom HXI

-- Fencing Degen
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 3 WHERE `itemId` = 16829 AND `modId` = 12;  -- INT+1 to INT+3
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 3 WHERE `itemId` = 16829 AND `modId` = 13;  -- MND+1 to MND+3
INSERT INTO `item_mods` VALUES (16829,114,3,1); -- ENFEEBLING SKILL +3

-- Warlocks Boots
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 12, `value` = 1 WHERE `itemId` = 14093 AND `modId` = 11;  -- AGI+3 to INT+1
INSERT INTO `item_mods` VALUES (14093,13,1,1);   -- MND: 1

-- Rogues Bonnet
INSERT INTO `item_mods` VALUES (12514,9,2,1);  -- DEX: 2

-- Rogues Vest
INSERT INTO `item_mods` VALUES (12643,25,3,1);   -- ACC: 3

-- Rogues Bonnet +1
INSERT INTO `item_mods` VALUES (15230,12,5,1); -- INT+5
INSERT INTO `item_mods` VALUES (15230,298,1,1); -- STEAL: 1

-- Nanaa's Lucky Charm
-- DAT replaced: 23040  
INSERT INTO `item_mods` VALUES (50021,25,3,1);   -- ACC: 3
INSERT INTO `item_mods` VALUES (50021,303,1,1); -- TREASURE_HUNTER: 1

-- Honor Sword
UPDATE `item_equipment` SET `changes_tag` = 1, `level` = 40 WHERE `itemId` = 17643 AND `level` = 42;  -- Lvl:42 to Lvl:40
INSERT INTO `item_mods` VALUES (17643,2,10,1);  -- HP: 20
INSERT INTO `item_mods` VALUES (17643,5,10,1);  -- MP: 20

-- Gallant Leggings
INSERT INTO `item_mods` VALUES (14095,9,2,1);   -- DEX: 2

-- Gallant Surcoat
INSERT INTO `item_mods` VALUES (12644,25,3,1);   -- ACC: 3

-- Gallant Leggings +1
INSERT INTO `item_mods` VALUES (15358,9,2,1);   -- DEX: 2

-- Gallant Surcoat +1
INSERT INTO `item_mods` VALUES (14479,25,3,1);   -- ACC: 3

-- Ragnarok
UPDATE `item_weapon` SET `changes_tag` = 1, `dmg` = 93 WHERE `itemId` = 18282 AND `dmg` = 86;  -- DMG:86 to DMG:93
UPDATE `item_weapon` SET `changes_tag` = 1, `delay` = 466 WHERE `itemId` = 18282 AND `delay` = 431;  -- Delay:431 to Delay:466

-- Raven Scythe
UPDATE `item_equipment` SET `changes_tag` = 1, `level` = 40 WHERE `itemId` = 16798 AND `level` = 43;  -- Lvl:43 to Lvl:40

-- Chaos Sollerets
INSERT INTO `item_mods` VALUES (14096,25,3,1);   -- ACC: 3

-- Chaos Flanchard
INSERT INTO `item_mods` VALUES (14221,9,2,1);   -- DEX: 2

-- Chaos Cuirass
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 8, `value` = 3 WHERE `itemId` = 12645 AND `modId` = 10 AND `value` = 3;  -- VIT+3 to STR+3

-- Chaos Sollerets +1
INSERT INTO `item_mods` VALUES (15359,25,3,1);   -- ACC: 3

-- Barbaroi Axe
INSERT INTO `item_mods` VALUES (16680,991,5,1);  -- PET_ACC_EVA: 5

-- Beast Gaiters
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 14, `value` = 3 WHERE `itemId` = 14097 AND `modId` = 11 AND `value` = 3;  -- AGI+3 to CHR+3

-- Beast Helm
INSERT INTO `item_mods` VALUES (12517,993,5,1); -- PET_MACC_MEVA: 5
INSERT INTO `item_mods` VALUES (12517,13,3,1);   -- MND: 3

-- Beast Jackcoat
INSERT INTO `item_mods` VALUES (12646,25,3,1);   -- ACC: 3

-- Beast Helm +1
INSERT INTO `item_mods` VALUES (15233,993,8,1); -- PET_MACC_MEVA: 5

-- Paper Knife
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 5 WHERE `itemId` = 16766 AND `modId` = 14 AND `value` = 2;  -- CHR+2 to CHR+5
DELETE FROM `item_mods` WHERE itemid = 16766 AND `modId` = 11; -- Remove AGI

-- Choral Slippers
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 2 WHERE `itemId` = 14098 AND `modId` = 11 AND `value` = 3;  -- AGI+3 to AGI+2
INSERT INTO `item_mods` VALUES (14098,9,2,1);

-- Choral Roundlet
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 2 WHERE `itemId` = 13857 AND `modId` = 13 AND `value` = 3;  -- MND+3 to MND+2
INSERT INTO `item_mods` VALUES (13857,14,2,1);
-- Additional effect: Gravity instead of Additional effect: Poison

-- Hunters Bracers
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 3, `modId` = 11  WHERE `itemId` = 13971 AND `modId` = 9;  -- DEX+3 to AGI+3

-- Hunters Braccae
INSERT INTO `item_mods` VALUES (14224,11,2,1); -- AGI:2
-- added "Master Archer" trait

-- Hunters Zamburak
UPDATE `item_basic` SET `changes_tag` = 1, `name` = "hunter\'s_zamburak", `sortname` = "hunter\'s_zamburak" WHERE `itemid` = 17188; -- from Sniping Bow to Hunter's Zamburak
UPDATE `item_equipment` SET `changes_tag` = 1, `name` = "hunter\'s_zamburak" WHERE `itemid` = 17188; -- from Sniping Bow to Hunter's Zamburak
UPDATE `item_weapon` SET `changes_tag` = 1, `name` = "hunter\'s_zamburak" WHERE `itemid` = 17188; -- from Sniping Bow to Hunter's Zamburak
UPDATE `item_weapon` SET `changes_tag` = 1, `delay` = 288 WHERE `itemId` = 17188 AND `delay` = 360; -- DELAY:360 to DELAY:288
INSERT INTO `item_mods` VALUES (17188,24,3,1); -- RATT: 3
INSERT INTO `item_mods` VALUES (17188,26,3,1); -- RACC: 3

-- Magoroku
UPDATE `item_equipment` SET `changes_tag` = 1, `level` = 40 WHERE `itemId` = 17812 AND `level` = 42;  -- Lvl:42 to Lvl:40

-- Myochin Sune-Ate
INSERT INTO `item_mods` VALUES (14100,11,4,1); -- AGI:4

-- Myochin Domaru
INSERT INTO `item_mods` VALUES (13781,25,3,1); -- ACC:3

-- Myochin Kabuto
INSERT INTO `item_mods` VALUES (13868,8,2,1); -- STR:2

-- Yoichinoyumi
-- added "Master Archer" trait

-- Anju
UPDATE `item_weapon` SET `changes_tag` = 1, `dmg` = 23 WHERE `itemId` = 17771; -- DMG:21 to DMG:23

-- Zushio
UPDATE `item_weapon` SET `changes_tag` = 1, `dmg` = 25 WHERE `itemId` = 17772; -- DMG:23 to DMG:25

-- Ninja Hatsuburi
INSERT INTO `item_mods` VALUES (13869,11,3,1); -- AGI:3

-- Ninja Chainmail
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 9 WHERE `itemId` = 13782 AND `modId` = 10;  -- VIT+3 to DEX+3

-- Peregrine
UPDATE `item_equipment` SET `changes_tag` = 1, `level` = 40 WHERE `itemId` = 16887 AND `level` = 42;  -- Lvl:42 to Lvl:40
 -- UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 10 WHERE `itemId` = 16887 AND `modId` = 11;  -- AGI+1 to VIT+1

-- Drachen Brais
INSERT INTO `item_mods` VALUES (14227,25,3,1); -- ACC:3

-- Drachen Mail
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 8, `value` = 3 WHERE `itemId` = 12649 AND `modId` = 10;  -- VIT+3 to STR+3
INSERT INTO `item_mods` VALUES (12649,23,3,1); -- ATT:3

-- Drachen Armet
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 9, `value` = 2 WHERE `itemId` = 12519 AND `modId` = 13;  -- MND+5 to DEX+2

-- Kukulcans Staff
UPDATE `item_equipment` SET `changes_tag` = 1, `level` = 40 WHERE `itemId` = 17532 AND `level` = 41;  -- Lvl:41 to Lvl:40
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 30  WHERE `itemId` = 17532 AND `modId` = 5 AND `value` = 20;  -- MP+10 to MP+20
INSERT INTO `item_mods` VALUES (17532,71,3,1); -- MPHEAL:3

-- Evokers Pigaches
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 20  WHERE `itemId` = 14103 AND `modId` = 5 AND `value` = 15;  -- MP+10 to MP+20

-- Evokers Doublet
INSERT INTO `item_mods` VALUES (12650,357,-1,1); -- BP_DELAY: -1

-- Thiefs Knife
DELETE FROM `item_mods` WHERE itemid = 16480 AND `modId` = 303; -- Remove TREASURE_HUNTER: 1
-- INSERT INTO `item_mods` VALUES (16480,506,???,1); -- EXTRA_DMG_CHANCE: ???
INSERT INTO `item_mods` VALUES (16480,507,200,1); -- OCC_DO_EXTRA_DMG: 2X
UPDATE `item_weapon` SET `changes_tag` = 1, `dmg` = 18 WHERE `itemId` = 16480;

