<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BigBlueButton Classroom - {{ $meetingID }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0e1726;
            color: #ffffff;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: #1b2e4b;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #3b3f5c;
        }
        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .bbb-logo {
            background-color: #2563eb;
            color: #fff;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .user-badge {
            background-color: #10b981;
            color: #064e3b;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        main {
            flex: 1;
            display: grid;
            grid-template-columns: 260px 1fr;
            height: calc(100vh - 65px);
        }
        aside {
            background-color: #191e3a;
            border-right: 1px solid #3b3f5c;
            padding: 1rem;
        }
        aside h3 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888ea8;
            margin-bottom: 1rem;
        }
        .user-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            background-color: #1b2e4b;
            border-radius: 6px;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .classroom-stage {
            background-color: #0e1726;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
        }
        .whiteboard-box {
            background: #ffffff;
            color: #1e293b;
            width: 90%;
            height: 75%;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 2px dashed #cbd5e1;
        }
        .controls {
            margin-top: 1.5rem;
            display: flex;
            gap: 1rem;
        }
        .btn-ctrl {
            background-color: #3b3f5c;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }
        .btn-ctrl.active {
            background-color: #2563eb;
        }
        .status-pill {
            background-color: #065f46;
            color: #34d399;
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 4px;
            margin-top: 8px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-title">
            <span class="bbb-logo">BBB</span>
            <h2>Online Classroom</h2>
        </div>
        <div>
            Logged in as <strong>{{ $fullName }}</strong>
            <span class="user-badge">{{ $role }}</span>
        </div>
    </header>
    <main>
        <aside>
            <h3>Users (1)</h3>
            <div class="user-item">
                <div class="avatar">{{ strtoupper(substr($fullName, 0, 1)) }}</div>
                <div>
                    <div><strong>{{ $fullName }}</strong></div>
                    <div style="font-size: 0.75rem; color: #888ea8;">(You - Tutor/Moderator)</div>
                </div>
            </div>
        </aside>
        <section class="classroom-stage">
            <div class="whiteboard-box">
                <h2 style="font-size: 1.8rem; color: #1e293b; margin-bottom: 0.5rem;">BigBlueButton Interactive Classroom</h2>
                <p style="color: #64748b; margin-bottom: 1rem;">Active Meeting ID: <strong>{{ $meetingID }}</strong></p>
                <span class="status-pill">● Live Session Connected</span>
            </div>
            <div class="controls">
                <button class="btn-ctrl active">🎤 Mute Audio</button>
                <button class="btn-ctrl active">📹 Share Webcam</button>
                <button class="btn-ctrl">🖥️ Share Screen</button>
                <a href="#" class="btn-ctrl" style="background-color: #4f46e5; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">📚 Resources</a>
                <a href="{{ route('meeting.index') }}" class="btn-ctrl" style="background-color: #dc2626; text-decoration: none;">Leave Meeting</a>
            </div>
        </section>
    </main>
</body>
</html>
