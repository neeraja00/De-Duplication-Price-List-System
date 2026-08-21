<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PL Deduplicator</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Outfit', 'sans-serif'] }, colors: { brand: { 50: '#eff6ff', 100: '#dbeafe', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 900: '#1e3a8a' }, accent: { 400: '#f472b6', 500: '#ec4899', 600: '#db2777' } } } }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { background-color: #0f172a; overflow-x: hidden; }
        .page-enter { animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); filter: blur(4px); } to { opacity: 1; transform: translateY(0); filter: blur(0); } }
        
        .btn-primary { background: linear-gradient(135deg, #4f46e5 0%, #ec4899 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3); transition: all 0.3s ease; }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(236, 72, 153, 0.5); transform: translateY(-2px); }
    </style>
</head>
<body class="antialiased min-h-screen bg-slate-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
    <div class="sm:mx-auto sm:w-full sm:max-w-md page-enter">
        <div class="flex justify-center">
            <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="shield-check" class="h-6 w-6 text-white"></i>
            </div>
        </div>
        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-white">Admin Login</h2>
        <p class="mt-2 text-center text-sm text-slate-400">
            Authorized Personnel Only
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-800 py-8 px-4 shadow-xl shadow-black/50 sm:rounded-xl sm:px-10 border border-slate-700">
            @if($errors->any())
                <div id="error-alert" class="rounded-md bg-red-900/50 p-4 mb-6 border border-red-500/50">
                    <div class="flex">
                        <div class="flex-shrink-0"><i data-lucide="alert-circle" class="h-5 w-5 text-red-400"></i></div>
                        <div class="ml-3"><h3 class="text-sm font-medium text-red-200">Invalid credentials or unauthorized</h3></div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Admin Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-slate-500"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full pl-10 bg-slate-900 text-white sm:text-sm border-slate-600 rounded-md py-2 border focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" placeholder="admin@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-slate-500"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full pl-10 bg-slate-900 text-white sm:text-sm border-slate-600 rounded-md py-2 border focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        Authenticate
                    </button>
                </div>
            </form>
            <div class="mt-6 pt-6 border-t border-slate-700 text-center">
                <p class="text-xs text-slate-500"><a href="{{ route('login') }}" class="hover:text-slate-300 transition-colors">Return to User Login</a></p>
            </div>
        </div>
    </div>
    <script> 
        lucide.createIcons(); 
        setTimeout(() => {
            const alert = document.getElementById('error-alert');
            if(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 4000);
    </script>
</body>
</html>
