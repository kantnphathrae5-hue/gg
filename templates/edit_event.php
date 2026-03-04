<?php
session_start();
require_once '../Include/database.php';
require_once '../databases/Events.php';

if (empty($_SESSION['user_id'])) {
    header("Location: /templates/sign_in.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$event = getEventById($id);

// ป้องกันคนอื่นแอบเข้า
if (!$event || $event['organizer_id'] != $_SESSION['user_id']) {
    echo "<script>alert('ไม่พบข้อมูลกิจกรรม หรือคุณไม่มีสิทธิ์แก้ไข'); window.location.href='/templates/manage_event.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขกิจกรรม</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        .form-container { max-width: 800px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-top: 0; margin-bottom: 30px; }
        .btn-back { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #7f8c8d; font-weight: bold; transition: 0.2s; }
        .btn-back:hover { color: #3498db; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #34495e; }
        
        input[type="text"], input[type="number"], input[type="datetime-local"], textarea {
            width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 6px; box-sizing: border-box;
            font-family: inherit; font-size: 1em; transition: border-color 0.2s;
        }
        input:focus, textarea:focus { border-color: #3498db; outline: none; box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1); }
        textarea { resize: vertical; min-height: 120px; }
        
        .grid-2-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .upload-box { border: 2px dashed #cbd5e0; padding: 20px; text-align: center; border-radius: 6px; background-color: #f8f9fa; margin-bottom: 20px; }
        .upload-box input[type="file"] { margin-top: 10px; }
        
        .btn-submit { background-color: #3498db; color: white; border: none; padding: 15px 20px; font-size: 1.1em; border-radius: 6px; cursor: pointer; width: 100%; font-weight: bold; transition: 0.3s; margin-top: 10px; }
        .btn-submit:hover { background-color: #2980b9; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="form-container">
        <a href="/templates/manage_event.php" class="btn-back">⬅ กลับหน้าจัดการกิจกรรม</a>
        <h2>✏️ แก้ไขกิจกรรม: <?php echo htmlspecialchars($event['event_name']); ?></h2>
        
        <form action="/routes/event.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
            
            <div class="form-group">
                <label>ชื่อกิจกรรม <span style="color:red;">*</span></label>
                <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>รายละเอียดกิจกรรม</label>
                <textarea name="description"><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            
            <div class="grid-2-col">
                <div class="form-group">
                    <label>วันที่เริ่ม <span style="color:red;">*</span></label>
                    <input type="datetime-local" name="start_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['start_date'])); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>วันที่สิ้นสุด <span style="color:red;">*</span></label>
                    <input type="datetime-local" name="end_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['end_date'])); ?>" required>
                </div>
            </div>
            
            <div class="grid-2-col">
                <div class="form-group">
                    <label>จำนวนที่รับ (คน)</label>
                    <input type="number" name="max_participants" value="<?php echo $event['max_participants']; ?>">
                </div>
                
                <div class="form-group">
                    <label>สถานที่จัดงาน</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>">
                </div>
            </div>
            
            <div class="upload-box">
                <label>📸 อัปโหลดรูปภาพเพิ่มเติม (เลือกได้หลายรูป)</label>
                <input type="file" name="event_images[]" accept="image/*" multiple>
                <p style="color: #7f8c8d; font-size: 0.85em; margin-top: 10px;">*หากไม่ต้องการเพิ่มรูปใหม่ ให้เว้นว่างไว้ รูปเก่าจะยังคงอยู่</p>
            </div>
            
            <button type="submit" class="btn-submit">💾 บันทึกการแก้ไข</button>
        </form>
    </div>

</body>
</html>