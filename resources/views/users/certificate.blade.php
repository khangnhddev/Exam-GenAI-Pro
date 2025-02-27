<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Certificate for {{ $certificate->course_title }}</h1>
        <p><strong>User:</strong> {{ $certificate->user->name }}</p>
        <p><strong>Certificate Path:</strong> <a href="{{ asset($certificate->certificate_path) }}">Download</a></p>
    </div>
</body>
</html>