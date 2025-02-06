<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-gray-800 rounded-lg shadow-lg p-8">
        <a href="{{ route('home') }}" class="block text-center mb-6">
            <img src="{{ siteLogo() }}" alt="Logo" class="mx-auto rounded-full">
        </a>

        <form action="{{ route('user.register') }}" method="POST" class="space-y-4">
            @csrf
            <h2 class="text-2xl font-bold text-white text-center mb-4">Create an Account</h2>
            
            <p class="text-gray-400 text-center">
                Already have an account? 
                <a href="#" class="text-blue-500 hover:text-blue-400">Login</a>
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-2">First Name</label>
                    <input 
                        type="text" 
                        name="firstname"
                        placeholder="First Name" value="{{ old('firstname') }}" required
                        class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        
                    >
                </div>
                <div>
                    <label class="block text-gray-300 mb-2">Last Name</label>
                    <input 
                        type="text" 
                     name="lastname"
                                    placeholder="Last Name" value="{{ old('lastname') }}"
                        class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>
            </div>

            <div>
                <label class="block text-gray-300 mb-2">Email Address</label>
                <input 
                    type="email"  name="email" value="{{ old('email') }}"
                    placeholder="Email Address" 
                    class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative">
                    <label class="block text-gray-300 mb-2">Password</label>
                    <input 
                        type="password" 
                        placeholder="Password"  name="password" 
                        class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded-md pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    <button type="button" class="absolute right-3 top-10 text-gray-400 hover:text-white">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="relative">
                    <label class="block text-gray-300 mb-2">Confirm Password</label>
                    <input 
                        type="password"  name="password_confirmation"
                        placeholder="Confirm Password" 
                        class="w-full px-3 py-2 bg-gray-700 text-white border border-gray-600 rounded-md pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                    <button type="button" class="absolute right-3 top-10 text-gray-400 hover:text-white">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center">
                <input 
                   type="checkbox" id="agree"
                                            @checked(old('agree')) name="agree"
                    class="mr-2 bg-gray-700 border-gray-600 rounded text-blue-500 focus:ring-blue-500"
                    required
                >
                <label for="agree" class="text-gray-300">
                    I agree to the 
                    <a href="#" class="text-blue-500 hover:text-blue-400">Terms of Service</a> 
                    and 
                    <a href="#" class="text-blue-500 hover:text-blue-400">Privacy Policy</a>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition duration-300"
            >
                Create Account
            </button>
        </form>
    </div>  @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif


</body>
</html>