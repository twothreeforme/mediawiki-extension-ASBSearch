<?php

class FFXIPH_StatsUtils {

    public static function getTraits( $mlvl, $slvl, $mjob, $sjob ){
        $db = new DatabaseQueryWrapper();

        $results = $db->getTraits( $mlvl, $slvl, $mjob, $sjob );

        $traits = [];
        foreach ( $results as $row ) {

            if ( !isset($traits[$row->modid]) ) $traits[$row->modid] = $row->value;
            else if ( $traits[$row->modid] < $row->value ) $traits[$row->modid] = $row->value;
        }

        return $traits;
    }

}


?>