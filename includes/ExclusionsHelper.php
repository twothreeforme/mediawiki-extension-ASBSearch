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

    public static function mobIsOOE($m){
        if ( substr($m, -2) == "_G" ) return true;  // Garrison Mobs
        foreach( ExclusionsHelper::$mobs as $mob) {
            if ( ucwords($mob) == ucwords($m)) return true;
        }
        return false;
    }

    public static function itemIsOOE($i){
        foreach( ExclusionsHelper::$items as $item) {
            if ( ucwords($item) == ucwords($i)) return true;
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
        'Windurst-Jeuno Airship',
        'San dOria-Jeuno Airship', 
        'Bastok-Jeuno Airship', 
        'Kazham-Jeuno Airship', 
        'Ship bound for Mhaura', 
        'Ship bound for Selbina',

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
         'Tenax Strap'

    ];

    public static $mobs = array(
        'Pixie'
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
        'Palborough Mines' => 'Waughroon Shrine'
    );
    
}

?>