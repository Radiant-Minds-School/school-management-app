<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="shortcut icon" href="{{ asset('images/radiant_logo-removebg-preview.png') }}" type="image/x-icon">
    <title>RMS</title>
</head>

<body class="bg-slate-50 min-h-screen sans-pro flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/radiant_logo.jpeg') }}" alt="Logo" class="h-24 w-24 rounded-full shadow-md">
            </div>
            <h1 class="font-bold text-4xl md:text-5xl text-slate-900 mb-3">Radiant Minds School</h1>
            <p class="text-slate-500 text-lg">Welcome to the school portal</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="font-semibold text-xl text-center text-slate-900 mb-6">Select your login type</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('login') }}" class="block">
                    <div class="border-2 border-slate-200 rounded-xl p-6 text-center hover:border-slate-900 hover:bg-slate-50 transition cursor-pointer group">
                        <div class="mb-3">
                            <svg class="w-12 h-12 mx-auto text-slate-700 group-hover:text-slate-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg text-slate-900 mb-1">Admin</h3>
                        <p class="text-sm text-slate-500">Administrative access</p>
                    </div>
                </a>

                <a href="{{ route('teacher.login') }}" class="block">
                    <div class="border-2 border-slate-200 rounded-xl p-6 text-center hover:border-slate-900 hover:bg-slate-50 transition cursor-pointer group">
                        <div class="mb-3">
                            <svg class="w-12 h-12 mx-auto text-slate-700 group-hover:text-slate-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg text-slate-900 mb-1">Teacher</h3>
                        <p class="text-sm text-slate-500">Teacher access</p>
                    </div>
                </a>

                <a href="{{ route('guardian.login') }}" class="block">
                    <div class="border-2 border-slate-200 rounded-xl p-6 text-center hover:border-slate-900 hover:bg-slate-50 transition cursor-pointer group">
                        <div class="mb-3">
                            <svg class="w-12 h-12 mx-auto text-slate-700 group-hover:text-slate-900 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg text-slate-900 mb-1">Guardian</h3>
                        <p class="text-sm text-slate-500">Parent/Guardian access</p>
                    </div>
                </a>
            </div>
        </div>

        <p class="text-center text-slate-400 text-sm mt-8">
            © {{ date('Y') }} Radiant Minds School. All rights reserved.
        </p>
    </div>
</body>

</html>
