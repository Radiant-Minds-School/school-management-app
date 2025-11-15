<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/radiant_logo-removebg-preview.png') }}" type="image/x-icon">
    <title>Guardian's Login</title>
</head>

<body class="bg-slate-50 min-h-screen sans-pro flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/radiant_logo.jpeg') }}" alt="Logo" class="h-16 w-16 rounded-full">
            </div>

            <div class="text-center mb-8">
                <h1 class="font-bold text-2xl text-slate-900 mb-2">Welcome back</h1>
                <p class="text-slate-500 text-sm">Please enter your details</p>
            </div>

            @if (session('status'))
                <div class="mb-4 text-green-600 text-sm text-center">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 text-red-600 text-sm text-center">{{ session('error') }}</div>
            @endif

            <form action="{{ route('guardian.login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block font-medium text-sm text-slate-700 mb-1">Email</label>
                        <input
                            autocomplete="email"
                            type="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-lg h-11 px-4 border border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none transition"
                            placeholder="Enter your email"
                            name="email"
                            required
                        />
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block font-medium text-sm text-slate-700 mb-1">Password</label>
                        <input
                            autocomplete="current-password"
                            name="password"
                            type="password"
                            placeholder="Enter your password"
                            class="w-full rounded-lg h-11 px-4 border border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none transition"
                            required
                        />
                        @error('password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember_me"
                            class="rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                        />
                        <span class="ml-2 text-sm text-slate-700">Remember me</span>
                    </label>
                    @if (Route::has('guardian.password.request'))
                        <a href="{{ route('guardian.password.request') }}" class="text-sm text-slate-900 hover:text-slate-600 transition">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button
                    class="w-full mt-6 bg-slate-900 text-white rounded-lg h-11 font-medium hover:bg-slate-800 transition"
                    type="submit">
                    Login
                </button>
            </form>

            <p class="text-slate-500 text-sm text-center mt-6">
                Don't have an account? Contact an administrator
            </p>
        </div>
    </div>
</body>

</html>
