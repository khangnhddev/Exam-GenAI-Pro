<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Results</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h1>Assessment Results</h1>
        @foreach($results as $result)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Score: {{ $result->score }}</h5>
                    <p class="card-text">Feedback: {{ $result->feedback }}</p>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html> 