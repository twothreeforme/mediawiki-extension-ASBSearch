<?php

class ExclusionsHelper {

    public static function zoneIsOOE($x){
        if ( gettype($x) == 'string' || gettype($x) == 'integer' ){ 
            foreach( ExclusionsHelper::$zones as $v) { 
                if ( $x == $v ) { 
                    //print_r($x . " found OOE"); 
                    return true; } 
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

        //Dynamis Divergence
        'Dynamis-Bastok [D]',
        'Dynamis-San dOria [D]',
        'Dynamis-Windurst [D]',
        'Dynamis-Jeuno [D]',
        
        //
        'Upper Jeuno',
        'Lower Jeuno',
        'Port Jeuno',
        'Rulude Gardens',
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
        'Port Windurst', 
        'Heavens Tower',
        'Kazham',
        'Windurst-Jeuno Airship',
        'San dOria-Jeuno Airship', 
        'Bastok-Jeuno Airship', 
        'Kazham-Jeuno Airship', 

        //Random ones
        'unknown',
        '49',
        '286',
        'GM Home',
        'Chocobo Circuit'
    ];

    public static $items = array(
        'King Behemoth' => 'Defending Ring'
    );
}

?>