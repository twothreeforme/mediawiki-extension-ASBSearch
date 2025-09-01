<?php


class FFXIPH_MathHelper {
    public function __construct() {
    }

    // https://stackoverflow.com/questions/17664565/does-a-clamp-number-function-exist-in-php
    public static function clamp($current, $min, $max) {
        return max($min, min($max, $current));
    }

}

?>
