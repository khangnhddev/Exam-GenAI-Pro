<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in Course</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7f9;
            color: #333;
        }
        .course-header {
            background-color: #1a1a1a;
            color: #fff;
            padding: 20px;
        }
        .course-content {
            padding: 20px;
        }
        .course-card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="course-header text-center">
        <h1>{{ $course['title'] }}</h1>
        <p>{{ $course['description'] }}</p>
    </div>

    <div class="container course-content">
        <div class="course-card">
            <h3>Who Should Take This Course</h3>
            <p>{{ $course['audience'] }}</p>
            <h4>Prerequisites</h4>
            <ul>
                @foreach($course['prerequisites'] as $prerequisite)
                    <li>{{ $prerequisite }}</li>
                @endforeach
            </ul>
            <a href="#" class="btn btn-primary">Enroll Now</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 