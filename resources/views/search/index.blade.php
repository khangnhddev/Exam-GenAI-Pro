<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-bar {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Search Courses</h1>
        <form method="GET" action="{{ url('/search') }}" class="search-bar">
            <div class="form-row">
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" placeholder="Keyword">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="Course">Course</option>
                        <option value="Skill Path">Skill Path</option>
                        <option value="Project">Project</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="skill" class="form-control">
                        <option value="">All Skills</option>
                        <!-- Add skill options here -->
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="difficulty" class="form-control">
                        <option value="">All Levels</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-control">
                        <option value="">Sort By</option>
                        <option value="popularity">Popularity</option>
                        <option value="created_at">Date</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <div class="filter-section">
            <h5>Filters</h5>
            <div class="form-row">
                <div class="col-md-3">
                    <select name="duration" class="form-control">
                        <option value="">Any Duration</option>
                        <option value="1">1 Hour</option>
                        <option value="5">5 Hours</option>
                        <option value="10">10 Hours</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="price" class="form-control">
                        <option value="">All Prices</option>
                        <option value="free">Free</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="language" class="form-control">
                        <option value="">All Languages</option>
                        <!-- Add language options here -->
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($courses as $course)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                            <p class="card-text"><small class="text-muted">{{ $course->duration }} hours</small></p>
                            <a href="#" class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $courses->links() }}
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>