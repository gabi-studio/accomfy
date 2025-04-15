// User DB functions
<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($data) {
        $sql = "INSERT INTO users 
            (first_name, last_name, email, password_hash, phone_number, role, school, program, profile_photo, about)
            VALUES 
            (:first_name, :last_name, :email, :password_hash, :phone_number, :role, :school, :program, :profile_photo, :about)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
