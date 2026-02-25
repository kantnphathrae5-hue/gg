<?php 

if(isset($_SESSION['user_id'])): ?>
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: right; border: 1px solid #ddd;">
            <b>ชื่อผู้ใช้:</b> <?php echo htmlspecialchars($_SESSION['name'] ?? 'ผู้ใช้งาน'); ?>
            
            <a href="/templates/profile.php" style="text-decoration: none; font-weight: bold; color: #3498db;">👤 ข้อมูลบัญชี</a> &nbsp;|&nbsp; 
            
            <a href="/templates/manage_event.php" style="text-decoration: none; color: #27ae60;">➕ จัดการกิจกรรม</a> &nbsp;|&nbsp;
            <a href="/templates/history.php" style="text-decoration: none; color: black;">📜 ประวัติการเข้าร่วม</a> &nbsp;|&nbsp;
            <a href="/routes/User.php?action=logout" style="text-decoration: none; color: #e74c3c;">🚪 ออกจากระบบ</a>
        </div>
        <?php  ?>
    <?php else: ?>
        <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ffeeba;">
            <p style="margin: 0; color: #856404;">
                ⚠️ คุณยังไม่ได้เข้าสู่ระบบ <a href="/templates/sign_in.php" style="font-weight: bold; text-decoration: none;">คลิกที่นี่เพื่อเข้าสู่ระบบ</a> หรือสมัครสมาชิกเพื่อลงทะเบียนเข้าร่วมกิจกรรม
            </p>
        </div>
    <?php endif; ?>