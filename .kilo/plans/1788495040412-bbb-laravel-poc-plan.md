# BigBlueButton (BBB) Laravel Proof of Concept Plan

## Goal
Build a minimal Laravel proof of concept (POC) allowing users to click a "Join Meeting" button, which programmatically creates a brand-new unique BigBlueButton classroom via the BBB API and redirects the user directly into the room as a moderator/tutor.

## System & Config Specs
- **BBB Server URL**: `https://test30.bigbluebutton.org/bigbluebutton/`
- **BBB Shared Secret**: `8cd803242780775d5065e8942b0c3924` (configured in `.env`)
- **Laravel Architecture**:
  - Service Layer (`App\Services\BigBlueButtonService`) for direct BBB REST API integration and SHA-1 checksum calculation.
  - Controller (`App\Http\Controllers\MeetingController`) handling room creation trigger and user redirect.
  - Blade View (`resources/views/meeting.blade.php`) rendering the "Join Meeting" test page.

---

## Detailed Task Breakdown

### 1. Laravel Project Setup
- Install / initialize Laravel application structure in workspace.
- Configure `.env` and `.env.example` with BBB settings:
  ```env
  BBB_SERVER_BASE_URL=https://test30.bigbluebutton.org/bigbluebutton/
  BBB_SECURITY_SALT=8cd803242780775d5065e8942b0c3924
  ```
- Add BBB configuration file `config/bigbluebutton.php` referencing `env('BBB_SERVER_BASE_URL')` and `env('BBB_SECURITY_SALT')`.

### 2. BigBlueButton Integration Service (`App\Services\BigBlueButtonService`)
- Implement `BigBlueButtonService` class:
  - **Checksum Generator**: Calculates `sha1($apiAction . $queryString . $secret)` according to BigBlueButton API specifications.
  - **Create Meeting Method (`createMeeting`)**:
    - Generates a unique `meetingID` per request using `Str::uuid()` or `room-` + `Str::random(10)`.
    - Generates secure random passwords for `moderatorPW` and `attendeePW`.
    - Sends HTTP GET request to `{BBB_SERVER_BASE_URL}api/create?name=...&meetingID=...&attendeePW=...&moderatorPW=...&checksum=...`.
    - Parses XML response to confirm `<returncode>SUCCESS</returncode>`.
  - **Join Meeting URL Generator (`getJoinUrl`)**:
    - Constructs join query params: `fullName`, `meetingID`, `password` (moderatorPW), `redirect=true`.
    - Calculates checksum for `join` action.
    - Returns full HTTP redirect URL: `{BBB_SERVER_BASE_URL}api/join?...&checksum=...`.

### 3. Controller & Routing Setup
- Create `App\Http\Controllers\MeetingController`:
  - `index()`: Displays `meeting.blade.php`.
  - `join(Request $request)`:
    - Calls `BigBlueButtonService` to create a new unique classroom.
    - Obtains the moderator join URL.
    - Returns `redirect()->away($joinUrl)` to send the browser directly into BBB classroom.
- Define web routes in `routes/web.php`:
  - `GET /` -> `MeetingController@index` (named `meeting.index`)
  - `POST /join-meeting` -> `MeetingController@join` (named `meeting.join`)

### 4. Blade View (`resources/views/meeting.blade.php`)
- Build HTML template containing:
  - Header: "BigBlueButton Classroom Proof of Concept".
  - Form submitting to route `meeting.join` (with `@csrf`).
  - Submit Button: `Join Meeting`.
  - Error message alert if classroom creation fails.

---

## Validation & Verification Plan

1. **Environment & App Boot Check**:
   - Verify routes via `php artisan route:list`.
2. **BBB API Integration Test**:
   - Test room creation against `https://test30.bigbluebutton.org/bigbluebutton/`.
   - Confirm XML parsing succeeds and `returncode` returns `SUCCESS`.
3. **End-to-End User Flow**:
   - Access home page `GET /`.
   - Click **Join Meeting**.
   - Verify browser redirects to `https://test30.bigbluebutton.org/bigbluebutton/api/join?...`.
   - Verify user enters BBB room as Moderator/Tutor.
   - Return to `GET /`, click **Join Meeting** again, verify a *new unique* meeting ID is created and a fresh room opens.
