<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting...</title>
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
    <meta http-equiv="refresh" content="0;url={{ route('login') }}">
</head>
<body>
    <p>Redirecting to login page... <a href="{{ route('login') }}">Click here if you are not redirected.</a></p>
</body>
</html>
