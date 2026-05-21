<?php

class UserRepository {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM apprenants WHERE email = ?
        ");
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    
    public function findById(int $id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM apprenants WHERE id = ?
        ");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function create(array $data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO apprenants (nom, prenom, email, password)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['password']
        ]);
    }
}