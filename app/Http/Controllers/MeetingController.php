<?php

namespace App\Http\Controllers;

use App\Services\BigBlueButtonService;
use Illuminate\Http\Request;
use Exception;

class MeetingController extends Controller
{
    /**
     * Show the BBB Join Meeting test page.
     */
    public function index()
    {
        return view('meeting');
    }

    /**
     * Create a new unique BigBlueButton classroom and redirect user as moderator.
     */
    public function join(Request $request, BigBlueButtonService $bbbService)
    {
        try {
            $meeting = $bbbService->createMeeting();
            $joinUrl = $bbbService->getModeratorJoinUrl(
                $meeting['meetingID'],
                $meeting['moderatorPW'],
                $request->input('full_name', 'Moderator / Tutor')
            );

            return redirect()->away($joinUrl);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
