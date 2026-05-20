<?php    
class Avis{
 private string $commentaire ;
 private int $note;
 private  DateTime $date;
 private int $id_aide ;

 public function __construct( string $commentaire , int $note ,DateTime $date, int $id_aide){
     $this->commentaire=$commentaire;
     $this->note=$note;
     $this->date=$date;
     $this->id_aide=$id_aide;
 }

 public function getCommentaire():string{
    return $this->commentaire;
 }
 public function getNote():int{
return $this->note;

 }public function getDate():DateTime{
    return $this->date;
 } public function getId_aide() :int {
    return $this->id_aide;
 }



 public function setCommentaire(string $commentaire){
     $this->commentaire=$commentaire;

 } public function setNote($note){
     $this->note=$note;

 }
 public function setDate(DateTime $date){
     $this->date=$date;
 }
 public function setId_aide(int $id_aide){
     $this->id_aide=$id_aide;
 }


}


?>