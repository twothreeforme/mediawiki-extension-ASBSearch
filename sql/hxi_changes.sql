-- ---------------------------------------------------------------------------
--  Notes: Horizon XI Wiki Changes
-- ---------------------------------------------------------------------------

-- Table: mob_droplist
-- Format: (dropId,dropType,groupId,groupRate,itemId,itemRate)

-- Table: hxi_bcnm_crate_list
-- Format: (bcnmId,groupId,groupRate,itemId,itemRate)
  
-- ---------------------------------------------------------------------------

	
--LOCK TABLE `mob_droplist` WRITE;
--ALTER TABLE `mob_droplist`
--    ADD COLUMN IF NOT EXISTS `content_tag` varchar(14) DEFAULT NULL AFTER `allegiance`;


LOCK TABLE `hxi_bcnm_crate_list` WRITE;	
ALTER TABLE `hxi_bcnm_crate_list`
    ADD COLUMN IF NOT EXISTS `changes_tag` tinyint(3) unsigned NOT NULL DEFAULT '0' AFTER `itemRate`;
	
UPDATE hxi_bcnm_crate_list SET changes_tag='1', itemId='18852' WHERE itemId='17440' AND bcnmId='79'; -- Replace Kraken Club(17440) with Octave Club(18852)

UNLOCK TABLES;
