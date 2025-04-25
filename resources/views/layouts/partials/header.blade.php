{{-- layouts/partials/header.blade.php --}}

@php
    use Illuminate\Support\Facades\Auth;
@endphp


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Olshop Fajri') }}</title>

    <!-- Fonts & Styles -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .wrapper { flex: 1; display: flex; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 1rem; height: 100vh; position: sticky; top: 0; }
        .main-content { flex-grow: 1; padding: 1.5rem; background-color: #ffffff; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Olshop Fajri') }}
                </a>
                <div class="d-flex">
                    @auth
                        <span class="me-3">{{ Auth::user()->name }}</span>
                        <a class="btn btn-sm btn-outline-danger" href="#" onclick="confirmLogout(event)">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class="btn btn-sm btn-outline-primary me-2" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-sm btn-outline-success" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </nav>
