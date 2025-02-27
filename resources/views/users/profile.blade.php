<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>User Profile</h1>
        <h3>Name: {{ $user->name }}</h3>
        <h3>Email: {{ $user->email }}</h3>

        <h2>Certificates</h2>
        <ul class="list-group">
            @foreach($certificates as $certificate)
                <li class="list-group-item">
                    <a href="{{ route('certificate.show', $certificate->id) }}">{{ $certificate->course_title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>