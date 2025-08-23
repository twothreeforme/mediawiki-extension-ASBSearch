<?php

class ExclusionsHelper {

    public static function zoneIsOOE($x){
        if ( gettype($x) == 'string' || gettype($x) == 'integer' ){ 
            foreach( ExclusionsHelper::$zones as $v) {
               //print_r($v);
                if ( $x == $v) { 
                    //print_r($x . " found OOE"); 
                    return true; } 
            }
        }
        return false;
    }

    public static function zoneIsTown($t){
        if ( gettype($t) == 'string' || gettype($t) == 'integer' ){
            foreach( ExclusionsHelper::$towns as $z) {
                if ( $t == $z) {
                    return true; }
            }
        }
        return false;
    }

    public static function mobIsOOE($m){
        if ( substr($m, -2) == "_G" ) return true;  // Garrison Mobs
        foreach( ExclusionsHelper::$mobs as $mob) {
            if ( ucwords($mob) == ucwords($m)) return true;
        }
        return false;
    }

    public static function itemIsOOE($i): bool{
        $i = ParserHelper::replaceUnderscores($i);

        foreach( ExclusionsHelper::$items as $item) {
            if ( strtolower($item) == strtolower($i)) 
                return true;
        }
        foreach( ExclusionsHelper::$itemSets as $item) {
            if (  str_contains(strtolower($i), strtolower($item) ) ) 
                return true;
        } 
        return false;
    }

    public static function zoneIsBCNM($zone){
        if ( gettype($zone) == 'string' || gettype($zone) == 'integer' ){ 
            $zone = ParserHelper::replaceUnderscores($zone);

            foreach( ExclusionsHelper::$bcnmZones as $k => $v) {
                //$k = ucwords($k);
                $v = ucwords($v);
                // print_r($k, $v);
                $zone = ucwords($zone);
                //print_r($v);
                if ( str_contains($v, $zone)) return true;  // str_contains($k, $zone) || 
            }
        }
        return false;
    }

