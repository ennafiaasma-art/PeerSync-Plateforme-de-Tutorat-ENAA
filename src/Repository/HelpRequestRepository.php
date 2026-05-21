<?php

namespace App\Entities;

use App\enums\Status;

class HelpRequest
{
    public function __construct(
        public string $titre,
        public string $description,
        public string $technologie,
        public int $apprenantId,
        public ?int $tuteurId = null,
        public Status $status = Status::EN_ATTENTE
    ) {}
}