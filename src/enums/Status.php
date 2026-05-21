<?php

namespace App\enums;

enum Status: string
{
    case EN_ATTENTE = 'EN_ATTENTE';
    case ASSIGNE = 'ASSIGNE';
    case RESOLUE = 'RESOLUE';
}
?>