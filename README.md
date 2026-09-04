# BigBlueButton (BBB) Laravel Integration POC

This repository contains a Laravel proof-of-concept integrating BigBlueButton (BBB) classroom meetings.

## Overview

### 1. Creating the BBB Meeting
`App\Services\BigBlueButtonService::createMeeting()` executes an HTTP `GET` request to the BigBlueButton `/api/create` endpoint, passing query parameters (`name`, `meetingID`, `attendeePW`, `moderatorPW`) signed with a SHA-1 checksum.

### 2. Generating the Unique Meeting ID
Unique meeting IDs are generated programmatically per session using `Str::uuid()` with a `room-` prefix (e.g. `room-550e8400-e29b-41d4-a716-446655440000`), ensuring distinct IDs for every room creation request.

### 3. Laravel Authentication with BBB
Authentication uses a shared secret (`salt`) configured via `config/bigbluebutton.php` / environment variables. For each request, Laravel computes a SHA-1 checksum:
`sha1($apiAction . $queryString . $salt)`
BBB calculates the checksum on its end using the same shared secret and rejects any requests where checksums do not match.

### 4. How the Join Process Works
1. User clicks **Join Meeting** on the landing page (`/`).
2. `MeetingController@join` triggers `BigBlueButtonService@createMeeting` to call the `/api/create` endpoint.
3. Upon receiving a successful XML response, `BigBlueButtonService@getModeratorJoinUrl` constructs a signed `/api/join` URL containing `fullName`, `meetingID`, `password` (moderatorPW), and `redirect=true`.
4. Laravel redirects the user's browser directly to the generated BBB join URL.

### 5. Adding the Resources Button
In the local mock classroom UI (`resources/views/mock_bbb_room.blade.php`), a `📚 Resources` action button was added to the main control bar pointing to `#`. In production BigBlueButton (v2.6+ / 3.0+), custom UI buttons are added via the `@bigbluebutton/plugin-sdk` HTML5 client plugin framework.

### 6. Difficulties & Limitations
- **Local Development Environment**: A full BigBlueButton server requires a dedicated Ubuntu installation or complex container setup. A mock BBB API controller (`MockBigBlueButtonController`) was implemented to test full request flows locally without external dependencies.
- **Client Extensibility**: BBB's HTML5 client is standalone; adding custom buttons in a real deployment requires building an HTML5 plugin rather than editing Laravel views.

### 7. Production Implementation Recommendations
- **Database Persistence**: Store meeting sessions, room UUIDs, passwords, and user role associations in Eloquent models and migrations.
- **Official PHP SDK**: Integrate the official `bigbluebutton/bigbluebutton-api-php` package for full feature coverage (recording management, webhooks, room status).
- **BBB HTML5 Plugin**: Package the Resources button into a plugin using `@bigbluebutton/plugin-sdk`.
- **Role-Based Join Links**: Generates student (attendee) vs. tutor (moderator) join links dynamically based on Laravel user authentication and authorization rules.
