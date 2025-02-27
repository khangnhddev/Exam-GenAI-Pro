<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assessment->title }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1>{{ $assessment->title }}</h1>
        <p>{{ $assessment->description }}</p>
        <form method="POST" action="{{ route('assessments.submit', $assessment->id) }}">
            @csrf
            <button type="submit" class="btn btn-primary">Submit Assessment</button>
        </form>
    </div>
</body>
</html> 