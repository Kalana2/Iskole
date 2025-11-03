<?php
class UserModel
{
    protected $pdo;
    private $userTable = 'user';
    private $userAddressTable = 'userAddresses';
    private $userNameTable = 'userName'; // fName, lName

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->userTable} WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->userTable}        WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    // gender, email, phone, createDate, role, active, dateOfBirth, password, pwdChanged
    public function createUser($data)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO {$this->userTable} (gender, email, phone, createDate, role, active, dateOfBirth, password, pwdChanged) VALUES (:gender, :email, :phone, :createDate, :role, :active, :dateOfBirth, :password, :pwdChanged)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'gender' => $data['gender'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'createDate' => $data['createDate'],
                'role' => $data['role'],
                'active' => $data['active'],
                'dateOfBirth' => $data['dateOfBirth'],
                'password' => $data['password'],
                'pwdChanged' => $data['pwdChanged']
            ]);
            $userId = $this->pdo->lastInsertId();

            $sql = "INSERT INTO {$this->userAddressTable} (userId, address_line1, address_line2, address_line3) VALUES (:userId, :address_line1, :address_line2, :address_line3)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'address_line1' => $data['address_line1'],
                'address_line2' => $data['address_line2'],
                'address_line3' => $data['address_line3']
            ]);

            $sql = "INSERT INTO {$this->userNameTable} (userId, firstName, lastName) VALUES (:userId, :firstName, :lastName)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'firstName' => $data['fName'],
                'lastName' => $data['lName']
            ]);

            $this->pdo->commit();

            return $userId;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error Processing Request: " . $e->getMessage());
        }
    }

}