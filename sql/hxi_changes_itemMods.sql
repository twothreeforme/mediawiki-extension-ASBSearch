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


UNLOCK TABLES;