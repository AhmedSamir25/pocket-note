<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">login</h2>

        @if ($errors->has('login'))
            <div class="mb-4 p-2 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $errors->first('login') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="w-full p-2 mb-2 border rounded">
            <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-2 border rounded">
            <button type="submit" class="w-full bg-black text-white p-2 mt-4  rounded">login</button>
        </form>
    </div>
</body>
</html>
