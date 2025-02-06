<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">   
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
</head>
<body class="bg-black min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="absolute top-4 left-4">
            <a href="#" class="text-white hover:text-gray-300">
                <img src="{{ siteLogo() }}" alt="Logo" class="mx-auto mb-4 rounded-full">
            </a>
        </div>
        <div class="absolute top-4 right-4">
            <a href="{{ route('home') }}" class="text-white hover:text-gray-300">
                <i class="fas fa-home text-2xl"></i>
            </a>
        </div>
        


         
        
        <form method="POST" action="{{ route('user.login') }}" 
             class="bg-black border border-gray-800 rounded-lg p-8">
            @csrf
            <h2 class="text-center text-2xl font-bold text-white mb-6">Log in to your account</h2>
            
            <div class="mb-4">
                <input 
                    type="text"  name="username"
                    placeholder="@lang('Email / Username')"
                    class="w-full p-3 bg-gray-900 text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>
            
            <div class="relative mb-4">
                <input 
                    type="password"  name="password"
                    placeholder="Password"
                    class="w-full p-3 bg-gray-900 text-white border border-gray-700 rounded-md pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <button 
                    type="submit" 
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white"
                    onclick="togglePasswordVisibility()"
                >
                    <i id="passwordToggle" class="fas fa-eye"></i>
                </button>
            </div>
            
            <div class="flex justify-between text-sm text-gray-400 mb-4">
                <a href="{{ route('user.register') }}" class="hover:text-white">Don't have an account?</a>
                <a href="{{ route('user.password.request') }}" class="hover:text-white">Forgot your password?</a>
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-blue-500 text-white py-3 rounded-md hover:bg-blue-600 transition duration-300"
            >
                Log in
            </button>
        </form>
    </div>
    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.querySelector('input[type="password"]');
            const toggleIcon = document.getElementById('passwordToggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>