<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px;
            background: #F9FAFB;
        }
        .certificate {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #E0E7FF;
        }
        .inner-border {
            border: 4px solid #E0E7FF;
            border-radius: 12px;
            padding: 32px;
            background: linear-gradient(135deg, white 0%, #EEF2FF 100%);
            text-align: center;
        }
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .logo-container {
            background: linear-gradient(to right, #4F46E5, #7C3AED);
            padding: 12px;
            border-radius: 12px;
            display: inline-block;
        }
        .logo {
            width: 40px;
            height: 40px;
        }
        .brand-text {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .brand-ai {
            font-size: 24px;
            font-weight: 500;
            color: #4F46E5;
        }
        .brand-pro {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(to right, #4F46E5, #7C3AED);
            -webkit-background-clip: text;
            color: transparent;
        }
        .title {
            font-size: 36px;
            font-weight: bold;
            background: linear-gradient(to right, #4F46E5, #7C3AED);
            -webkit-background-clip: text;
            color: transparent;
            margin: 24px 0 8px;
        }
        .subtitle {
            font-size: 18px;
            color: #6B7280;
            margin-bottom: 32px;
        }
        .recipient {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin: 16px 0;
        }
        .completion-text {
            font-size: 18px;
            color: #6B7280;
            margin: 24px 0;
        }
        .exam-title {
            font-size: 30px;
            font-weight: bold;
            color: #111827;
            margin: 32px 0;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            margin: 32px 0;
            max-width: 800px;
            margin: 32px auto;
        }
        .detail-card {
            background: white;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .detail-label {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 18px;
            font-weight: 500;
            color: #111827;
        }
        .score-value {
            color: #059669;
        }
        .qr-section {
            margin-top: 32px;
            display: inline-block;
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .verify-text {
            margin-top: 8px;
            font-size: 14px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="inner-border">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-container">
                    <svg class="logo" fill="none" viewBox="0 0 24 24" stroke="white">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="brand-ai">AI</span>
                    <span class="brand-pro">Pro</span>
                </div>
            </div>

            <!-- Title -->
            <h1 class="title">Certificate of Achievement</h1>
            <p class="subtitle">in AI Proficiency</p>

            <!-- Recipient -->
            <p class="recipient">{{ $user->name }}</p>
            
            <!-- Course Info -->
            <p class="completion-text">has successfully completed</p>
            <p class="exam-title">{{ $exam->title }}</p>

            <!-- Details Grid -->
            <div class="details-grid">
                <div class="detail-card">
                    <p class="detail-label">Issue Date</p>
                    <p class="detail-value">{{ $certificate->created_at->format('F d, Y') }}</p>
                </div>
                <div class="detail-card">
                    <p class="detail-label">Certificate ID</p>
                    <p class="detail-value">{{ $certificate->certificate_number }}</p>
                </div>
                <div class="detail-card">
                    <p class="detail-label">Score Achieved</p>
                    <p class="detail-value score-value">{{ $certificate->score }}%</p>
                </div>
            </div>

            <!-- QR Code -->
            <div class="qr-section">
                {!! QrCode::size(120)
                    ->style('dot')
                    ->eye('circle')
                    ->color(79, 70, 229)
                    ->generate(url("/verify/{$certificate->certificate_number}")) !!}
                <p class="verify-text">Scan to verify</p>
            </div>
        </div>
    </div>
</body>
</html>