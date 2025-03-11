<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">Create Account</h2>
        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Name" class="w-full p-2 mb-2 border rounded">
            <input type="email" name="email" placeholder="Email" class="w-full p-2 mb-2 border rounded">
            <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-2 border rounded">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full p-2 mb-2 border rounded">
            {{-- <a class="link mt-4" href="{{route('login')}}">you have an account?</a> --}}
            <button type="submit" class="w-full bg-black text-white p-2 rounded mt-4">Sign up</button>
        </form>
    </div>
</body>
</html>
