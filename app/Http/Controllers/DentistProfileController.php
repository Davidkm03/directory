<?php

namespace App\Http\Controllers;

use App\Models\DentistProfile;
use App\Models\DentistDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class DentistProfileController extends Controller
{
    /**
     * Display the dentist profile for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->dentistProfile()->with('documents')->first();

        return response()->json([
            'profile' => $profile,
            'user' => $user->only(['id', 'name', 'email', 'specialty', 'license_number', 'profile_photo_path']),
        ]);
    }

    /**
     * Store a newly created dentist profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->dentistProfile) {
            return response()->json(['message' => 'Profile already exists. Use update instead.'], 422);
        }

        $validatedData = $request->validate([
            'registration_number' => 'required|string|unique:dentist_profiles',
            'university' => 'required|string',
            'graduation_year' => 'required|integer|min:1950|max:' . date('Y'),
            'professional_experience' => 'nullable|string',
            'services_offered' => 'nullable|array',
            'languages' => 'nullable|array',
            'office_address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'phone_number' => 'required|string',
            'website_url' => 'nullable|url',
            'social_media' => 'nullable|array',
            'accepts_insurance' => 'boolean',
            'insurance_networks' => 'nullable|array',
        ]);

        $profile = new DentistProfile($validatedData);
        $profile->user_id = $user->id;
        $profile->verification_status = 'pending';
        $profile->save();

        return response()->json([
            'message' => 'Profile created successfully',
            'profile' => $profile,
        ], 201);
    }

    /**
     * Update the dentist profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->dentistProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        $validatedData = $request->validate([
            'registration_number' => [
                'required',
                'string',
                Rule::unique('dentist_profiles')->ignore($profile->id)
            ],
            'university' => 'required|string',
            'graduation_year' => 'required|integer|min:1950|max:' . date('Y'),
            'professional_experience' => 'nullable|string',
            'services_offered' => 'nullable|array',
            'languages' => 'nullable|array',
            'office_address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'phone_number' => 'required|string',
            'website_url' => 'nullable|url',
            'social_media' => 'nullable|array',
            'accepts_insurance' => 'boolean',
            'insurance_networks' => 'nullable|array',
        ]);

        // If profile was previously approved and certain fields changed, revert to pending
        $criticalFields = ['registration_number', 'university', 'graduation_year'];
        if ($profile->verification_status === 'approved' && $this->criticalFieldsChanged($profile, $validatedData, $criticalFields)) {
            $profile->verification_status = 'pending';
            $profile->verified_at = null;
            $profile->verified_by = null;
        }

        $profile->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile->fresh(),
        ]);
    }

    /**
     * Check if critical fields have been changed.
     *
     * @param  DentistProfile  $profile
     * @param  array  $newData
     * @param  array  $criticalFields
     * @return bool
     */
    private function criticalFieldsChanged(DentistProfile $profile, array $newData, array $criticalFields): bool
    {
        foreach ($criticalFields as $field) {
            if (isset($newData[$field]) && $profile->{$field} != $newData[$field]) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Upload a document for profile verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadDocument(Request $request)
    {
        $user = Auth::user();
        $profile = $user->dentistProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        $validatedData = $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
            'document_type' => 'required|string',
        ]);

        $file = $request->file('document');
        $originalFilename = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dentist-documents/' . $profile->id, $filename, 'public');

        $document = new DentistDocument([
            'dentist_profile_id' => $profile->id,
            'document_type' => $validatedData['document_type'],
            'file_path' => $path,
            'original_filename' => $originalFilename,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'status' => 'pending',
        ]);

        $document->save();

        // If profile was previously rejected, revert to pending when new documents are uploaded
        if ($profile->verification_status === 'rejected') {
            $profile->verification_status = 'pending';
            $profile->save();
        }

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document,
        ], 201);
    }

    /**
     * Remove a document.
     *
     * @param  int  $documentId
     * @return \Illuminate\Http\Response
     */
    public function removeDocument($documentId)
    {
        $user = Auth::user();
        $profile = $user->dentistProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        $document = DentistDocument::where('id', $documentId)
            ->where('dentist_profile_id', $profile->id)
            ->first();

        if (!$document) {
            return response()->json(['message' => 'Document not found.'], 404);
        }

        // Only allow deletion if document is not approved
        if ($document->status === 'approved') {
            return response()->json(['message' => 'Cannot delete an approved document.'], 403);
        }

        // Delete the file
        Storage::disk('public')->delete($document->file_path);
        
        // Delete the record
        $document->delete();

        return response()->json(['message' => 'Document removed successfully']);
    }

    /**
     * Get the verification status of the profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function verificationStatus()
    {
        $user = Auth::user();
        $profile = $user->dentistProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found.'], 404);
        }

        return response()->json([
            'status' => $profile->verification_status,
            'notes' => $profile->verification_notes,
            'verified_at' => $profile->verified_at,
            'documents' => $profile->documents,
        ]);
    }

    /**
     * Get a public profile for a dentist.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function publicProfile($userId)
    {
        $user = User::dentists()->findOrFail($userId);
        $profile = $user->verifiedDentistProfile;

        if (!$profile) {
            return response()->json(['message' => 'Verified profile not found.'], 404);
        }

        // Return public data only
        return response()->json([
            'user' => $user->only(['id', 'name', 'specialty', 'biography', 'profile_photo_path']),
            'profile' => $profile->only([
                'university', 'graduation_year', 'professional_experience', 
                'services_offered', 'languages', 'office_address', 'city',
                'state', 'postal_code', 'phone_number', 'website_url', 
                'social_media', 'accepts_insurance', 'insurance_networks'
            ]),
        ]);
    }
}
