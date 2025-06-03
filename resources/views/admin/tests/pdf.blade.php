<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $test->title }} - Test</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            color: #333;
            line-height: 1.6;
            background: linear-gradient(135deg, #f8fafc 0%, #e8f4f8 100%);
            background-attachment: fixed;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1a2e5a;
            padding-bottom: 20px;
            margin-bottom: 30px;
            background: white;
            border-radius: 8px;
            padding: 30px 20px 20px;
            box-shadow: 0 2px 10px rgba(26, 46, 90, 0.1);
        }
        .logo {
            max-width: 120px;
            max-height: 80px;
            margin-bottom: 15px;
        }
        .test-title {
            color: #1a2e5a;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .test-info {
            background: linear-gradient(135deg, #ffffff 0%, #f1f8ff 100%);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #e3f2fd;
            box-shadow: 0 1px 5px rgba(26, 46, 90, 0.05);
        }
        .info-item {
            display: inline-block;
            margin: 5px 15px;
            font-size: 14px;
        }
        .question {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #e3f2fd;
            border-radius: 8px;
            page-break-inside: avoid;
            background: white;
            box-shadow: 0 1px 3px rgba(26, 46, 90, 0.08);
        }
        .question-number {
            background-color: #1a2e5a;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
        .question-text {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .choices {
            margin-left: 20px;
        }
        .choice {
            margin: 8px 0;
            font-size: 14px;
        }
        .choice-letter {
            font-weight: bold;
            margin-right: 8px;
            color: #1a2e5a;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e3f2fd;
            padding-top: 20px;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(26, 46, 90, 0.08);
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        <div class="test-title">{{ $test->title }}</div>
        <div style="color: #666; font-size: 14px;">
            {{ $test->description }}
        </div>
    </div>

    <div class="test-info">
        <div class="info-item">
            <strong>📝 Soru Sayısı:</strong> {{ $test->questions_count }}
        </div>
        @if($test->duration_minutes)
        <div class="info-item">
            <strong>⏱️ Süre:</strong> {{ $test->duration_minutes }} Dakika
        </div>
        @endif
        @if($test->difficulty_level)
        <div class="info-item">
            <strong>🎯 Zorluk:</strong> {{ $test->difficulty_level }}
        </div>
        @endif
        <div class="info-item">
            <strong>📅 İndirilme Tarihi:</strong> {{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}
        </div>
    </div>

    @foreach($test->questions as $index => $question)
    <div class="question">
        <div class="question-number">Soru {{ $index + 1 }}</div>
        <div class="question-text">{{ $question->question_text }}</div>
        
        @if($question->choices && $question->choices->count() > 0)
        <div class="choices">
            @foreach($question->choices as $choiceIndex => $choice)
            <div class="choice">
                <span class="choice-letter">{{ chr(65 + $choiceIndex) }})</span>
                {{ $choice->choice_text }}
            </div>
            @endforeach
        </div>
        @endif
    </div>
    
    @if(($index + 1) % 10 == 0 && !$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach

    <div class="footer">
        <p>Bu test {{ config('app.name') }} platformu tarafından oluşturulmuştur.</p>
        <p>İndirilme Tarihi: {{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}</p>
    </div>
</body>
</html>