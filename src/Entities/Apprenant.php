<?php
namespace App\Entities;
class Apprenant{
private string $nom;
private int $id;
private string $prenom;
private string $email;
private string $password;
public function __construct(string $nom ,string $prenom,string $email,string $password ,int $id){
    $this->nom=$nom;
    $this->prenom=$prenom;
    $this->email=$email;
    $this->password=$password;
    $this->id=$id;
}


public function getnom() :string{
    return  $this->nom ;
} 
public function setnom( string $nom){
     $this->nom=$nom ;
}
public function getprenom(){
   return $this->prenom ;
}
public function setprenom( string $prenom){
      $this->prenom=$prenom ;

} 

public function getemail() :string{
   return $this->email ;
}
public function setemail(string $email){
     $this->email  = $email;
}
public function getpassword() :string{
   return $this->password ;
}
public function setpassword($password){
   $this->password =$password;
}

public function getId(): int
{
    return $this->id;
}


}


?>