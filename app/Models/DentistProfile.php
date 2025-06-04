<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DentistProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'registration_number',
        'university',
        'graduation_year',
        'professional_experience',
        'services_offered',
        'languages',
        'office_address',
        'city',
        'state',
        'postal_code',
        'phone_number',
        'website_url',
        'social_media',
        'accepts_insurance',
        'insurance_networks',
        'verification_status',
        'verification_notes',
        'specialty',
        'experience_years',
        'consultation_fee',
        'latitude',
        'longitude',
        'office_hours',
        'rating'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'services_offered' => 'array',
        'languages' => 'array',
        'social_media' => 'array',
        'insurance_networks' => 'array',
        'accepts_insurance' => 'boolean',
        'verified_at' => 'datetime',
        'office_hours' => 'array',
        'consultation_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:1',
        'experience_years' => 'integer'
    ];

    /**
     * Get the user that owns the dentist profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the documents for the dentist profile.
     */
    public function documents()
    {
        return $this->hasMany(DentistDocument::class);
    }

    /**
     * Get the user that verified this profile.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include profiles with a specific verification status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithVerificationStatus(Builder $query, string $status): Builder
    {
        return $query->where('verification_status', $status);
    }

    /**
     * Scope a query to only include pending profiles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Scope a query to only include approved profiles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('verification_status', 'approved');
    }

    /**
     * Scope a query to only include rejected profiles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('verification_status', 'rejected');
    }

    /**
     * Check if the profile is fully verified.
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'approved' && $this->verified_at !== null;
    }

    /**
     * Check if the profile is pending verification.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if the profile is rejected.
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }
    
    /**
     * Get the users that have favorited this dentist profile.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_dentists', 'dentist_profile_id', 'user_id')
                   ->withTimestamps();
    }
    
    /**
     * Scope a query to filter profiles by any of the given services.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $services
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyServices(Builder $query, array $services): Builder
    {
        return $query->where(function ($query) use ($services) {
            foreach ($services as $service) {
                $query->orWhereJsonContains('services_offered', $service);
            }
        });
    }
    
    /**
     * Scope a query to filter profiles by any of the given languages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $languages
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyLanguages(Builder $query, array $languages): Builder
    {
        return $query->where(function ($query) use ($languages) {
            foreach ($languages as $language) {
                $query->orWhereJsonContains('languages', $language);
            }
        });
    }
    
    /**
     * Scope a query to filter profiles that accept any of the given insurance networks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $networks
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyInsuranceNetworks(Builder $query, array $networks): Builder
    {
        return $query->where('accepts_insurance', true)
                      ->where(function ($query) use ($networks) {
                          foreach ($networks as $network) {
                              $query->orWhereJsonContains('insurance_networks', $network);
                          }
                      });
    }
    
    /**
     * Scope a query to filter profiles within a geographic radius (in km).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  float  $lat
     * @param  float  $lng
     * @param  float  $radius
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNearby(Builder $query, float $lat, float $lng, float $radius = 10): Builder
    {
        // Haversine formula to calculate distance based on lat/lng
        $haversine = "(
            6371 * acos(
                cos(radians(?)) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(latitude))
            )
        )";
        
        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->selectRaw("*, {$haversine} AS distance", [$lat, $lng, $lat])
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance');
    }
    
    /**
     * Get the full address.
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->office_address,
            $this->city,
            $this->state,
            $this->postal_code
        ]);
        
        return implode(', ', $parts);
    }
}
