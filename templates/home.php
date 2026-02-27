<?php
session_start();

require_once '../Include/database.php';
require_once '../databases/Events.php';

if (empty($_SESSION['user_id'])) {
    header("Location: /templates/sign_in.php");
    exit();
}


$search_name = $_GET['search_name'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';


$events = searchEventsForHome($_SESSION['user_id'], $search_name, $start_date, $end_date);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการกิจกรรมทั้งหมด</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        
        .search-container {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .search-container input {
            padding: 6px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-search {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 7px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-clear {
            background-color: #95a5a6;
            color: white;
            text-decoration: none;
            padding: 7px 15px;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <?php include 'header.php' ?>
    
    <h2>รายการกิจกรรม</h2>

    <div class="search-container">
        <form method="GET" action="">
            <label>ชื่อกิจกรรม:</label>
            <input type="text" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="ค้นหาชื่อกิจกรรม...">
            
            <label>ตั้งแต่วันที่:</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            
            <label>ถึงวันที่:</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            
            <button type="submit" class="btn-search">🔍 ค้นหา</button>
            <a href="/templates/home.php" class="btn-clear">ล้างค่า</a>
        </form>
    </div>

    <table>
        <thead>
        <tbody>
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo $event['event_id']; ?></td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['organizer_name']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($event['start_date'])); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo $event['max_participants']; ?> คน</td>
                        <td>
                            <form action="/routes/Registration.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="request_join">
                                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                                <button type="submit" onclick="return confirm('ต้องการขอเข้าร่วมกิจกรรมนี้ใช่หรือไม่?');" style="cursor: pointer; padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 3px;">
                                    ขอเข้าร่วม
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">ยังไม่มีกิจกรรมในระบบ</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>