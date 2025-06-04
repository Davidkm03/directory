<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - Configuration - Dental Community</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-blue-800 tracking-tight">Dental Community</h1>
                <p class="mt-3 text-xl text-gray-600">Installer Configuration</p>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800">Platform configuration</h2>
                    <p class="mt-2 text-gray-600">Please fill in all fields to configure your Dental Community.</p>
                </div>

                @if($errors->any())
                <div class="p-4 bg-red-50 border-l-4 border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ $errors->first() }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('installer.process-config') }}" class="p-6 space-y-6">
                    @csrf

                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Application configuration</h3>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700">Application name</label>
                                <div class="mt-1">
                                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name', 'Comunidad Odontológica') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div>
                                <label for="app_url" class="block text-sm font-medium text-gray-700">Application URL</label>
                                <div class="mt-1">
                                    <input type="text" name="app_url" id="app_url" value="{{ old('app_url', Request::root()) }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Full URL where the application is installed</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Database configuration</h3>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label for="db_host" class="block text-sm font-medium text-gray-700">Database host</label>
                                <div class="mt-1">
                                    <input type="text" name="db_host" id="db_host" value="{{ old('db_host', '127.0.0.1') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="db_port" class="block text-sm font-medium text-gray-700">Port</label>
                                <div class="mt-1">
                                    <input type="text" name="db_port" id="db_port" value="{{ old('db_port', '3306') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="db_name" class="block text-sm font-medium text-gray-700">Database name</label>
                                <div class="mt-1">
                                    <input type="text" name="db_name" id="db_name" value="{{ old('db_name') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="db_user" class="block text-sm font-medium text-gray-700">Database user</label>
                                <div class="mt-1">
                                    <input type="text" name="db_user" id="db_user" value="{{ old('db_user') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="db_password" class="block text-sm font-medium text-gray-700">Database password</label>
                                <div class="mt-1">
                                    <input type="password" name="db_password" id="db_password" value="{{ old('db_password') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Administrator configuration</h3>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div>
                                <label for="admin_name" class="block text-sm font-medium text-gray-700">Administrator name</label>
                                <div class="mt-1">
                                    <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="admin_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="mt-1">
                                    <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div>
                                <label for="admin_password" class="block text-sm font-medium text-gray-700">Password</label>
                                <div class="mt-1">
                                    <input type="password" name="admin_password" id="admin_password" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200 flex items-center justify-between">
                        <a href="{{ route('installer.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="mr-2 -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </a>
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Install
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
