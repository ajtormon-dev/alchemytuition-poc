# BigBlueButton Classroom Interface Customization: Resources Button Plan

## Goal
Add a visible "Resources" button/menu option inside the BigBlueButton (BBB) classroom interface that opens a placeholder URL (`#`).

## Overview & Approach
1. **Mock Classroom Interface (Current Laravel POC)**:
   - Add a visible `📚 Resources` button to the action control bar in `resources/views/mock_bbb_room.blade.php`.
   - Configure the button to open a placeholder link (`#`) or open in a new tab when clicked.
   - Style the button to fit seamlessly with the existing BBB control bar buttons (Mute Audio, Share Webcam, Share Screen, Leave Meeting).

2. **Production BigBlueButton Extension (Architectural Context)**:
   - In production BigBlueButton (BBB 2.6+ / 3.0+), UI extensions are implemented using the **BigBlueButton HTML5 Client Plugin SDK** (`@bigbluebutton/plugin-sdk`).
   - Custom action bar items or top navbar options are registered using `registerActionButton` / `registerNavBarItem` or configured via BBB `userdata-*` join parameters / custom client configuration (`settings.yml`).

---

## Detailed Task Breakdown

### 1. Classroom UI Extension (`resources/views/mock_bbb_room.blade.php`)
- Add a new "Resources" control button to the `.controls` container in the classroom stage.
- Button specification:
  ```html
  <a href="#" target="_blank" class="btn-ctrl" style="background-color: #4f46e5; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
      📚 Resources
  </a>
  ```
- Ensure the button is clearly visible, styled consistently with the rest of the control bar, and opens `#` without disrupting classroom state.

### 2. Feature Tests Update (`tests/Feature/MeetingTest.php`)
- Update `test_mock_bbb_join_endpoint_renders_classroom` in `tests/Feature/MeetingTest.php` to assert that the "Resources" button is present on the rendered classroom page:
  ```php
  $response->assertSee('Resources');
  ```

---

## Validation & Verification Plan

1. **Feature Tests**:
   - Run `vendor/bin/phpunit tests/Feature/MeetingTest.php` or `php artisan test`.
   - Verify all tests pass including classroom rendering and presence of the "Resources" button.

2. **Manual Visual Inspection**:
   - Access `http://127.0.0.1:8000/` in browser.
   - Click **Join Meeting**.
   - Confirm the rendered BBB classroom displays the **📚 Resources** button in the control bar.
   - Click the **Resources** button and verify it points to `#`.
