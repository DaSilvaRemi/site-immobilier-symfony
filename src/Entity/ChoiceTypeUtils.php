<?php

namespace App\Entity;

class ChoiceTypeUtils
{
    /**
     * @param array $choices
     * @return array
     */
    public static function getChoices(array $choices): array {
        $output = [];

        foreach ($choices as $k => $v){
            $output[$v] = $k;
        }

        return $output;
    }
}