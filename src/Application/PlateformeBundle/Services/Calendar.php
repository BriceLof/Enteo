<?php

namespace Application\PlateformeBundle\Services;

class Calendar
{
    /**
     * retourne un tableau de nom des couleurs de google calendar
     *
     * @return array
     */
    public function getColorName($codeColor)
    {
        $color = array(
            '%23B1365F' => '#b1365f',
            '%235C1158' => 'Fuchsia',
            '%23711616' => 'Red',
            '%23691426' => 'Crimson',
            '%23BE6D00' => 'Orange',
            '%23B1440E' => 'Orange Red',
            '%23853104' => 'Red Orange',
            '%238C500B' => 'Burnt Orange' ,
            '%23754916' => 'Brown Orange',
            '%2388880E' => '#bfbf4d',
            '%23AB8B00' => 'Goldenrod',
            '%23856508' => 'Darker Goldenrod',
            '%2328754E' => 'Green',
            '%231B887A' => 'Lighter Green',
            '%230D7813' => 'Forest Green',
            '%23528800' => 'Olive Green',
            '%23125A12' => 'Jungle Green',
            '%232F6309' => 'Another Olive',
            '%232F6213' => 'Another Green',
            '%230F4B38' => 'Sea Green',
            '%235F6B02' => 'Golden Olive',
            '%234A716C' => 'Green Gray',
            '%236E6E41' => 'Olive Gray',
            '%2329527A' => 'Dull Navy',
            '%232952A3' => 'Standard Blue',
            '%234E5D6C' => 'Blue Gray',
            '%235A6986' => 'Blue Steel',
            '%23182C57' => 'Another blue',
            '%23060D5E' => 'Dark Blue',
            '%23113F47' => 'Sea Blue',
            '%237A367A' => 'Violet',
            '%235229A3' => '#5229a3',
            '%23865A5A' => 'Purple Gray',
            '%23705770' => 'Purple Brown',
            '%2323164E' => 'Deep Purple',
            '%235B123B' => 'Magenta',
            '%2342104A' => 'Another Purple',
            '%23875509' => 'Yellow Brown',
            '%238D6F47' => 'Brown',
            '%236B3304' => 'Nice Brown',
            '%23333333' => 'Black',
        );

        return $color[$codeColor];
    }

    public function getColor(){
        $color = array(
            '%23B1365F',
            '%2388880E',
            '%235229A3',
            '%230F4B38',
            '%23856508',
            '%235C1158',
            '%23711616',
            '%23691426',
            '%23BE6D00',
            '%23B1440E',
            '%23853104',
            '%238C500B',
            '%23754916',
            '%23AB8B00',
            '%2328754E',
            '%231B887A',
            '%2328754E',
            '%23528800',
            '%23125A12',
            '%232F6213',
            '%234E5D6C',
            '%235F6B02',
            '%234A716C',
            '%236E6E41',
            '%230D7813',
            '%2329527A',
            '%232F6309',
            '%232952A3',
            '%235A6986',
            '%23182C57',
            '%23060D5E',
            '%23113F47',
            '%237A367A',
            '%235229A3',
            '%23865A5A',
            '%23705770',
            '%2323164E',
            '%235B123B',
            '%2342104A',
            '%23875509',
            '%238D6F47',
            '%236B3304',
            '%23333333',
        );

        return $color;
    }
}