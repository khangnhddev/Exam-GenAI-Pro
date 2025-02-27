<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết khóa học</title>
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
        .badge-popular {
            background-color: #ffc107;
            color: #fff;
        }
        .btn-custom {
            background-color: #6c63ff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #5a54d1;
        }
    </style>
</head>
<body>
    <div class="course-header text-center">
        <h1>{{ $course['title'] }}</h1>
        <p>{{ $course['description'] }}</p>
        <span class="badge badge-popular">POPULAR</span>
        <span>Intermediate</span>
        <span>85h</span>
        <a href="#" class="text-white ml-2">Claim Your Certificate</a>
        <div class="mt-3">
            <button class="btn btn-custom">Start Course</button>
        </div>
    </div>

    <div class="container course-content">
        <div class="row">
            <div class="col-md-8">
                <div class="course-card">
                    <h3>Course Overview</h3>
                    <p>{{ $course['details'] }}</p>
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-outline-secondary">Code Feedback</button>
                        <button class="btn btn-outline-secondary">Mock Interview</button>
                        <button class="btn btn-outline-secondary">Explanations</button>
                        <button class="btn btn-outline-secondary">Adaptive Learning</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="course-card">
                    <h4>This Course Includes</h4>
                    <ul>
                        <li>{{ $course['lessons'] }} Lessons</li>
                        <li>{{ $course['playgrounds'] }} Playgrounds</li>
                        <li>{{ $course['challenges'] }} Challenges</li>
                        <li>{{ $course['quizzes'] }} Quizzes</li>
                    </ul>
                    <div class="mt-4">
                        <h5>Want Your Certificate?</h5>
                        <p>Complete all lessons in the course</p>
                        <p>Spend at least 50% of the stated completion hours on the course</p>
                        <button class="btn btn-secondary" disabled>Claim Certificate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 