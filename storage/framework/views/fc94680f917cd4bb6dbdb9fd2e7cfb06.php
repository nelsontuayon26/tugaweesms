<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Enrollment Approved - Welcome!</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .footer { background: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .button { display: inline-block; background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; }
        .credentials-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border: 2px solid #10b981; }
        .warning { background: #fef3c7; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #f59e0b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Congratulations!</h1>
            <p>Your enrollment has been approved</p>
        </div>
        
        <div class="content">
            <p>Dear Parent/Guardian,</p>
            
            <p>We are pleased to inform you that <strong><?php echo e($application->student_full_name); ?></strong> has been officially enrolled at Tugawe Elementary School!</p>
            
            <div class="credentials-box">
                <h3 style="margin-top: 0; color: #10b981;">Student Account Credentials</h3>
                <p><strong>Student Name:</strong> <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></p>
                <p><strong>LRN:</strong> <?php echo e($student->lrn ?? 'Not Assigned'); ?></p>
                <p><strong>Username:</strong> <?php echo e($user->username); ?></p>
                <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #e5e7eb;">
                <p style="margin-bottom: 0;"><strong>Login Portal:</strong> <a href="<?php echo e(url('/student/login')); ?>"><?php echo e(url('/student/login')); ?></a></p>
            </div>
            
            <div class="warning">
                <strong>Important:</strong> Please keep these credentials secure. You can use the parent email and the password you created during enrollment to access the parent portal.
            </div>
            
            <h3>Next Steps:</h3>
            <ol>
                <li>Login to the pupil portal using the credentials above</li>
                <li>Complete the student profile</li>
                <li>Review class schedule (will be available soon)</li>
                <li>Attend orientation on the first day of school</li>
            </ol>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="<?php echo e(url('/student/login')); ?>" class="button">Login to Pupil Portal</a>
            </p>
            
            <p>Welcome to the Tugawe Elementary School family! We look forward to a wonderful school year.</p>
            
            <p>Best regards,<br>
            <strong>Tugawe Elementary School Administration</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; <?php echo e(date('Y')); ?> Tugawe Elementary School. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\emails\enrollment\approved.blade.php ENDPATH**/ ?>