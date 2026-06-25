<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Activity Reports') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Card -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 overflow-hidden">
                <div class="px-5 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Custom Date Range
                    </h3>
                </div>
                <form method="GET" action="{{ route('tasks.reports') }}" class="px-5 py-4 flex flex-wrap items-end gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-md shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Query
                    </button>
                </form>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Updates</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">{{ $updates->count() }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Days Covered</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">{{ $groupedByDate->count() }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4">
                    <div class="text-xs font-medium text-green-600 uppercase tracking-wider">Completed</div>
                    <div class="text-2xl font-bold text-green-700 mt-1">{{ $updates->where('status', 'Completed')->count() }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4">
                    <div class="text-xs font-medium text-blue-600 uppercase tracking-wider">Active</div>
                    <div class="text-2xl font-bold text-blue-700 mt-1">{{ $updates->where('status', 'Active')->count() }}</div>
                </div>
            </div>

            <!-- Results grouped by date -->
            @if($groupedByDate->count() > 0)
                @foreach($groupedByDate as $dateKey => $dayUpdates)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-5 overflow-hidden">
                        <!-- Day Header -->
                        <div class="px-5 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    {{ \Carbon\Carbon::parse($dateKey)->format('d') }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($dateKey)->format('l, F j, Y') }}</h4>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $dayUpdates->count() }} update(s)</span>
                        </div>

                        <!-- Updates Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personnel</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($dayUpdates as $update)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-2.5 whitespace-nowrap text-sm font-mono text-gray-600">{{ $update->created_at->format('H:i:s') }}</td>
                                            <td class="px-4 py-2.5 text-sm font-medium text-gray-900">{{ $update->task->name ?? 'Deleted' }}</td>
                                            <td class="px-4 py-2.5 whitespace-nowrap">
                                                @if($update->status === 'Completed')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Done</span>
                                                @elseif($update->status === 'Active')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Active</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">New</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5 text-sm text-gray-600 max-w-xs truncate">{{ $update->remark ?? '—' }}</td>
                                            <td class="px-4 py-2.5 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-6 w-6 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($update->user->name ?? '?', 0, 1)) }}
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $update->user->name ?? 'Unknown' }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-12 text-center">
                    <div class="h-16 w-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">No Results Found</h3>
                    <p class="text-sm text-gray-500 mt-1">No activity updates were recorded between {{ \Carbon\Carbon::parse($startDate)->format('M j, Y') }} and {{ \Carbon\Carbon::parse($endDate)->format('M j, Y') }}.</p>
                    <p class="text-sm text-gray-400 mt-1">Try adjusting the date range above.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
