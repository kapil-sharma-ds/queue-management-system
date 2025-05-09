<!-- resources/views/staff/search.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Search</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-6">
    <div class="w-full max-w-xl bg-white shadow-lg rounded-xl p-6">
        <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Search Staff</h1>

        <form action="{{ url('/staff/search') }}" method="POST" class="flex gap-2 mb-6">
            @csrf
            <input
                type="text"
                name="query"
                placeholder="Enter name or email"
                class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
            >
                Search
            </button>
        </form>

        @if(isset($results))
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    @if($query)
                        Search Results for "<span class="text-blue-600">{{ $query }}</span>"
                    @else
                        Search Results
                    @endif
                </h2>

                @if(count($results) > 0)
                    <ul class="space-y-4">
                        @foreach($results as $staff)
                            <li class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <p class="text-lg font-medium text-gray-800">{{ $staff['name'] }}</p>
                                <p class="text-sm text-gray-600">{{ $staff['email'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600">No results found.</p>
                @endif
            </div>
        @else
            <p class="text-gray-500 text-center mt-8">Results will be shown here after you search.</p>
        @endif
    </div>
</body>
</html>
