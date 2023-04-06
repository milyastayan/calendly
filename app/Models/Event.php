<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'user_id',
        'description',
        'duration',
        'start_date',
        'end_date',
        'location',
        'custom_link',
        'color',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getAvailableAppointments()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $appointments = [];

        while ($start->lessThanOrEqualTo($end)) {
            $appointment = new \stdClass();
            $appointment->date = $start->format('Y-m-d');
            $appointment->times = [];

            $time = Carbon::parse($this->start_date);
            while ($time->lessThan(Carbon::parse($this->end_date))) {
                $appointmentTime = new \stdClass();
                $appointmentTime->time = $time->format('H:i');
                $appointmentTime->available = $this->isAvailable($time);

                $appointment->times[] = $appointmentTime;

                $time->addMinutes($this->duration);
            }

            $appointments[] = $appointment;

            $start->addDay();
        }

        return $appointments;
    }

    private function isAvailable($time)
    {
        // Check if the time is already scheduled
        $scheduledAppointment = Appointment::where('event_id', $this->id)
            ->where('start_time', $time->format('Y-m-d H:i:s'))
            ->first();

        if ($scheduledAppointment) {
            return false;
        }

        return true;
    }


}
