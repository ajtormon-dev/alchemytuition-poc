<?php

namespace Tests\Feature;

use App\Services\BigBlueButtonService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MeetingTest extends TestCase
{
    public function test_meeting_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Join Meeting');
        $response->assertSee('BigBlueButton Classroom');
    }

    public function test_checksum_calculation_is_correct(): void
    {
        $service = new BigBlueButtonService();
        // sha1("create" + queryString + salt)
        $action = 'create';
        $queryString = 'name=Test';
        $expectedChecksum = sha1('createname=Test8cd803242780775d5065e8942b0c3924');

        $this->assertEquals($expectedChecksum, $service->buildChecksum($action, $queryString));
    }

    public function test_join_meeting_creates_room_and_redirects_to_bbb(): void
    {
        Http::fake([
            '*/api/create*' => Http::response(
                '<?xml version="1.0"?><response><returncode>SUCCESS</returncode><meetingID>test-room-1</meetingID></response>',
                200
            ),
        ]);

        $response = $this->post('/join-meeting');

        $response->assertStatus(302);
        $redirectUrl = $response->headers->get('Location');
        
        $this->assertStringContainsString('/api/join?', $redirectUrl);
        $this->assertStringContainsString('fullName=Moderator+%2F+Tutor', $redirectUrl);
        $this->assertStringContainsString('redirect=true', $redirectUrl);
        $this->assertStringContainsString('checksum=', $redirectUrl);
    }

    public function test_each_join_click_creates_a_unique_classroom(): void
    {
        Http::fake([
            '*/api/create*' => Http::response(
                '<?xml version="1.0"?><response><returncode>SUCCESS</returncode></response>',
                200
            ),
        ]);

        $res1 = $this->post('/join-meeting');
        $url1 = $res1->headers->get('Location');

        $res2 = $this->post('/join-meeting');
        $url2 = $res2->headers->get('Location');

        $this->assertNotEquals($url1, $url2);
    }

    public function test_mock_bbb_create_endpoint_returns_valid_xml(): void
    {
        $response = $this->get('/mock-bbb/api/create?meetingID=room-test');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');
        $response->assertSee('<returncode>SUCCESS</returncode>', false);
    }

    public function test_mock_bbb_join_endpoint_renders_classroom(): void
    {
        $response = $this->get('/mock-bbb/api/join?meetingID=room-test&fullName=Tutor');

        $response->assertStatus(200);
        $response->assertSee('BigBlueButton Interactive Classroom');
        $response->assertSee('room-test');
    }
}
