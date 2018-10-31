<?php

namespace Application\PlateformeBundle\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class Text
{
    public function slugify($text){
        return strtr($text, 'áàâäãåçéèêëíìîïñóòôöõúùûüýÿ', 'aaaaaaceeeeiiiinooooouuuuyy');
    }
}