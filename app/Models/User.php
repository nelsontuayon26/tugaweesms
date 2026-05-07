<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'role_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'username',
        'email',
        'password',
        'password_updated_at',
        'photo',
        'is_active',
          'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_updated_at' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
        'two_factor_enabled' => 'boolean',
    ];

    // Default settings
public function getSettingsAttribute($value)
{
    $defaults = [
        'profile_visible' => true,
        'email_visible_to_students' => false,
        'show_last_active' => true,
    ];

    // decode JSON if $value is a string
    $value = is_string($value) ? json_decode($value, true) : $value;

    // fallback to empty array if null
    return array_merge($defaults, $value ?? []);
}
    public function role()
{
    return $this->belongsTo(Role::class);
}

public function student()
{
    return $this->hasOne(Student::class);
}


 public function sections()
    {
        return $this->hasMany(Section::class, 'teacher_id');
    }

     public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function notificationSettings()
    {
        return $this->hasOne(UserNotificationSetting::class);
    }

    public function getNotificationSettingsAttribute()
    {
        return UserNotificationSetting::forUser($this->id);
    }

    
public function getFullNameAttribute()
{
    return "{$this->first_name} {$this->middle_name} {$this->last_name}";
}


}