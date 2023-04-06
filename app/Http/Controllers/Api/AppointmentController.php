<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentReminder;
use App\Models\Event;
use Illuminate\Support\Carbon;
use App\Traits\ZoomMeetingTrait;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    use ZoomMeetingTrait;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function store(AppointmentRequest $request, $user_id, $event_id)
    {
        // Find the event and validate that it belongs to the user
        $event = Event::where('user_id', $user_id)->findOrFail($event_id);

        $start_time = Carbon::parse($request->input('start_time'));
        $end_time = $start_time->copy()->addMinutes($event->duration);

        $start_date = Carbon::parse($event->start_date);
        $end_date = Carbon::parse($event->end_date);

        // Validate that the appointment time is within the range of the event start date and end date
        if (!$start_time->between($start_date, $end_date) || !$end_time->between($start_date, $end_date)) {
            return response()->json(['message' => 'The selected time is out of range. Please choose a time within the event range.'], 422);
        }
        // Validate that the appointment time is available
        $conflicting_appointment = $event->appointments()->whereBetween('start_time', [$start_time, $end_time])->exists();
        if ($conflicting_appointment) {
            return response()->json(['message' => 'The selected time is not available. Please choose a different time.'], 422);
        }

        // Create a Zoom meeting
        $zoom_data = [
            'topic' => $event->title,
            'start_time' => $start_time->format('Y-m-d\TH:i:s'),
            'duration' => $event->duration,
            'agenda' => $event->description,
            'host_video' => false,
            'participant_video' => false,
            'waiting_room' => true,
        ];
        $zoom_response = $this->create($zoom_data);
        if ($zoom_response['success']) {
            $link = $zoom_response['data']['join_url'];
        }

        // Create the appointment
        $appointment = $event->appointments()->create([
            'guest_name' => $request->input('guest_name'),
            'guest_email' => $request->input('guest_email'),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'meeting_link' => $link,
            'reminder_time' => $start_time->copy()->subHour(),
        ]);

        $mail = new AppointmentConfirmation($appointment);
        Mail::to($event->user->email)->send($mail);
        Mail::to($appointment->guest_email)->send($mail);

        // Send reminder email
        $reminder_mail = new AppointmentReminder($appointment);
        Mail::to($event->user->email)->later($appointment->reminder_time, $reminder_mail);
        Mail::to($appointment->guest_email)->later($appointment->reminder_time, $reminder_mail);

        return new AppointmentResource($appointment);
    }

}
