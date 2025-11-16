<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'todo_list_id',
        'title',
        'image',
        'description',
        'priority_id',
        'status_id',
        'deadline'
    ];
    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function detail()
    {
        return $this->hasOne(TaskDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function todoList()
    {
        return $this->belongsTo(TodoList::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
