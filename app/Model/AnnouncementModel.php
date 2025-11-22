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
        // $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $query = "SELECT a.*, t.audienceName 
                  FROM announcement a
                  JOIN target_audience t 
                  ON a.audienceID = t.audienceID
                  ORDER BY a.created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAnnouncementById($announcement_id)
    {
        $sql = "SELECT a.*, t.audienceName 
                FROM announcement a
                JOIN target_audience t 
                ON a.audienceID = t.audienceID
                WHERE a.announcement_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . implode(":", $this->conn->errorInfo()));
        }
        $stmt->execute([$announcement_id]);
        $announcement = $stmt->fetch();
        return $announcement;
    }

    public function updateAnnouncement($announcement_id, $data)
    {
        $query = "UPDATE " . $this->table . " SET title = ?, content = ?, published_by = ?, role = ?, audienceID = ? WHERE announcement_id = ?";
        $stmt = $this->conn->prepare($query);
        $audienceID = isset($data['audienceID']) ? $data['audienceID'] : 0;
        return $stmt->execute([$data['title'], $data['content'], $data['published_by'], $data['role'], $audienceID, $announcement_id]);
    }

    public function deleteAnnouncement($announcement_id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE announcement_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$announcement_id]);
    }

    public function getAnnouncementByUserID($user_id)
    {
        $sql = "SELECT a.announcement_id,a.title, a.content, a.created_at, t.audienceName
                FROM announcement a
                JOIN target_audience t 
                ON a.audienceID = t.audienceID
                WHERE a.published_by = ?
                ORDER BY a.created_at DESC LIMIT 4";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . implode(":", $this->conn->errorInfo()));
        }
        $stmt->execute([$user_id]);
        $announcements = $stmt->fetchAll();
        return $announcements;
    }

    public function getAnnouncementsByAudienceIDs($audienceIDs)
    {
        if (empty($audienceIDs)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($audienceIDs), '?'));
        $sql = "SELECT a.*, t.audienceName 
                FROM announcement a
                JOIN target_audience t 
                ON a.audienceID = t.audienceID
                WHERE a.audienceID IN ($placeholders)
                ORDER BY a.created_at DESC LIMIT 5";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . implode(":", $this->conn->errorInfo()));
        }

        $stmt->execute($audienceIDs);
        $announcements = $stmt->fetchAll();
        return $announcements;
    }
}
