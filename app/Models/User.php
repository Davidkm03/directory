<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'specialty',
        'license_number',
        'biography',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * Get the dentist profile associated with the user.
     */
    public function dentistProfile()
    {
        return $this->hasOne(DentistProfile::class);
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a dentist.
     *
     * @return bool
     */
    public function isDentist(): bool
    {
        return $this->role === 'dentist';
    }

    /**
     * Check if the user is a patient.
     *
     * @return bool
     */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    /**
     * Scope a query to only include users with a specific role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    /**
     * Scope a query to only include dentists.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDentists(Builder $query): Builder
    {
        return $query->where('role', 'dentist');
    }

    /**
     * Scope a query to only include admins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }

    /**
     * Get verified dentist profile if exists.
     */
    public function verifiedDentistProfile()
    {
        return $this->hasOne(DentistProfile::class)->where('verification_status', 'approved');
    }
    
    /**
     * Get the dentist profiles that the user has favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteDentists(): BelongsToMany
    {
        return $this->belongsToMany(DentistProfile::class, 'favorite_dentists')
                    ->withTimestamps();
    }
    
    /**
     * Check if the user has favorited a specific dentist profile.
     *
     * @param int $dentistProfileId
     * @return bool
     */
    public function hasFavoriteDentist(int $dentistProfileId): bool
    {
        return $this->favoriteDentists()->where('dentist_profile_id', $dentistProfileId)->exists();
    }
}
