<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'is_completed',
        'priority',
    ];

    /**
     * Get the priority color class based on priority level
     *
     * @return string
     */
    public function getPriorityColorClass()
    {
        return match($this->priority) {
            1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            3 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        };
    }

    /**
     * Get the priority label
     *
     * @return string
     */
    public function getPriorityLabel()
    {
        return match($this->priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            default => 'Medium',
        };
    }

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
