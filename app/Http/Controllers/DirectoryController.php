<?php

namespace App\Http\Controllers;

use App\Models\DentistProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class DirectoryController extends Controller
{
    /**
     * Tiempo de caché en segundos (30 minutos)
     */
    const CACHE_TIME = 1800;

    /**
     * Obtener directorio de dentistas con búsqueda avanzada y filtros
     */
    public function index(Request $request)
    {
        // Generar una clave de caché única basada en los parámetros de búsqueda
        $cacheKey = 'directory_' . md5(json_encode($request->all()));
        
        // Intentar obtener resultados desde la caché
        if (!$request->has('skipCache') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        // Consulta base: solo perfiles verificados
        $query = DentistProfile::with(['user:id,name,email,role'])
                              ->approved()
                              ->select([
                                  'dentist_profiles.*',
                                  DB::raw('CONCAT(city, ", ", state) as location')
                              ]);
        
        // Aplicar filtros si existen
        $this->applyFilters($query, $request);
        
        // Obtener resultados
        $perPage = $request->input('perPage', 10);
        $results = $query->paginate($perPage);
        
        // Procesar los resultados para incluir URLs de imágenes de perfil
        $results->getCollection()->transform(function ($profile) {
            // Añadir URL de imagen de perfil si existe
            $profile->profile_image = $profile->user && $profile->user->profile_photo_path
                ? asset('storage/' . $profile->user->profile_photo_path)
                : asset('images/default-profile.jpg');
                
            // Formatear servicios para mostrar
            $profile->services_list = is_array($profile->services_offered) 
                ? $profile->services_offered 
                : ($profile->services_offered ? json_decode($profile->services_offered) : []);
                
            // Formatear redes sociales
            $profile->social_networks = is_array($profile->social_media) 
                ? $profile->social_media 
                : ($profile->social_media ? json_decode($profile->social_media) : []);
                
            return $profile;
        });
        
        // Guardar en caché para futuras solicitudes
        if (!$request->has('skipCache')) {
            Cache::put($cacheKey, $results, self::CACHE_TIME);
        }
        
        return $results;
    }
    
    /**
     * Mostrar detalles de un dentista específico
     */
    public function show($id)
    {
        $cacheKey = 'dentist_profile_' . $id;
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $profile = DentistProfile::with(['user:id,name,email,role'])
                               ->approved()
                               ->findOrFail($id);
        
        // Añadir URL de imagen de perfil
        $profile->profile_image = $profile->user && $profile->user->profile_photo_path
            ? asset('storage/' . $profile->user->profile_photo_path)
            : asset('images/default-profile.jpg');
            
        // Formatear servicios para mostrar
        $profile->services_list = is_array($profile->services_offered) 
            ? $profile->services_offered 
            : ($profile->services_offered ? json_decode($profile->services_offered) : []);
            
        // Formatear redes sociales
        $profile->social_networks = is_array($profile->social_media) 
            ? $profile->social_media 
            : ($profile->social_media ? json_decode($profile->social_media) : []);
        
        Cache::put($cacheKey, $profile, self::CACHE_TIME);
        
        return $profile;
    }
    
    /**
     * Aplicar filtros a la consulta base
     */
    private function applyFilters(Builder $query, Request $request)
    {
        // Filtrar por nombre o especialidad
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('specialty', 'like', "%{$search}%");
            });
        }
        
        // Filtrar por servicios
        if ($request->has('services') && is_array($request->services)) {
            $query->withAnyServices($request->services);
        }
        
        // Filtrar por especialidad
        if ($request->has('specialty')) {
            $query->where('specialty', 'like', "%{$request->specialty}%");
        }
        
        // Filtrar por ubicación (ciudad o estado)
        if ($request->has('location')) {
            $location = $request->location;
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('state', 'like', "%{$location}%");
            });
        }
        
        // Filtrar por años de experiencia (mínimo)
        if ($request->has('minExperience') && is_numeric($request->minExperience)) {
            $query->where('experience_years', '>=', (int)$request->minExperience);
        }
        
        // Filtrar por idiomas
        if ($request->has('languages') && is_array($request->languages)) {
            $query->withAnyLanguages($request->languages);
        }
        
        // Ordenar resultados
        $sortBy = $request->input('sortBy', 'created_at');
        $sortDir = $request->input('sortDir', 'desc');
        
        // Validar campos de ordenamiento permitidos
        $allowedSortFields = ['created_at', 'experience_years', 'rating'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDir);
        }
    }
    
    /**
     * Obtener las opciones de filtro para el directorio
     */
    public function getFilterOptions()
    {
        $cacheKey = 'directory_filter_options';
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        // Obtener especialidades únicas
        $specialties = DentistProfile::approved()
                                   ->whereNotNull('specialty')
                                   ->distinct()
                                   ->pluck('specialty')
                                   ->toArray();
        
        // Obtener servicios únicos
        $services = DentistProfile::approved()
                                ->get()
                                ->flatMap(function ($profile) {
                                    return is_array($profile->services_offered) 
                                        ? $profile->services_offered 
                                        : (is_string($profile->services_offered) ? json_decode($profile->services_offered) : []);
                                })
                                ->unique()
                                ->values()
                                ->toArray();
        
        // Obtener ubicaciones únicas (ciudad, estado)
        $locations = DentistProfile::approved()
                                 ->select(DB::raw('DISTINCT CONCAT(city, ", ", state) as location'))
                                 ->pluck('location')
                                 ->toArray();
        
        // Obtener idiomas únicos
        $languages = DentistProfile::approved()
                                 ->get()
                                 ->flatMap(function ($profile) {
                                     return is_array($profile->languages) 
                                        ? $profile->languages 
                                        : (is_string($profile->languages) ? json_decode($profile->languages) : []);
                                 })
                                 ->unique()
                                 ->values()
                                 ->toArray();
        
        $options = [
            'specialties' => $specialties,
            'services' => $services,
            'locations' => $locations,
            'languages' => $languages,
            'experience_range' => [
                'min' => DentistProfile::approved()->min('experience_years') ?? 0,
                'max' => DentistProfile::approved()->max('experience_years') ?? 30
            ]
        ];
        
        Cache::put($cacheKey, $options, self::CACHE_TIME);
        
        return $options;
    }
    
    /**
     * Agregar o quitar un dentista de favoritos
     */
    public function toggleFavorite(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Debes iniciar sesión para marcar favoritos'], 401);
        }
        
        $user = auth()->user();
        $dentistProfile = DentistProfile::approved()->findOrFail($id);
        
        // Verificar si ya está en favoritos
        $favorite = $user->favoriteDentists()->where('dentist_profile_id', $id)->first();
        
        if ($favorite) {
            // Si ya existe, lo eliminamos
            $user->favoriteDentists()->detach($id);
            $message = 'Dentista eliminado de favoritos';
            $isFavorite = false;
        } else {
            // Si no existe, lo agregamos
            $user->favoriteDentists()->attach($id);
            $message = 'Dentista agregado a favoritos';
            $isFavorite = true;
        }
        
        // Invalidar caché de favoritos del usuario
        Cache::forget('favorite_dentists_' . $user->id);
        
        return response()->json([
            'message' => $message,
            'isFavorite' => $isFavorite
        ]);
    }
    
    /**
     * Obtener dentistas favoritos del usuario
     */
    public function getFavorites(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Debes iniciar sesión para ver favoritos'], 401);
        }
        
        $user = auth()->user();
        $cacheKey = 'favorite_dentists_' . $user->id;
        
        if (!$request->has('skipCache') && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $favorites = $user->favoriteDentists()
                          ->with('user:id,name,email,role')
                          ->get()
                          ->map(function($profile) {
                              // Añadir URL de imagen de perfil
                          $profile->profile_image = $profile->user && $profile->user->profile_photo_path
                                  ? asset('storage/' . $profile->user->profile_photo_path)
                                  : asset('images/default-profile.jpg');
                                  
                              return $profile;
                          });
        
        Cache::put($cacheKey, $favorites, self::CACHE_TIME);
        
        return $favorites;
    }
}
