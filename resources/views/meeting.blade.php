<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB Classroom Proof of Concept</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        h1 {
            font-size: 1.5rem;
            color: #1a202c;
            margin-bottom: 0.75rem;
        }
        p {
            color: #4a5568;
            font-size: 0.95rem;
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 0.85rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>BigBlueButton Classroom</h1>
        <p>Click below to create a new unique online classroom and join as moderator.</p>

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('meeting.join') }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary">Join Meeting</button>
        </form>
    </div>
</body>
</html>
