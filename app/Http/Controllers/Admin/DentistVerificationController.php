<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DentistProfile;
use App\Models\DentistDocument;
use App\Models\User;
use App\Jobs\SendVerificationStatusEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DentistVerificationController extends Controller
{
    /**
     * Constructor to apply middleware.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
            }
            return $next($request);
        });
    }

    /**
     * Get list of pending dentist profiles.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingProfiles()
    {
        $profiles = DentistProfile::with(['user', 'documents'])
            ->pending()
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['profiles' => $profiles]);
    }

    /**
     * Get a specific dentist profile with all details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProfile($id)
    {
        $profile = DentistProfile::with(['user', 'documents', 'verifiedBy'])
            ->findOrFail($id);

        return response()->json(['profile' => $profile]);
    }

    /**
     * Approve a dentist profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveProfile(Request $request, $id)
    {
        $profile = DentistProfile::findOrFail($id);
        $admin = Auth::user();

        // Validate that all required documents are provided
        $documents = $profile->documents;
        if ($documents->count() === 0) {
            return response()->json(['message' => 'Cannot approve profile without any documents.'], 422);
        }

        // Update profile status
        $profile->verification_status = 'approved';
        $profile->verification_notes = $request->input('notes');
        $profile->verified_at = now();
        $profile->verified_by = $admin->id;
        $profile->save();

        // Approve all pending documents
        foreach ($documents as $document) {
            if ($document->status === 'pending') {
                $document->status = 'approved';
                $document->admin_notes = 'Approved as part of profile verification';
                $document->save();
            }
        }

        // Queue email notification
        SendVerificationStatusEmail::dispatch($profile->user, 'approved', $profile->verification_notes);

        return response()->json([
            'message' => 'Profile approved successfully',
            'profile' => $profile->fresh()->load(['user', 'documents', 'verifiedBy']),
        ]);
    }

    /**
     * Reject a dentist profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rejectProfile(Request $request, $id)
    {
        $validatedData = $request->validate([
            'notes' => 'required|string',
        ]);
        
        $profile = DentistProfile::findOrFail($id);
        
        // Update profile status
        $profile->verification_status = 'rejected';
        $profile->verification_notes = $validatedData['notes'];
        $profile->save();

        // Queue email notification
        SendVerificationStatusEmail::dispatch($profile->user, 'rejected', $profile->verification_notes);

        return response()->json([
            'message' => 'Profile rejected successfully',
            'profile' => $profile->fresh()->load(['user', 'documents']),
        ]);
    }

    /**
     * Approve a specific document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $documentId
     * @return \Illuminate\Http\Response
     */
    public function approveDocument(Request $request, $documentId)
    {
        $document = DentistDocument::findOrFail($documentId);
        $document->status = 'approved';
        $document->admin_notes = $request->input('notes');
        $document->save();

        return response()->json([
            'message' => 'Document approved successfully',
            'document' => $document,
        ]);
    }

    /**
     * Reject a specific document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $documentId
     * @return \Illuminate\Http\Response
     */
    public function rejectDocument(Request $request, $documentId)
    {
        $validatedData = $request->validate([
            'notes' => 'required|string',
        ]);
        
        $document = DentistDocument::findOrFail($documentId);
        $document->status = 'rejected';
        $document->admin_notes = $validatedData['notes'];
        $document->save();

        return response()->json([
            'message' => 'Document rejected successfully',
            'document' => $document,
        ]);
    }

    /**
     * Get dashboard statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStatistics()
    {
        $stats = [
            'total_dentists' => User::dentists()->count(),
            'pending_profiles' => DentistProfile::pending()->count(),
            'approved_profiles' => DentistProfile::approved()->count(),
            'rejected_profiles' => DentistProfile::rejected()->count(),
            'pending_documents' => DentistDocument::pending()->count(),
            'recent_profiles' => DentistProfile::with('user')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return response()->json(['statistics' => $stats]);
    }
}
