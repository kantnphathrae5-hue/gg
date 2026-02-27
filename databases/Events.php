<?php



function getAllEvents() {
    global $conn;
    
    $sql = "SELECT e.*, u.name AS organizer_name 
            FROM Events e 
            LEFT JOIN Users u ON e.organizer_id = u.user_id 
            ORDER BY e.start_date DESC";
            
    $result = $conn->query($sql);
    
    $events = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    
    return $events;
}


function getEventById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// อัปเดตข้อมูลกิจกรรม
function updateEvent($id, $data) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Events SET event_name=?, description=?, start_date=?, end_date=?, max_participants=?, location=? WHERE event_id=?");
    
    $stmt->bind_param("ssssisi", 
        $data['event_name'], 
        $data['description'], 
        $data['start_date'], 
        $data['end_date'], 
        $data['max_participants'], 
        $data['location'],
        $id
    );

    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// ลบข้อมูลกิจกรรม
function deleteEvent($id) {
    global $conn;
    
   
    $conn->query("DELETE FROM Event_Images WHERE event_id = " . intval($id));
    
    
    $conn->query("DELETE FROM Registrations WHERE event_id = " . intval($id));
    
    
    $stmt = $conn->prepare("DELETE FROM Events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function createEvent($data) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Events (organizer_id, event_name, description, start_date, end_date, max_participants, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("issssis", 
        $data['organizer_id'], $data['event_name'], $data['description'], 
        $data['start_date'], $data['end_date'], $data['max_participants'], $data['location']
    );

    if ($stmt->execute()) {
        $last_id = $conn->insert_id; 
        $stmt->close();
        return $last_id; 
    }
    
    $stmt->close();
    return false;
}


function addEventImage($event_id, $image_path) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Event_Images (event_id, image_path) VALUES (?, ?)");
    $stmt->bind_param("is", $event_id, $image_path);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getEventsForHome($current_user_id) {
    global $conn;
    $sql = "SELECT e.*, u.name as organizer_name 
            FROM Events e 
            JOIN Users u ON e.organizer_id = u.user_id 
            WHERE e.organizer_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $events = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

function getEventsByOrganizer($organizer_id) {
    global $conn;
    $sql = "SELECT e.*, u.name as organizer_name 
            FROM Events e 
            JOIN Users u ON e.organizer_id = u.user_id 
            WHERE e.organizer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $organizer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $events = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

function searchEventsForHome($current_user_id, $search_name = '', $start_date = '', $end_date = '') {
    global $conn;
    
   
    $sql = "SELECT e.*, u.name as organizer_name 
            FROM Events e 
            JOIN Users u ON e.organizer_id = u.user_id 
            WHERE e.organizer_id != ?";
            
    $types = "i";
    $params = [$current_user_id];
    
  
    if (!empty($search_name)) {
        $sql .= " AND e.event_name LIKE ?";
        $types .= "s";
        $params[] = "%" . $search_name . "%";
    }
    
   
    if (!empty($start_date)) {
        $sql .= " AND DATE(e.start_date) >= ?";
        $types .= "s";
        $params[] = $start_date;
    }
    
   
    if (!empty($end_date)) {
       
        $sql .= " AND DATE(e.start_date) <= ?"; 
        $types .= "s";
        $params[] = $end_date;
    }
    
    $sql .= " ORDER BY e.start_date DESC";
    
    $stmt = $conn->prepare($sql);
    
   
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $events = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}
?>