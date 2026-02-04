<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Kulkaltim</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --sage-green: #16a34a;
            --dark-sage: #15803d;
            --bg-soft: #f3f4f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-soft);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
        }

        .brand-logo {
            font-size: 1.8rem;
            color: var(--sage-green);
            margin-bottom: 10px;
            display: inline-block;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #374151;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--sage-green);
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1);
        }

        .btn-login {
            background-color: var(--sage-green);
            color: white;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            border: none;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background-color: var(--dark-sage);
            transform: translateY(-2px);
        }

        .back-link {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 20px;
            display: block;
            text-align: center;
        }
        
        .back-link:hover { color: var(--sage-green); }
    </style>
</head>
<body>

    <div class="container">
        <div class="d-flex justify-content-center">
            
            <div class="login-card text-center text-md-start">
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="text-decoration-none">
                        <span class="brand-logo fw-bold">🍽️ Kulkaltim</span>
                    </a>
                    <h5 class="fw-bold mt-2">Selamat Datang Kembali</h5>
                    <p class="text-muted small">Silakan masuk untuk mengelola data kuliner.</p>
                </div>

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3 text-start">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border: 1px solid #e5e7eb;">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" 
                                   name="email" 
                                   class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                   id="email" 
                                   placeholder="admin@kulkaltim.com" 
                                   value="{{ old('email') }}" 
                                   style="border-radius: 0 12px 12px 0;"
                                   required 
                                   autofocus>
                            
                            @error('email')
                                <div class="invalid-feedback d-block mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 text-start">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border: 1px solid #e5e7eb;">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   class="form-control border-start-0" 
                                   id="password" 
                                   style="border-radius: 0 12px 12px 0;"
                                   placeholder="••••••••" 
                                   required>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        Masuk Dashboard <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                </form>

                <a href="{{ url('/') }}" class="back-link">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>

</body>
</html>