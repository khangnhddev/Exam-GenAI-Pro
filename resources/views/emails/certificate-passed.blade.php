<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { background-color: #f8f9fa; padding: 20px; border-radius: 8px; }
        .score { font-size: 24px; color: #28a745; text-align: center; }
        .footer { text-align: center; margin-top: 30px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Congratulations {{ $user->fullname }}! 🎉</h1>
        </div>
        
        <div class="content">
            <p>You have successfully passed the exam: <strong>{{ $exam->title }}</strong></p>
            
            <div class="score">
                Your Score: {{ $attempt->score }}%
            </div>
            
            <p>Details:</p>
            <ul>
                <li>Exam: {{ $exam->title }}</li>
                <li>Completed On: {{ $attempt->completed_at->format('F j, Y, g:i a') }}</li>
                {{-- <li>Time Taken: {{ $attempt->duration_taken }} minutes</li>
                <li>Questions Attempted: {{ $attempt->total_questions }}</li> --}}
            </ul>
            
            <p>Keep up the great work! Your certificate has been added to your profile.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from AIro Learning Platform</p>
        </div>
    </div>
</body>
</html>