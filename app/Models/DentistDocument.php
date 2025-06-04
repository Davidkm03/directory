<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DentistDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dentist_profile_id',
        'document_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'status',
        'admin_notes'
    ];

    /**
     * Get the dentist profile that owns the document.
     */
    public function dentistProfile()
    {
        return $this->belongsTo(DentistProfile::class);
    }

    /**
     * Scope a query to only include documents with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected documents.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get the document's URL.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }
    
    /**
     * Get the document's public URL for viewing.
     *
     * @return string
     */
    public function getPublicUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
