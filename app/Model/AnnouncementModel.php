<?php
require_once __DIR__ . '/../Core/Database.php';

class AnnouncementModel
{
    private $conn;
    private $table = "announcement";

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function getConnectionStatus()
    {
        return $this->conn !== null;
    }

    public function addAnnouncement($data)
    {
        $sql = "INSERT INTO " . $this->table . " (title, content, published_by, role,admin,mp,teacher,parent,student) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$data['title'], $data['content'], $data['published_by'], $data['role'], $data['admin'], $data['mp'], $data['teacher'], $data['parent'], $data['student']]);
    }

    public function getAllAnnouncements()
    {
        $query = "SELECT a.*, ur.roleName
                  FROM announcement a
                  JOIN userRoles as ur ON a.role = ur.roleID
                  ORDER BY a.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateAnnouncement($announcement_id, $data)
    {
        $query = "UPDATE " . $this->table . " SET title = ?, content = ?, admin = ?, mp = ?, teacher = ?, parent = ?, student = ? WHERE announcement_id = ?";
        $stmt = $this->conn->prepare($query);

        // Convert audience to role flags
        $audiences = explode(',', $data['audience']);
        $admin = in_array('admin', $audiences) ? 1 : 0;
        $mp = in_array('mp', $audiences) ? 1 : 0;
        $teacher = in_array('teacher', $audiences) ? 1 : 0;
        $parent = in_array('parent', $audiences) ? 1 : 0;
        $student = in_array('student', $audiences) ? 1 : 0;

        // If audience is 'all', set all flags to 1
        if ($data['audience'] === 'all') {
            $admin = $mp = $teacher = $parent = $student = 1;
        }

        return $stmt->execute([
            $data['title'],
            $data['content'],
            $admin,
            $mp,
            $teacher,
            $parent,
            $student,
            $announcement_id
        ]);
    }

    public function deleteAnnouncement($announcement_id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE announcement_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$announcement_id]);
    }
}
