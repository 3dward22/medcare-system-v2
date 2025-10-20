@extends('layouts.app')

@section('content')
<main class="pt-20 bg-gradient-to-br from-sky-50 via-white to-teal-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 space-y-3 sm:space-y-0">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="mr-2">📱</span> Guardian SMS Log
            </h1>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline text-sm">
                ← Back to Dashboard
            </a>
        </div>

        <!-- 🔍 Filter + Search -->
        <form method="GET" 
              class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Guardian name, phone, or message"
                       class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <!-- Nurse Filter (Admin Only) -->
            @if(auth()->user()->role === 'admin')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Filter by Nurse</label>
                    <select name="nurse" class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">All Nurses</option>
                        @foreach($nurses as $nurse)
                            <option value="{{ $nurse }}" {{ request('nurse') == $nurse ? 'selected' : '' }}>
                                {{ $nurse }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Date Range -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <!-- Sort -->
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sort By</label>
                <select name="sort" class="w-full border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-blue-400 focus:border-blue-400">
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Newest First</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest First</option>
                </select>
            </div>

            <!-- Apply -->
            <div class="flex items-end sm:col-span-2 lg:col-span-1">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                    Apply Filters
                </button>
            </div>
        </form>

        <!-- 🖥 Desktop Table -->
        <div class="hidden md:block bg-white shadow-md rounded-xl border border-gray-200 overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-50 to-teal-50 text-gray-700 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Guardian</th>
                        <th class="px-4 py-3 text-left font-semibold">Phone</th>
                        <th class="px-4 py-3 text-left font-semibold">Message</th>
                        <th class="px-4 py-3 text-left font-semibold">Sent By</th>
                        <th class="px-4 py-3 text-left font-semibold">Role</th>
                        <th class="px-4 py-3 text-left font-semibold">Date Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $log->guardian_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $log->guardian_phone ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700 max-w-xs truncate" title="{{ $log->message }}">
                                {{ Str::limit($log->message ?? '-', 50) }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $log->sent_by ?? 'N/A' }}</td>
                            <td class="px-4 py-3 capitalize text-gray-600">{{ $log->sent_by_role ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y - h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">No SMS records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 📱 Mobile Card View -->
        <div class="grid grid-cols-1 md:hidden gap-4">
            @forelse($logs as $log)
                <div class="bg-white p-4 rounded-xl shadow border border-gray-100 hover:shadow-md transition">
                    <p class="text-sm text-gray-700"><strong>👨‍👩 Guardian:</strong> {{ $log->guardian_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600"><strong>📞 Phone:</strong> {{ $log->guardian_phone ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-700 mt-1"><strong>💬 Message:</strong> {{ $log->message ?? '-' }}</p>
                    <p class="text-sm text-gray-600 mt-1"><strong>👩‍⚕️ Sent By:</strong> {{ $log->sent_by ?? 'Nurse' }} ({{ $log->sent_by_role ?? 'nurse' }})</p>
                    <p class="text-xs text-gray-500 mt-2"><strong>🕒 Sent:</strong> {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y - h:i A') }}</p>
                </div>
            @empty
                <p class="text-center text-gray-500 py-7">No SMS records found.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</main>
@endsection