    public static $zones = [

        //ToUA
        'Aht Urhgan Whitegate',
        'Al Zahbi',
        'Nashmau',
        'Aydeewa Subterrane',
        'Jade Sepulcher',
        'Mamook',
        'Mamool Ja Training Grounds',
        'Wajaom Woodlands',
        'Arrapago Reef',
        'Caedarva Mire',
        'Hazhalm Testing Grounds',
        'Illrusi Atoll',
        'Leujaoam Sanctum',
        'Periqia',
        'Talacca Cove',
        'The Ashu Talif',
        'Alzadaal Undersea Ruins',
        'Nyzul Isle',
        'Silver Sea Remnants',
        'Arrapago Remnants',
        'Bhaflau Remnants',
        'Zhayolm Remnants',
        'Halvung',
        'Lebros Cavern',
        'Mount Zhayolm',
        'Navukgo Execution Chamber',
        'Bhaflau Thickets',
        'The Colosseum',
        'Open sea route to Al Zahbi',
        'Open sea route to Mhaura',
        'Silver Sea route to Al Zahbi',
        'Silver Sea route to Nashmau',
        'Ilrusi Atoll',

        //Wings of the Goddess
        'Castle Oztroja (S)',
        'Garlaige Citadel (S)',
        'Meriphataud Mountains (S)',
        'Sauromugue Champaign (S)',
        'Beadeaux (S)',
        'Crawlers Nest (S)',
        'Pashhow Marshlands (S)',
        'Rolanberry Fields (S)',
        'Vunkerl Inlet (S)',
        'Beaucedine Glacier (S)',
        'Bastok Markets (S)',
        'Grauberg (S)',
        'North Gustaberg (S)',
        'Ruhotz Silvermines',
        'Batallia Downs (S)',
        'The Eldieme Necropolis (S)',
        'La Vaule (S)',
        'Jugner Forest (S)',
        'East Ronfaure (S)',
        'Everbloom Hollow',
        'Southern San dOria (S)',
        'Fort Karugo-Narugo (S)',
        'Ghoyus Reverie',
        'Windurst Waters (S)',
        'West Sarutabaruta (S)',
        'Provenance',
        'Xarcabard (S)',
        'Castle Zvahl Baileys (S)',
        'Castle Zvahl Keep (S)',
        'Throne Room (S)',

        //Abyssea
        'Abyssea-Konschtat',
        'Abyssea-La Theine',
        'Abyssea-Tahrongi',
        'Abyssea-Attohwa',
        'Abyssea-Misareaux',
        'Abyssea-Vunkerl',
        'Abyssea-Altepa',
        'Abyssea-Grauberg',
        'Abyssea-Uleguerand',
        'Abyssea-Empyreal Paradox',

        //Additional Zones (unorganized)
        'Feretory',
        'Marquette Abdhaljs-Legion',

        //Wings of the Goddess
        'Walk of Echoes',
        'Walk of Echoes [P1]',
        'Walk of Echoes [P2]',

        //Seekers of Adoulin
        'Eastern Adoulin',
        'Western Adoulin',
        'Celennia Memorial Library',
        'Mog Garden',
        'Rala Waterways',
        'Ceizak Battlegrounds',
        'Cirdas Caverns',
        'Cirdas Caverns U',
        'Dho Gates',
        'Foret de Hennetiel',
        'Kamihr Drifts',
        'Leafallia',
        'Moh Gates',
        'Morimar Basalt Fields',
        'Marjami Ravine',
        'Rala Waterways U',
        'Sih Gates',
        'Yahse Hunting Grounds',
        'Woh Gates',
        'Yorcia Weald',
        'Yorcia Weald U',
        'Outer RaKaznar',
        'RaKaznar Inner Court',
        'Outer RaKaznar [U1]',
        'Outer RaKaznar [U2]',
        'Outer RaKaznar [U3]',
        'Silver Knife',
        'Desuetia - Empyreal Paradox',
        'Escha RuAun',
        'Escha ZiTah',
        'Mount Kamihr',
        'RaKaznar Turris',
        'Reisenjima Henge',
        'Reisenjima Sanctorium',
        'Reisenjima',

        //Dynamis Divergence
        'Dynamis-Bastok [D]',
        'Dynamis-San dOria [D]',
        'Dynamis-Windurst [D]',
        'Dynamis-Jeuno [D]',
        

        // Avatar Battlefields
        'Cloister of Flames',
        'Cloister of Frost',
        'Cloister of Gales',
        'Cloister of Storms',
        'Cloister of Tides',
        'Cloister of Tremors',

        //PVP
        'Desuetia Empyreal Paradox',
        'Diorama Abdhaljs-Ghelsba',
        'Abdhaljs Isle-Purgonorgo',

        //Random ones
        'unknown',
        '49',
        '286',
        'GM Home',
        'Chocobo Circuit',
        'Mordion Gaol',
        'Maquette Abdhaljs-Legion A',
        'Maquette Abdhaljs-Legion B',
        'Residential Area',
        'Stellar Fulcrum', //May add this back in later
        'Throne Room [V]',


        //Mission / No-Mobs / No drops
        'Empyreal Paradox',
        'Gwora-Throne Room',
        'Gwora-Corridor',
        'Hall of Transference',
        'Hall of the Gods',
        'LaLoff Amphitheater',
        'Sealions Den'

    ];

    public static $towns = [
        // Towns
        'Upper Jeuno',
        'Lower Jeuno',
        'Port Jeuno',
        'RuLude Gardens',
        'Northern San dOria',
        'Southern San dOria',
        'Port San dOria',
        'Chateau dOraguille',
        'Bastok Markets',
        'Bastok Mines',
        'Port Bastok',
        'Metalworks',
        'Windurst Woods',
        'Windurst Walls',
        'Windurst Waters',
        'Port Windurst',
        'Heavens Tower',
        'Kazham',
        'Selbina',
        'Rabao',
        'Mhaura',
        'Norg',
        'Tavnazian Safehold',
        'Windurst - Jeuno Airship',
        'San dOria - Jeuno Airship',
        'Bastok - Jeuno Airship',
        'Kazham - Jeuno Airship',
        'Ship bound for Mhaura',
        'Ship bound for Selbina'
    ];

