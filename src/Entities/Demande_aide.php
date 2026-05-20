<?php 

class Demande_aide {

    private string $titre;
    private string $description;
    private string $technologie;
    private int $id_apprenant;
    private int $id_tuteur;

    public function __construct(
        string $titre,
        string $description,
        string $technologie,
        int $id_apprenant,
        int $id_tuteur
    ) {
        $this->titre = $titre;
        $this->description = $description;
        $this->technologie = $technologie;
        $this->id_apprenant = $id_apprenant;
        $this->id_tuteur = $id_tuteur;
    }

    // Getters

    public function getTitre(): string {
        return $this->titre;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getTechnologie(): string {
        return $this->technologie;
    }

    public function getIdApprenant(): int {
        return $this->id_apprenant;
    }

    public function getIdTuteur(): int {
        return $this->id_tuteur;
    }

    // Setters

    public function setTitre(string $titre){
        $this->titre = $titre;
    }

    public function setDescription(string $description){
        $this->description = $description;
    }

    public function setTechnologie(string $technologie){
        $this->technologie = $technologie;
    }

    public function setIdApprenant(int $id_apprenant){
        $this->id_apprenant = $id_apprenant;
    }

    public function setIdTuteur(int $id_tuteur) {
        $this->id_tuteur = $id_tuteur;
    }
}

?>