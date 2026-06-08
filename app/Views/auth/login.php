<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Warehouse Management System</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full">
<div class="min-h-full flex">
    <!-- Left Panel - Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-sidebar via-primary-900 to-primary-800 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 400 400"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs><rect width="400" height="400" fill="url(#grid)"/></svg>
        </div>
        <!-- Floating orbs decoration -->
        <div class="absolute top-20 left-20 w-72 h-72 bg-primary-500/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-32 right-16 w-56 h-56 bg-indigo-400/15 rounded-full blur-3xl animate-float-delayed"></div>
        <div class="relative z-10 flex flex-col justify-center px-16 text-white">
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center mb-8 shadow-xl ring-1 ring-white/20">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h1 class="text-4xl font-bold mb-4">Warehouse<br>Management System</h1>
            <p class="text-lg text-gray-300 leading-relaxed max-w-md">Enterprise-grade inventory tracking, supply request management, and real-time analytics for your warehouse operations.</p>
            <div class="mt-12 grid grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center ring-1 ring-white/10 hover:bg-white/15 transition-colors duration-300">
                    <p class="text-2xl font-bold">100%</p><p class="text-xs text-gray-300 mt-1">Uptime</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center ring-1 ring-white/10 hover:bg-white/15 transition-colors duration-300">
                    <p class="text-2xl font-bold">Real-time</p><p class="text-xs text-gray-300 mt-1">Tracking</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-4 text-center ring-1 ring-white/10 hover:bg-white/15 transition-colors duration-300">
                    <p class="text-2xl font-bold">Secure</p><p class="text-xs text-gray-300 mt-1">RBAC</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="flex-1 flex items-center justify-center px-6 py-12 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">WMS</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome back</h2>
                <p class="text-gray-500 text-sm mb-8">Sign in to your account to continue</p>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger mb-6">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mb-6">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
                <?php endif; ?>

                <form action="/login" method="POST" class="space-y-5">
                    <?= csrf_field() ?>
                    <div>
                        <label for="login" class="form-label">Username or Email</label>
                        <input type="text" id="login" name="login" value="<?= old('login') ?>" class="form-input" placeholder="Enter username or email" required autofocus>
                    </div>
                    <div>
                        <label for="password" class="form-label">Password</label>
                        <div class="password-wrapper relative">
                            <input type="password" id="password" name="password" class="form-input pr-11" placeholder="Enter password" required>
                            <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition-colors" tabindex="-1">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary w-full py-3 text-base">
                        Sign In
                    </button>
                </form>
                <br>
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                    <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-3 text-gray-400 font-medium">Or continue with</span></div>
                </div>
                <br>
                <a href="/auth/google/login" class="flex items-center justify-center gap-3 w-full py-3 border border-gray-200 hover:border-gray-300 hover:bg-gray-50 rounded-xl font-semibold shadow-sm text-gray-700 bg-white transition-all duration-200 hover:shadow-md cursor-pointer">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    <span>Continue with Google</span>
                </a>

                <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                    <p class="text-sm text-gray-500 text-center">Don't have an account? <a href="/register" class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Register here</a></p>
                    <p class="text-xs text-gray-400 text-center">Demo: <span class="font-medium text-gray-600">admin</span> / <span class="font-medium text-gray-600">warehouse</span> / <span class="font-medium text-gray-600">staff</span> — Password: <span class="font-medium text-gray-600">password123</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/app.js"></script>
</body>
</html>
