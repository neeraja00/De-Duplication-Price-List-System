<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PL Deduplicator</title>
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
        body { background-color: #f8fafc; overflow-x: hidden; }
        .bg-blobs { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; overflow: hidden; background: #f8fafc; }
        .blob { position: absolute; filter: blur(80px); opacity: 0.6; animation: float 20s infinite alternate ease-in-out; }
        .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(96,165,250,0.3) 0%, rgba(96,165,250,0) 70%); }
        .blob-2 { bottom: -10%; right: -10%; width: 60vw; height: 60vw; background: radial-gradient(circle, rgba(236,72,153,0.2) 0%, rgba(236,72,153,0) 70%); animation-delay: -5s; }
        .blob-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(167,139,250,0.2) 0%, rgba(167,139,250,0) 70%); animation-delay: -10s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(5%, 5%) scale(1.05); } 100% { transform: translate(-5%, 10%) scale(0.95); } }
        
        .glass-panel { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.9); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.6); }
        .page-enter { animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); filter: blur(4px); } to { opacity: 1; transform: translateY(0); filter: blur(0); } }
        
        .btn-primary { background: linear-gradient(135deg, #4f46e5 0%, #ec4899 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3); transition: all 0.3s ease; }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(236, 72, 153, 0.5); transform: translateY(-2px); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md page-enter">
        <div class="flex justify-center">
            <div class="h-12 w-12 rounded-full bg-brand-600 flex items-center justify-center shadow-lg shadow-brand-500/30">
                <i data-lucide="user-plus" class="h-6 w-6 text-white"></i>
            </div>
        </div>
        <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-slate-900">Create an account</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md page-enter" style="animation-delay: 0.1s;">
        <div class="glass-panel py-8 px-4 sm:rounded-2xl sm:px-10">
            @if($errors->any())
                <div id="error-alert" class="rounded-md bg-red-50 p-4 mb-6 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0"><i data-lucide="alert-circle" class="h-5 w-5 text-red-400"></i></div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-slate-400"></i>
                        </div>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 border focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors" placeholder="John Doe">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-slate-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required class="block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 border focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors" placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-slate-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required class="block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 border focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors" placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="check-circle" class="h-5 w-5 text-slate-400"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 border focus:ring-brand-500 focus:border-brand-500 outline-none transition-colors" placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="flex w-full justify-center rounded-md py-2 px-4 text-sm font-semibold btn-primary shadow-sm transition-all">
                        Register
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-600">Already have an account? <a href="{{ route('login') }}" class="font-medium text-brand-600 hover:text-brand-500">Sign in</a></p>
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
