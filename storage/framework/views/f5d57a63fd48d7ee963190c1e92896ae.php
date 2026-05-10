<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting...</title>
    <script>
        window.location.href = "<?php echo e(route('login')); ?>";
    </script>
    <meta http-equiv="refresh" content="0;url=<?php echo e(route('login')); ?>">
</head>
<body>
    <p>Redirecting to login page... <a href="<?php echo e(route('login')); ?>">Click here if you are not redirected.</a></p>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\auth\student-login.blade.php ENDPATH**/ ?>