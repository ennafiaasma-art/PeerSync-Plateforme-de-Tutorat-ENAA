<?php

namespace App\Entities;

use App\enums\Status;
use Exception;
use App\Entities\Apprenant;

class HelpRequest
{
    public function __construct(
        public string $titre,
        public string $description,
        public string $technologie,
        public int $apprenantId,
        public ?int $tuteurId = null,
        public ?string $commentaire = null,
        public Status $status = Status::EN_ATTENTE
    ) {}

 
    public function assignTo(Apprenant $tutor): void
    {
        if ($this->status === Status::RESOLUE) {
            throw new Exception("Impossible d'assigner une demande résolue.");
        }

        if ($this->status === Status::ASSIGNE) {
            throw new Exception("Cette demande est déjà assignée.");
        }

        if ($this->apprenantId === $tutor->getId()) {
            throw new Exception("Un utilisateur ne peut pas s'assigner sa propre demande.");
        }

$this->tuteurId = $tutor->getId();
        $this->status = Status::ASSIGNE;
    }

public function resolve($commentaire)
{
    $this->status = Status::RESOLUE;
    $this->commentaire = $commentaire;
}
}