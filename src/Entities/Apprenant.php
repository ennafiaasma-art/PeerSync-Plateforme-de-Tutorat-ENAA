<?php
class Apprenant{
private string $nom;
private string $prenom;
private string $email;
private string $password;
public function __construct(string $nom ,string $prenom,string $email,string $password){
    $this->nom=$nom;
    $this->prenom=$prenom;
    $this->email=$email;
    $this->password=$password;
}


}


?>