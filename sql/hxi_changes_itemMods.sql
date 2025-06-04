-- HXI specific Item Modifiers consolidated

LOCK TABLE `item_mods` WRITE;

-- Koenig Schaller
UPDATE `item_mods` SET `changes_tag` = 1, `modId` = 11 WHERE `itemId` = 12421 AND `modId` = 14 AND `value` = 10; -- CHR+10 to AGI+10

-- Adaman Celata 
UPDATE `item_mods` SET `changes_tag` = 1, `value` = 8 WHERE `itemId` = 12429 AND `modId` = 25;  -- ACC+5 to ACC+8  

-- Abyssal Earring 
INSERT INTO `item_mods` VALUES (14741,83,5,1); -- GSWORD: 5

-- Beastly Earring
INSERT INTO `item_mods` VALUES (14742,83,5,1); -- POLEARM: 5
INSERT INTO `item_mods` VALUES (14742,991,10,1);  -- PET_ACC_EVA: 10

-- Chaos sollerets +1
INSERT INTO `item_mods` VALUES (15359,25,3,1);   -- ACC: 3

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
-- Can be equipped at Lv. 40 instead of Lv. 41

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
-- Can be equipped at Lv. 40 instead of Lv. 41

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
-- Replaced
INSERT INTO `item_mods` VALUES (50021,25,3,1);   -- ACC: 3
INSERT INTO `item_mods` VALUES (50021,303,1,1); -- TREASURE_HUNTER: 1

UNLOCK TABLES;