    public static $items = [
        // BCNM OOE TOAU Grips
         'Claymore Grip', 
         'Pole Grip',
         'Sword Strap',
         'Spear Strap',
         'Staff Strap',

        // WOTG NM Grips
         'Orca Strap',
         'Shark Strap',

        // ZMN Grips
         'Dark Grip',
         'Earth Grip',
         'Fire Grip',
         'Ice Grip',
         'Light Grip',
         'Thunder Grip',
         'Water Grip',
         'Wind Grip',
         'Magic Strap',

        // Abyssea NM Grips
         'Amicus Grip',
         'Pax Grip',
         'Fulcio Grip',
         'Caecus Grip',
         'Vox Grip',
         'Elementa Grip',
         'Vallus Grip',
         'Curatio Grip',
         'Macero Grip',
         'Quire Grip',
         'Uthers Grip',
         'Danger Grip',
         'Divinus Grip',

        // Post WoTG   Pre Abyssea 
         'Disciple Grip',
         'Succubus Grip',
         'Tenax Strap',

        // Other OOE Items
         'Trump Crown',

        // Dynamis items
        'Vial of Cagebeast Blood', 'Vial of Sea Monk Venom', 'Perforated Wing', 'Undying Moiety',
        'Shadescale Femur', 'Shadescale Skull', 'Shadescale Talon', 'Shadescale Heart',
        'Forgotten Step', 'Forgotten Touch', 'Forgotten Journey', 'Forgotten Thought', 'Forgotten Hope',
        'Heralds Juju', 'Undying Juju', 'Revelatory Juju', 'Creepers Juju',
        'Redolent Root', 'Clump of Absorbent Moss', 'Odorless Fungus'
    ];

    public static $itemSets = [
        'Mirage', 'Pantin', 'Commodore', 'Argute', 'Etoile',
        'Fiendish', 'Bijou'
    ];

    public static $mobs = array(

        // Voidwalker
        'Meanderer', 'Meditator', 'Hungerer', 'Ateuvhi', 'Vuzdei', 'Mighrah',
        'Capricornus', 'Yacumama', 'Lamprey Lord', 'Shoggoth', 'Jyeshtha', 'Farruca Fly', 'Skuld', 'Urd', 'Erebus', 'Feuerunke', 'Chesma', 'Tammuz',
        'Krabkatoa', 'Blobdingnag', 'Orcus', 'Verthandi', 'Lord Ruthven', 'Dawon',
        'Yilbegan', 'Provenance Watcher', 'Slime','She-Slime', 'Metal_Slime', 'Quagmire_Pugil', 'Sunderclaw','Void_Hare', 'Prickly_Sheep', 'Ig-Alima',

        //Garrison
        'Orcish_Cursemaker', 'Orcish_Serjeant', 'Orcish_Fighter', 'Orcish_Chasseur', 'Orcish_Fighterchief', 'Orcish_Fighter_G','Orcish_Serjeant_G','Orcish_Cursemaker_G','Orcish_Colonel',
        'Goblin_Swordmaker', 'Goblin_Leecher_G', 'Goblin_Gaoler', 'Goblin_Gambler_G', 'Goblin_Swindler',


        // Voidwatch 
        'Abununnu', 'Aello', 'Agathos', 'Akupara', 'Akvan', 'Belphoebe', 'Bhishani', 'Bismarck', 'Botulus_Rex', 'Brekekekex', 'Celaeno', 
        'Cath_Palug', 'Cherufe', 'Cottus', 'Dimgruzub', 'Fjalar', 'Gasha', 'Gaunab', 'Giltine', 'Goji', 'Gugalanna', 'Gwynn_Ap_Nudd', 'Hahava', 
        'Holy_Moly', 'Ildebrann', 'Ig-Alima', 'Isarukitsck', 'Kaggen', 'Kalasutrax', 'Kholomodumo', 'Krabimanjaro', 'Laidly_Laurence', 'Lancing_Lamorak', 
        'Lorbulcrud', 'Lord_Asag', 'Malleator_Maurok', 'Melancholic_Moira', 'Mellonia', 'Mimic_King', 'Modron', 'Morta', 'Murk-Veined_Baneberry', 
        'Neith', 'Nympha_Eunomia', 'Ocythoe', 'Ogbunabali', 'Pancimanci', 'Pil', 'Qilin', 'Roly-Poly', 'Rw_Nw_Prt_M_Hrw', 'Sabotender_Campeador', 'Sallow_Seymour', 'Sarimanok', 'Smierc', 'Stachysaurus', 
        'Tangaroa', 'Taweret', 'Tsui-Goab', 'Uptala', 'Ushumgal', 'Vanasaarvik', 'Virvatuli', 'Voidwrought', 'Yalungur', 'Yatagarasu',
        
        // Unity
        'Bounding_Belinda', 'Hugemaw_Harold', 'Prickly_Pitriv', 'Ironhorn_Baldurno', 'Sleepy_Mabel', 'Serpopard_Ninlil', 'Abyssdiver', 
        'Immanibugard', 'Intuila', 'Jester_Malatrix', 'Orcfeltrap', 'Sybaritic_Samantha', 'Valkurm_Imperator', 'Cactrot_Veloz', 'Emperor_Arthro', 
        'Garbage_Gel', 'Joyous_Green', 'Keeper_of_Heiligtum', 'Tiyanak', 'Voso', 'Warblade_Beak', 'Woodland_Mender', 'Arke', 'Ayapec', 
        'Azure-toothed_Clawberry', 'Bakunawa', 'Beist', 'Centurio_XX-I', 'Coca', 'Douma_Weapon', 'King_Uropygid', 'Kubool_Jas_Mhuufya', 
        'Largantua', 'Lumber_Jill', 'Mephitas', 'Muut', 'Specter_Worm', 'Strix', 'Vermillion_Fishfly', 'Azrael', 'Borealis_Shadow', 
        'Camahueto', 'Carousing_Celine', 'Grand_Grenade', 'Vedrfolnir', 'Vidmapire', 'Volatile_Cluster', 'Glazemane', 'Wyvernhunter_Bambrox', 'Hidhaegg',
        
        // Ukn
        'Otherworldly_Rimester', 'Blue_Bascinet', 'Pixie', 'Gougetooth_Ganzaga', 'Wayward_Worm', 'Tatenashi_Armor', 'Hizamaru_Armor', 'Ubuginu_Armor', 'Hachiryu_Armor', 'Omodaka_Armor', 
        'Bull_[Herd1]', 'Cow_[Herd1]', 'Calf_[Herd1]', 'Bull_[Herd2]', 'Cow_[Herd2]', 'Calf_[Herd2]', 'Bull_[Herd3]', 'Cow_[Herd3]', 'Calf_[Herd3]',
        'Pyracmon', 'Wraith_Bat', 'Spriggan_Sifter',  'Spriggan_Crier', 'Spriggan_Graverobber', 'Astral_Box',

    );

