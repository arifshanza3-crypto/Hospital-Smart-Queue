<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'token_number',
        'patient_id',
        'patient_name',
        'doctor_id',  // ✅ Added
        'phone',
        'email',
        'department',
        'type',
        'status',
        'estimated_time',
        'position',
        'called_at',
        'started_at',
        'completed_at',
        'created_at'
    ];

    protected $casts = [
        'patient_id' => 'string',
        'position' => 'integer',
        'estimated_time' => 'integer',
        'called_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
    ];
    
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * ✅ Relationship with Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * ✅ Dynamic Estimated Time Calculate Karne Ke Liye
     */
    public function getDynamicEstimatedTime()
    {
        if (in_array($this->status, ['completed', 'serving', 'cancelled', 'missed'])) {
            return 0;
        }

        $aheadCount = Token::where('status', 'waiting')
            ->where('position', '<', $this->position)
            ->count();

        $timePerPatient = 15;
        $totalTime = $aheadCount * $timePerPatient;

        return $totalTime;
    }

    /**
     * ✅ Dynamic Waiting Time Calculate Karne Ke Liye
     */
    public function getDynamicWaitingTime()
    {
        if (in_array($this->status, ['completed', 'serving', 'cancelled', 'missed'])) {
            return 0;
        }

        $aheadCount = Token::where('status', 'waiting')
            ->where('position', '<', $this->position)
            ->count();

        return $aheadCount * 15;
    }

    /**
     * ✅ Dynamic Position Calculate Karne Ke Liye
     */
    public function getDynamicPosition()
    {
        if (in_array($this->status, ['completed', 'serving', 'cancelled', 'missed'])) {
            return 0;
        }

        $position = Token::where('status', 'waiting')
            ->where('position', '<=', $this->position)
            ->count();

        return $position;
    }
}