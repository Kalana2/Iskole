<?php
class UserModel
{
    protected $pdo;
    protected $userTable = 'user';
    protected $userAddressTable = 'userAddress';
    protected $userNameTable = 'userName'; // fName, lName

    protected $userRoleMap = ['admin' => 0, 'mp' => 1, 'teacher' => 2, 'student' => 3, 'parent' => 4];

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
        $stmt = $this->pdo->prepare("SELECT {$this->userTable}.*, {$this->userNameTable}.firstName, {$this->userNameTable}.lastName FROM {$this->userTable} 
        LEFT JOIN {$this->userNameTable} 
        ON {$this->userTable}.userID = {$this->userNameTable}.userID   
        WHERE {$this->userTable}.email = :email AND {$this->userTable}.active = 1");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        return $data;
    }

    public function getUserByEmailWithClassID($email)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            {$this->userTable}.*,
            {$this->userNameTable}.firstName,
            {$this->userNameTable}.lastName,
            t.classID AS classID
        FROM {$this->userTable}
        LEFT JOIN {$this->userNameTable}
            ON {$this->userTable}.userID = {$this->userNameTable}.userID
        LEFT JOIN teachers t
            ON t.userID = {$this->userTable}.userID
        WHERE {$this->userTable}.email = :email
          AND {$this->userTable}.active = 1
        LIMIT 1
    ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }


    public function getUserById($userId)
    {
        $sql = "SELECT {$this->userTable}.*, {$this->userNameTable}.firstName, {$this->userNameTable}.lastName 
                FROM {$this->userTable} 
                LEFT JOIN {$this->userNameTable} 
                ON {$this->userTable}.userID = {$this->userNameTable}.userID   
                WHERE {$this->userTable}.userID = :userId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // gender, email, phone, createDate, role, active, dateOfBirth, password, pwdChanged
    public function createUser($data)
    {
        try {
            // Do NOT begin/commit here; outer model should manage transaction
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

            $sql = "INSERT INTO {$this->userAddressTable} (userID, address_line1, address_line2, address_line3) VALUES (:userId, :address_line1, :address_line2, :address_line3)";
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

            return $userId;
        } catch (PDOException $e) {
            throw new Exception("Error Processing Request: " . $e->getMessage());
        }
    }

    public function getRecentUsers($count = 5)
    {
        // ensure $count is an integer and has a sensible default
        $count = (int) $count;
        if ($count <= 0) {
            $count = 5;
        }

        // order by userID descending to get the latest users; bind LIMIT as integer
        $sql = "SELECT {$this->userTable}.*, {$this->userNameTable}.firstName, {$this->userNameTable}.lastName
        FROM {$this->userTable}
        LEFT JOIN {$this->userNameTable}
        ON {$this->userTable}.userID = {$this->userNameTable}.userID
        WHERE {$this->userTable}.active = 1
        ORDER BY {$this->userTable}.userID DESC
        LIMIT :count";

        $stmt = $this->pdo->prepare($sql);
        // bind as integer to avoid being passed as a quoted string which breaks LIMIT
        $stmt->bindValue(':count', $count, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $sql = "SELECT {$this->userTable}.*, 
                {$this->userNameTable}.firstName, 
                {$this->userNameTable}.lastName,
                {$this->userAddressTable}.address_line1,
                {$this->userAddressTable}.address_line2,
                {$this->userAddressTable}.address_line3,
                students.studentID
        FROM {$this->userTable}
        LEFT JOIN {$this->userNameTable}
        ON {$this->userTable}.userID = {$this->userNameTable}.userID
        LEFT JOIN {$this->userAddressTable}
        ON {$this->userTable}.userID = {$this->userAddressTable}.userID
        LEFT JOIN students
        ON {$this->userTable}.userID = students.userID
        WHERE {$this->userTable}.active = 1
        ORDER BY {$this->userTable}.userID DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserDetailsById($userId)
    {
        $sql = "SELECT {$this->userTable}.*, 
                {$this->userNameTable}.firstName, 
                {$this->userNameTable}.lastName,
                {$this->userAddressTable}.address_line1,
                {$this->userAddressTable}.address_line2,
                {$this->userAddressTable}.address_line3,
                students.studentID
        FROM {$this->userTable}
        LEFT JOIN {$this->userNameTable}
        ON {$this->userTable}.userID = {$this->userNameTable}.userID
        LEFT JOIN {$this->userAddressTable}
        ON {$this->userTable}.userID = {$this->userAddressTable}.userID
        LEFT JOIN students
        ON {$this->userTable}.userID = students.userID
        WHERE {$this->userTable}.userID = :userId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchUsers($query)
    {
        $searchTerm = '%' . $query . '%';

        $sql = "SELECT {$this->userTable}.*, 
                {$this->userNameTable}.firstName, 
                {$this->userNameTable}.lastName,
                {$this->userAddressTable}.address_line1,
                {$this->userAddressTable}.address_line2,
                {$this->userAddressTable}.address_line3,
                students.studentID
        FROM {$this->userTable}
        LEFT JOIN {$this->userNameTable}
        ON {$this->userTable}.userID = {$this->userNameTable}.userID
        LEFT JOIN {$this->userAddressTable}
        ON {$this->userTable}.userID = {$this->userAddressTable}.userID
        LEFT JOIN students
        ON {$this->userTable}.userID = students.userID
        WHERE {$this->userTable}.active = 1
        AND (
            {$this->userNameTable}.firstName LIKE :search
            OR {$this->userNameTable}.lastName LIKE :search
            OR {$this->userTable}.email LIKE :search
            OR students.studentID LIKE :search
        )
        ORDER BY {$this->userTable}.userID DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['search' => $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editUser($userId, $data)
    {
        // Validate and sanitize input data
        $firstName = htmlspecialchars(trim($data['firstName'] ?? ''));
        $lastName = htmlspecialchars(trim($data['lastName'] ?? ''));
        $email = htmlspecialchars(trim($data['email'] ?? ''));
        $phone = htmlspecialchars(trim($data['phone'] ?? ''));
        $gender = htmlspecialchars(trim($data['gender'] ?? ''));
        $dob = htmlspecialchars(trim($data['dateOfBirth'] ?? ''));
        $address1 = htmlspecialchars(trim($data['address_line1'] ?? ''));
        $address2 = htmlspecialchars(trim($data['address_line2'] ?? ''));
        $address3 = htmlspecialchars(trim($data['address_line3'] ?? ''));

        // Update user information in the database
        $sql = "UPDATE {$this->userTable} SET
                email = :email,
                phone = :phone,
                gender = :gender,
                dateOfBirth = :dob
                WHERE userID = :userId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'dob' => $dob,
            'userId' => $userId
        ]);

        // Update user name
        $sql = "UPDATE {$this->userNameTable} SET
                firstName = :firstName,
                lastName = :lastName
                WHERE userID = :userId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'userId' => $userId
        ]);

        // Update user address
        $sql = "UPDATE {$this->userAddressTable} SET
                address_line1 = :address1,
                address_line2 = :address2,
                address_line3 = :address3
                WHERE userID = :userId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'address1' => $address1,
            'address2' => $address2,
            'address3' => $address3,
            'userId' => $userId
        ]);

        return true;
    }

    public function deleteUser($userId)
    {
        try {
            // Soft delete: set active = 0 instead of actually deleting the record
            $sql = "UPDATE {$this->userTable} SET active = 0 WHERE userID = :userId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
        }
    }



    public function findStudentInClass(int $classId, string $q): ?array
    {
        $sql = "
        SELECT
            s.studentID,
            s.classID,
            s.gradeID,
            u.userID,
            u.email,
            u.phone,
            u.dateOfBirth,
            un.firstName,
            un.lastName
        FROM students s
        INNER JOIN user u ON u.userID = s.userID
        LEFT JOIN userName un ON un.userID = s.userID
        WHERE s.classID = :class_id
          AND (
                s.studentID = :exact
             OR u.email LIKE :like
             OR u.phone LIKE :like
             OR CONCAT(IFNULL(un.firstName,''),' ',IFNULL(un.lastName,'')) LIKE :like
             OR un.firstName LIKE :like
             OR un.lastName LIKE :like
          )
        LIMIT 1
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':class_id' => $classId,
            ':exact' => $q,
            ':like' => "%$q%",
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function updatePassword($userId, $hashedPassword)
    {
        try {
            $sql = "UPDATE {$this->userTable} SET password = :password, pwdChanged = :pwdChanged WHERE userID = :userId";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'password' => $hashedPassword,
                'pwdChanged' => date('Y-m-d H:i:s'),
                'userId' => $userId
            ]);

            return $result && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }

}
