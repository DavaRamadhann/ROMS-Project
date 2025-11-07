<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role checking methods
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // Relationships - Uncomment setelah model dibuat
    // public function orders()
    // {
    //     return $this->hasMany(Order::class, 'customer_id');
    // }

    // public function chatRooms()
    // {
    //     return $this->hasMany(ChatRoom::class, 'customer_id');
    // }

    // public function chatMessages()
    // {
    //     return $this->hasMany(ChatMessage::class, 'sender_id');
    // }
}