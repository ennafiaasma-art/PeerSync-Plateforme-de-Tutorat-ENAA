<?php

namespace App\Entities;

use Exception;

class Evaluation
{
    public function __construct(
        public int $note,
        public string $commentaire,
        public int $tuteurId,
        public int $apprenantId
    ) {

        
        if ($this->note < 1 || $this->note > 5) {
            throw new Exception("La note doit etre comprise entre 1 et 5.");
        }
    }
}