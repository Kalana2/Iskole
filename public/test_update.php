<?php
// Test script to debug database UPDATE
require_once __DIR__ . '/../app/Core/Database.php';

$db = Database::getInstance();

echo "<h2>Testing ExamTimeTable UPDATE</h2>";

// 1. Check current record
echo "<h3>1. Current Record:</h3>";
$stmt = $db->prepare("SELECT * FROM examTimeTable WHERE grade = '6'");
$stmt->execute();
$current = $stmt->fetch();
echo "<pre>";
print_r($current);
echo "</pre>";

// 2. Try UPDATE
echo "<h3>2. Attempting UPDATE:</h3>";
$testFile = '/assets/exam_timetable_images/test_' . time() . '.png';
$sql = "UPDATE examTimeTable SET file = :file, title = :title WHERE grade = :grade";
echo "SQL: $sql<br>";
echo "Params: file=$testFile, title='Test Title', grade=6<br>";

$stmt = $db->prepare($sql);
$result = $stmt->execute([
    ':file' => $testFile,
    ':title' => 'Test Title - Grade 6',
    ':grade' => '6'
]);

echo "Execute result: " . ($result ? 'TRUE' : 'FALSE') . "<br>";
echo "Rows affected: " . $stmt->rowCount() . "<br>";
echo "Error info: ";
print_r($stmt->errorInfo());
echo "<br>";

// 3. Check updated record
echo "<h3>3. After UPDATE:</h3>";
$stmt = $db->prepare("SELECT * FROM examTimeTable WHERE grade = '6'");
$stmt->execute();
$updated = $stmt->fetch();
echo "<pre>";
print_r($updated);
echo "</pre>";

if ($updated && $updated['file'] === $testFile) {
    echo "<h3 style='color: green;'>✓ UPDATE SUCCESSFUL - File was updated in database!</h3>";
} else {
    echo "<h3 style='color: red;'>✗ UPDATE FAILED - File was NOT updated in database!</h3>";
}
?>