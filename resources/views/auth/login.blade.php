@extends('layouts.app')

@section('title', 'Login - EPerpus Sawit')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-700">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">EPerpus Sawit</h1>
                <p class="text-gray-600 mt-2">Sistem Perpustakaan Digital</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        placeholder="admin@eperpus.local"
                        required
                    >
                    @error('email')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                >
                    Login
                </button>
            </form>

            <!-- Default Credentials -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm font-semibold text-gray-700 mb-2">Demo Credentials:</p>
                <p class="text-sm text-gray-600">Email: <code class="bg-gray-200 px-2 py-1 rounded">admin@eperpus.local</code></p>
                <p class="text-sm text-gray-600">Password: <code class="bg-gray-200 px-2 py-1 rounded">password123</code></p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-300 text-sm mt-6">
            &copy; 2026 EPerpus Sawit. All rights reserved.
        </p>
    </div>
</div>
@endsection
