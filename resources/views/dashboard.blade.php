<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-6">
<div class="max-w-7xl mx-auto">
    <header class="flex justify-between items-center py-4 mb-8 border-b border-gray-200">
        <h1 class="text-4xl font-extrabold text-gray-900">Application Dashboard</h1>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition duration-150">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </header>

    <!-- Status Message Area -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
            <p class="font-bold">Success!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if($applications->isEmpty())
        <div class="text-center py-12 bg-white rounded-xl shadow-lg border border-gray-100">
            <p class="text-xl text-gray-500">No applications found yet.</p>
            <a href="{{ route('application.create') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium">
                Go back to form
            </a>
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-xl shadow-2xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / Applicant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bio & DOB</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID / Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resume</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach($applications as $application)
                    <tr class="hover:bg-indigo-50 transition duration-100">
                        <!-- Applicant Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $application->full_name }}</div>
                            <div class="text-xs text-gray-500">#{{ $application->id }} - {{ $application->gender }}</div>
                        </td>

                        <!-- Contact Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $application->email }}</div>
                            <div class="text-sm text-gray-500">{{ $application->phone }}</div>
                        </td>

                        <!-- Bio & DOB -->
                        <td class="px-6 py-4 max-w-sm">
                            <div class="text-sm text-gray-700 truncate">{{ $application->short_bio }}</div>
                            <div class="text-xs text-gray-500 mt-1">DOB: {{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</div>
                        </td>

                        <!-- Payment Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = [
                                    'paid' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                ][$application->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                        </td>

                        <!-- Payment IDs -->
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <p title="Payment ID" class="text-xs">P: {{ $application->razorpay_payment_id ?? 'N/A' }}</p>
                            <p title="Order ID" class="text-xs">O: {{ $application->razorpay_order_id ?? 'N/A' }}</p>
                        </td>

                        <!-- Resume Link -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ Storage::url($application->resume_path) }}" target="_blank"
                               class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                                View Resume
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
</body>
</html>
