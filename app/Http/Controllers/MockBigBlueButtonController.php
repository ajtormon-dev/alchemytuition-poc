<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MockBigBlueButtonController extends Controller
{
    /**
     * Mock BigBlueButton API /create endpoint.
     */
    public function create(Request $request)
    {
        $meetingID = $request->query('meetingID', 'room-' . Str::uuid());
        $name = $request->query('name', 'BigBlueButton Classroom');
        $attendeePW = $request->query('attendeePW', 'att_123');
        $moderatorPW = $request->query('moderatorPW', 'mod_123');
        $createTime = time() * 1000;
        $internalMeetingID = Str::slug($name) . '-' . Str::random(6);

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<response>
  <returncode>SUCCESS</returncode>
  <meetingID>{$meetingID}</meetingID>
  <internalMeetingID>{$internalMeetingID}</internalMeetingID>
  <parentMeetingID>bbb-none</parentMeetingID>
  <attendeePW>{$attendeePW}</attendeePW>
  <moderatorPW>{$moderatorPW}</moderatorPW>
  <createTime>{$createTime}</createTime>
  <voiceBridge>70000</voiceBridge>
  <dialNumber>613-555-1234</dialNumber>
  <createDate>Fri Sep 04 2026</createDate>
  <hasUserJoined>false</hasUserJoined>
  <duration>0</duration>
  <hasBeenForciblyEnded>false</hasBeenForciblyEnded>
  <messageKey></messageKey>
  <message></message>
</response>
XML;

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Mock BigBlueButton API /join endpoint (Renders classroom environment).
     */
    public function join(Request $request)
    {
        $meetingID = $request->query('meetingID', 'Unknown Room');
        $fullName = $request->query('fullName', 'Moderator / Tutor');
        $password = $request->query('password', '');

        return view('mock_bbb_room', [
            'meetingID' => $meetingID,
            'fullName' => $fullName,
            'role' => 'Moderator / Tutor',
        ]);
    }
}
