<?php
// api.php - API para operaciones CRUD

// Endpoint especial para debug
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
    echo "<h2>DEBUG API.PHP</h2>";
    echo "<pre>";
    echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'No definido') . "\n";
    echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'No definido') . "\n";
    echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'No definido') . "\n";
    echo "</pre>";
    
    echo "<h3>Incluyendo config.php...</h3>";
    require_once 'config.php';
    echo "<h3>Config.php incluido exitosamente</h3>";
    exit;
}

// Configuración de headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Conexión directa a la base de datos (sin config.php por ahora)
try {
    // Detectar entorno
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $isProduction = ($httpHost !== 'localhost' && !preg_match('/^127\.0\.0\.1/', $httpHost));
    
    if ($isProduction) {
        // Configuración para InfinityFree
        $host = 'sql104.infinityfree.com';
        $dbname = 'if0_39980782_planificamir';
        $username = 'if0_39980782';
        $password = 'DWccYKXLKSdQpPo';
        $port = 3306;
    } else {
        // Configuración local XAMPP
        $host = 'localhost';
        $dbname = 'planificamir';
        $username = 'root';
        $password = '';
        $port = 3306;
    }
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

 $method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Cargar todos los datos
        $subjects = getSubjects();
        $tasks = getTasks();
        echo json_encode(['subjects' => $subjects, 'tasks' => $tasks]);
        break;

    case 'POST':
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        // Fallback to form-data if JSON parse failed
        if (!is_array($data)) {
            $data = $_POST;
        }
        $action = $data['action'] ?? '';

        switch ($action) {
            case 'add_subject':
                $id = addSubject($data['name']);
                echo json_encode(['success' => true, 'id' => $id]);
                break;

            case 'add_topic':
                $id = addTopic($data['subject_id'], $data['name']);
                echo json_encode(['success' => true, 'id' => $id]);
                break;

            case 'add_task':
                $id = addTask($data['name'], $data['date']);
                echo json_encode(['success' => true, 'id' => $id]);
                break;

            case 'update_topic':
                updateTopic($data['id'], $data['updates']);
                echo json_encode(['success' => true]);
                break;

            case 'update_task':
                updateTask($data['id'], $data['updates']);
                echo json_encode(['success' => true]);
                break;

            case 'delete_topic':
                deleteTopic($data['id']);
                echo json_encode(['success' => true]);
                break;

            case 'delete_subject':
                deleteSubject($data['id']);
                echo json_encode(['success' => true]);
                break;

            case 'delete_task':
                deleteTask($data['id']);
                echo json_encode(['success' => true]);
                break;

            case 'add_round':
                $newRound = addRound($data['subject_id']);
                echo json_encode(['success' => true, 'round_number' => $newRound]);
                break;

            case 'update_round':
                updateRound($data['id'], $data['updates']);
                echo json_encode(['success' => true]);
                break;

            case 'upsert_round':
                // expects topic_id, round_number, completed
                $topicId = $data['topic_id'] ?? null;
                $roundNumber = $data['round_number'] ?? null;
                $completed = isset($data['completed']) ? (int)$data['completed'] : 0;
                if ($topicId && $roundNumber) {
                    $id = upsertRound($topicId, $roundNumber, $completed);
                    echo json_encode(['success' => true, 'id' => $id]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
                }
                break;

            case 'delete_round':
                $deleted = deleteRound($data['subject_id']);
                echo json_encode(['success' => $deleted]);
                break;

            default:
                echo json_encode(['error' => 'Acción no válida']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no soportado']);
}

function getSubjects() {
    global $pdo;
    
    // Get subjects
    $subjectsStmt = $pdo->query("SELECT * FROM subjects ORDER BY name");
    $subjects = $subjectsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get all topics
    $topicsStmt = $pdo->query("SELECT * FROM topics ORDER BY name");
    $topics = $topicsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get all rounds
    $roundsStmt = $pdo->query("SELECT * FROM topic_rounds ORDER BY round_number");
    $rounds = $roundsStmt->fetchAll(PDO::FETCH_ASSOC);
    $roundsByTopic = [];
    foreach ($rounds as $r) {
        $tid = $r['topic_id'];
        if (!isset($roundsByTopic[$tid])) $roundsByTopic[$tid] = [];
        $roundsByTopic[$tid][] = $r;
    }

    // Group topics by subject_id
    $topicsBySubject = [];
    foreach ($topics as $topic) {
        $subjectId = $topic['subject_id'];
        if (!isset($topicsBySubject[$subjectId])) {
            $topicsBySubject[$subjectId] = [];
        }
        // attach rounds to topic
        $topicId = $topic['id'];
        $topic['rounds'] = isset($roundsByTopic[$topicId]) ? $roundsByTopic[$topicId] : [];
        $topicsBySubject[$subjectId][] = $topic;
    }
    
    // Attach topics to subjects
    foreach ($subjects as &$subject) {
        $subjectId = $subject['id'];
        $subject['topics'] = isset($topicsBySubject[$subjectId]) ? $topicsBySubject[$subjectId] : [];
    }
    
    return $subjects;
}

function addRound($subjectId) {
    global $pdo;
    // find max round_number for topics in this subject
    $stmt = $pdo->prepare("SELECT MAX(tr.round_number) AS maxr FROM topic_rounds tr JOIN topics t ON tr.topic_id = t.id WHERE t.subject_id = ?");
    $stmt->execute([$subjectId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxr = $row['maxr'] ?? 0;
    $newRound = $maxr + 1;

    // get topics in subject
    $tstmt = $pdo->prepare("SELECT id FROM topics WHERE subject_id = ?");
    $tstmt->execute([$subjectId]);
    $topics = $tstmt->fetchAll(PDO::FETCH_COLUMN);

    $istmt = $pdo->prepare("INSERT INTO topic_rounds (topic_id, round_number, completed) VALUES (?, ?, 0)");
    foreach ($topics as $tid) {
        $istmt->execute([$tid, $newRound]);
    }
    return $newRound;
}

function updateRound($id, $updates) {
    global $pdo;
    $setParts = [];
    $params = [];
    foreach ($updates as $key => $value) {
        $setParts[] = "$key = ?";
        $params[] = $value;
    }
    $params[] = $id;
    $stmt = $pdo->prepare("UPDATE topic_rounds SET " . implode(', ', $setParts) . " WHERE id = ?");
    $stmt->execute($params);
}

function getTasks() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY date");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addSubject($name) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO subjects (name) VALUES (?)");
    $stmt->execute([$name]);
    return $pdo->lastInsertId();
}

function addTopic($subjectId, $name) {
    global $pdo;
    // Get the next order_index for this subject
    $stmt = $pdo->prepare("SELECT MAX(order_index) AS max_order FROM topics WHERE subject_id = ?");
    $stmt->execute([$subjectId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextOrder = ($row['max_order'] ?? 0) + 1;
    
    // Use default numeric values for new topics (16 = "Bajo" category)
    $stmt = $pdo->prepare("INSERT INTO topics (subject_id, name, importance, rentability, order_index) VALUES (?, ?, 16, 16, ?)");
    $stmt->execute([$subjectId, $name, $nextOrder]);
    return $pdo->lastInsertId();
}

function addTask($name, $date) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tasks (name, date) VALUES (?, ?)");
    $stmt->execute([$name, $date]);
    return $pdo->lastInsertId();
}

function updateTopic($id, $updates) {
    global $pdo;
    $setParts = [];
    $params = [];
    foreach ($updates as $key => $value) {
        $setParts[] = "$key = ?";
        $params[] = $value;
    }
    $params[] = $id;
    $stmt = $pdo->prepare("UPDATE topics SET " . implode(', ', $setParts) . " WHERE id = ?");
    $stmt->execute($params);
}

function updateTask($id, $updates) {
    global $pdo;
    $setParts = [];
    $params = [];
    foreach ($updates as $key => $value) {
        $setParts[] = "$key = ?";
        $params[] = $value;
    }
    $params[] = $id;
    $stmt = $pdo->prepare("UPDATE tasks SET " . implode(', ', $setParts) . " WHERE id = ?");
    $stmt->execute($params);
}

function deleteTopic($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM topics WHERE id = ?");
    $stmt->execute([$id]);
}

function deleteSubject($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$id]);
}

function deleteTask($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
}

function deleteRound($subjectId) {
    global $pdo;
    // find max round_number for this subject
    $stmt = $pdo->prepare("SELECT MAX(tr.round_number) AS maxr FROM topic_rounds tr JOIN topics t ON tr.topic_id = t.id WHERE t.subject_id = ?");
    $stmt->execute([$subjectId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxr = $row['maxr'];
    if (!$maxr) return false;
    $dstmt = $pdo->prepare("DELETE tr FROM topic_rounds tr JOIN topics t ON tr.topic_id = t.id WHERE t.subject_id = ? AND tr.round_number = ?");
    $dstmt->execute([$subjectId, $maxr]);
    return true;
}

function upsertRound($topicId, $roundNumber, $completed) {
    global $pdo;
    // try to find existing
    $stmt = $pdo->prepare("SELECT id FROM topic_rounds WHERE topic_id = ? AND round_number = ?");
    $stmt->execute([$topicId, $roundNumber]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $id = $row['id'];
        $ustmt = $pdo->prepare("UPDATE topic_rounds SET completed = ? WHERE id = ?");
        $ustmt->execute([$completed, $id]);
        return $id;
    } else {
        $istmt = $pdo->prepare("INSERT INTO topic_rounds (topic_id, round_number, completed) VALUES (?, ?, ?)");
        $istmt->execute([$topicId, $roundNumber, $completed]);
        return $pdo->lastInsertId();
    }
}
?>
