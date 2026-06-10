<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Activity Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">Support Tracker</h1>
            <p class="text-slate-400 mt-2">Sign in to manage daily activities</p>
        </div>

        <div class="bg-white rounded-xl shadow-2xl p-8">
            @if ($errors->any())
                <div class="mb-4 rounded-md bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                        Email address
                    </label>
                    <input id="email" name="email" type="email" required autofocus
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                        Password
                    </label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                    <label for="remember" class="ml-2 text-sm text-slate-700">Remember me</label>
                </div>

                <button type="submit"
                        class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-700 text-white font-medium rounded-md shadow-sm transition">
                    Sign in
                </button>
            </form>
        </div>
    </div>

</body>
</html>
