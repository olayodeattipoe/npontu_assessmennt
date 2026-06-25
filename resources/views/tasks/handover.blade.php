<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daily Handover Log') }}
            </h2>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('tasks.handover') }}" class="flex items-center gap-2">
                    <label for="date" class="text-sm font-medium text-gray-600">Date:</label>
                    <input type="date" name="date" id="date" value="{{ $date }}" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-1.5">
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Date Display -->
            <div class="mb-6 flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h3>
                    <p class="text-sm text-gray-500">
                        @if($updates->flatten()->count() > 0)
                            {{ $updates->flatten()->count() }} update(s) across {{ $updates->count() }} team member(s)
                        @else
                            No activity updates recorded for this date.
                        @endif
                    </p>
                </div>
            </div>

            @if($updates->count() > 0)
                @foreach($updates as $userName => $userUpdates)
                    <!-- Personnel Section -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6 overflow-hidden">
                        <!-- Personnel Header -->
                        <div class="px-5 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                    {{ strtoupper(substr($userName, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">{{ $userName }}</h4>
                                    <p class="text-xs text-gray-500">{{ $userUpdates->first()->user->email ?? '' }}</p>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $userUpdates->count() }} update(s)</span>
                        </div>

                        <!-- Update Timeline -->
                        <div class="divide-y divide-gray-100">
                            @foreach($userUpdates as $update)
                                <div class="px-5 py-3 flex items-start gap-4 hover:bg-gray-50 transition-colors">
                                    <!-- Time -->
                                    <div class="flex-shrink-0 text-right" style="min-width: 72px;">
                                        <div class="text-sm font-semibold text-gray-700">{{ $update->created_at->format('H:i') }}</div>
                                        <div class="text-xs text-gray-400">{{ $update->created_at->format('A') }}</div>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div class="flex-shrink-0 pt-0.5">
                                        @if($update->status === 'Completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Done
                                            </span>
                                        @elseif($update->status === 'Active')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $update->task->name ?? 'Deleted Activity' }}</p>
                                        @if($update->remark)
                                            <p class="text-sm text-gray-600 mt-0.5">{{ $update->remark }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-12 text-center">
                    <div class="h-16 w-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">No Activity Updates</h3>
                    <p class="text-sm text-gray-500 mt-1">No updates were recorded for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}.</p>
                    <p class="text-sm text-gray-400 mt-1">Try selecting a different date above.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
