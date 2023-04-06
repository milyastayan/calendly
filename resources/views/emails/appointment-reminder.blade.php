@component('mail::message')
    # Appointment Reminder

    Dear {{ $appointment->guest_name }},

    Your appointment is scheduled for {{ $appointment->start_time->format('l, F jS Y \a\t h:i A') }}.

    Here are the details of your appointment:

    - Guest Name: {{ $appointment->guest_name }}
    - Guest Email: {{ $appointment->guest_email }}
    - Start Time: {{ $appointment->start_time->format('l, F jS Y \a\t h:i A') }}
    - End Time: {{ $appointment->end_time->format('l, F jS Y \a\t h:i A') }}
    - Meeting Link: {{ $appointment->meeting_link }}

    Thank you for choosing us for your appointment needs. We look forward to speaking with you soon!

    Best regards,
    The MSSAQ Team
@endcomponent
