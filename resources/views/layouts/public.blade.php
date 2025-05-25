<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $store->name ?? config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
        }
        
        .nav-link:hover {
            color: white !important;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 25px;
            padding: 6px 18px;
            font-weight: 500;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
        }
        
        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('store.public.home', $store) }}">
                @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" style="height: 30px; margin-right: 10px;">
                @else
                    <i class="fas fa-store me-2"></i>
                @endif
                {{ $store->name }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('store.public.home', $store) }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    @if($store->phone)
                        <li class="nav-item">
                            <a class="nav-link" href="tel:{{ $store->phone }}">
                                <i class="fas fa-phone me-1"></i>{{ $store->phone }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ $store->name }}</h5>
                    @if($store->description)
                        <p>{{ $store->description }}</p>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Contact</h5>
                    @if($store->phone)
                        <p><i class="fas fa-phone me-2"></i>{{ $store->phone }}</p>
                    @endif
                    @if($store->address)
                        <p><i class="fas fa-map-marker-alt me-2"></i>{{ $store->address }}</p>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