    public static $bcnmZones = array(
        // zoneName => BC name
        'Giddeus' => 'Balgas Dais',
        'Uleguerand Range' => 'Bearclaw Pinnacle',
        'Attohwa Chasm'   => 'Boneyard Gully',
        'Quicksand Caves' => 'Chamber of Oracles',
        'Ghelsba_Outpost' => 'Ghelsba Outpost',
        'Yughott Grotto' => 'Horlais Peak',
        'Newton Movalpolos' => 'Mine Shaft 2716',
        'Riverne - Site A01' => 'Monarch Linn',
        'FeiYin' => 'QuBia Arena',
        'Den of Rancor' => 'Sacrificial Chamber',
        'PsoXja' => 'The Shrouded Maw',
        'Palborough Mines' => 'Waughroon Shrine',
        'Kindred Spirits' => 'Throne Room',
        'Spire of Dem' => 'Spire of Dem',
        'Spire of Holla' => 'Spire of Holla',
        'Spire of Mea' => 'Spire of Mea',
        'Spire of Vahzl' => 'Spire of Vahzl',

    );

    public static $diggingRelevantZones = array(
        102 => 'La_Theine_Plateau',
        103 => 'Valkurm_Dunes',
        104 => 'Jugner_Forest',
        108 => 'Konschtat_Highlands',
        2 => 'Carpenters_Landing',
        105 => 'Batallia_Downs',
        109 => 'Pashhow_Marshlands',
        110 => 'Rolanberry_Fields',
        120 => 'Sauromugue_Champaign',
        119 => 'Meriphitaud_Mountains',
        121 => 'The_Sanctuary_of_ZiTah',
        117 => 'Tahrongi_Canyon',
        118 => 'Buburimu_Peninsula',
        51 => 'Wajaom_Woodlands',
        52 => 'Bhaflau_Thickets'
    );

    public static $weatherZones = array(
        37 => 'Temenos',
        38 => 'Apollyon',
        39 => 'Dynamis-Valkurm',
        40 => 'Dynamis-Buburimu',
        41 => 'Dynamis-Qufim',
        42 => 'Dynamis-Tavnazia',
        134 => 'Dynamis-Beaucedine',
        135 => 'Dynamis-Xarcabard',
        152 => 'Altar_Room',
        168 => 'Chamber_of_Oracles',
        185 => 'Dynamis-San_dOria',
        186 => 'Dynamis-Bastok',
        187 => 'Dynamis-Windurst',
        188 => 'Dynamis-Jeuno',

    );

}

?>