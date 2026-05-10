<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo e($title); ?></title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .footer { background: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .button { display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; }
        .status-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4f46e5; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo e($title); ?></h1>
            <p>Tugawe Elementary School</p>
        </div>
        
        <div class="content">
            <p>Dear Parent/Guardian,</p>
            
            <div class="status-box">
                <p><strong>Application Number:</strong> <?php echo e($application->application_number); ?></p>
                <p><strong>Student:</strong> <?php echo e($application->student_full_name); ?></p>
                <p><?php echo e($body); ?></p>
            </div>
            
            <?php if($application->admin_notes): ?>
                <p><strong>Admin Notes:</strong></p>
                <p><?php echo e($application->admin_notes); ?></p>
            <?php endif; ?>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="<?php echo e(url('/enroll')); ?>" class="button">Check Full Details</a>
            </p>
            
            <p>Best regards,<br>
            Tugawe Elementary School Admissions Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; <?php echo e(date('Y')); ?> Tugawe Elementary School. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\emails\enrollment\status-updated.blade.php ENDPATH**/ ?>