<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Task Board') }}
                </h2>
                <!-- Drag and Drop Hint -->
                <p class="text-xs text-gray-500 mt-1 flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1"></path>
                    </svg>
                    Hint: Drag and drop cards to instantly transition between activity states.
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Filtering Mechanism -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="filterInput" onkeyup="filterTasks()" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow shadow-sm" placeholder="Filter by keyword...">
                </div>
                
                <!-- Add Card Button -->
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-task-modal'))" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Activity
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6" style="height: calc(100vh - 140px); min-height: 600px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col" style="height: 100%;">
            
            <!-- Session Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="height: 100%; display: flex; flex-direction: column; border: 1px solid #e5e7eb;">
                
                <!-- Board Columns -->
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); width: 100%; flex-grow: 1; overflow: hidden; background-color: #f9fafb;">
                    
                    {{-- New Column --}}
                    <div style="display: flex; flex-direction: column; height: 100%; border-right: 3px solid #d1d5db; background-color: #f9fafb;">
                        <div class="px-4 py-3 border-b-2 border-gray-200 bg-white flex items-center justify-between sticky top-0">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center">
                                <div class="w-2 h-2 rounded-full bg-gray-400 mr-2"></div>
                                New
                            </h3>
                            <span class="bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $tasks->where('status', 'New')->count() }}</span>
                        </div>
                        <div class="p-3 flex-1 overflow-y-auto space-y-3 dropzone" data-status="New" ondragover="event.preventDefault(); this.classList.add('bg-gray-100')" ondragleave="this.classList.remove('bg-gray-100')" ondrop="handleDrop(event, 'New')">
                            @foreach($tasks->where('status', 'New') as $task)
                                <div id="task-card-{{ $task->id }}" draggable="true" ondragstart="drag(event, {{ $task->id }})" class="task-card bg-white border-l-4 border-gray-400 rounded-lg shadow-sm hover:shadow-md transition-all p-3 cursor-grab flex flex-col gap-2 relative group">
                                    <div class="text-sm font-medium text-gray-900 leading-tight pr-6">{{ $task->name }}</div>
                                    @if($task->comment)
                                        <div class="text-xs text-gray-600 line-clamp-2 mt-1">{{ $task->comment }}</div>
                                    @endif
                                    <div class="flex justify-between items-end mt-2">
                                        <div class="text-xs text-gray-500 font-mono">ID-{{ $task->id }}</div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="h-6 w-6 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-xs font-bold text-white" title="{{ $task->creator->name ?? 'N/A' }}">
                                                {{ strtoupper(substr($task->creator->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $task->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Active Column --}}
                    <div style="display: flex; flex-direction: column; height: 100%; border-right: 3px solid #d1d5db; background-color: rgba(239, 246, 255, 0.5);">
                        <div class="px-4 py-3 border-b-2 border-gray-200 bg-white flex items-center justify-between sticky top-0">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center">
                                <div class="w-2 h-2 rounded-full bg-blue-500 mr-2"></div>
                                Active
                            </h3>
                            <span class="bg-blue-100 text-blue-700 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $tasks->where('status', 'Active')->count() }}</span>
                        </div>
                        <div class="p-3 flex-1 overflow-y-auto space-y-3 dropzone" data-status="Active" ondragover="event.preventDefault(); this.classList.add('bg-blue-50')" ondragleave="this.classList.remove('bg-blue-50')" ondrop="handleDrop(event, 'Active')">
                            @foreach($tasks->where('status', 'Active') as $task)
                                <div id="task-card-{{ $task->id }}" draggable="true" ondragstart="drag(event, {{ $task->id }})" class="task-card bg-white border-l-4 border-blue-500 rounded-lg shadow-sm hover:shadow-md transition-all p-3 cursor-grab flex flex-col gap-2 relative group">
                                    <div class="text-sm font-medium text-gray-900 leading-tight pr-6">{{ $task->name }}</div>
                                    @if($task->comment)
                                        <div class="text-xs text-gray-600 line-clamp-2 mt-1">{{ $task->comment }}</div>
                                    @endif
                                    <div class="flex justify-between items-end mt-2">
                                        <div class="text-xs text-gray-500 font-mono">ID-{{ $task->id }}</div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="h-6 w-6 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-xs font-bold text-white" title="{{ $task->updater->name ?? $task->creator->name ?? 'N/A' }}">
                                                {{ strtoupper(substr($task->updater->name ?? $task->creator->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $task->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Completed Column --}}
                    <div style="display: flex; flex-direction: column; height: 100%; background-color: rgba(240, 253, 244, 0.5);">
                        <div class="px-4 py-3 border-b-2 border-gray-200 bg-white flex items-center justify-between sticky top-0">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center">
                                <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                                Completed
                            </h3>
                            <span class="bg-green-100 text-green-700 py-0.5 px-2.5 rounded-full text-xs font-medium">{{ $tasks->where('status', 'Completed')->count() }}</span>
                        </div>
                        <div class="p-3 flex-1 overflow-y-auto space-y-3 dropzone" data-status="Completed" ondragover="event.preventDefault(); this.classList.add('bg-green-50')" ondragleave="this.classList.remove('bg-green-50')" ondrop="handleDrop(event, 'Completed')">
                            @foreach($tasks->where('status', 'Completed') as $task)
                                <div id="task-card-{{ $task->id }}" draggable="true" ondragstart="drag(event, {{ $task->id }})" class="task-card bg-white border-l-4 border-green-500 rounded-lg shadow-sm p-3 flex flex-col gap-2 opacity-75 cursor-grab">
                                    <div class="text-sm font-medium text-gray-900 leading-tight line-through pr-6">{{ $task->name }}</div>
                                    @if($task->comment)
                                        <div class="text-xs text-gray-600 line-clamp-2 mt-1">{{ $task->comment }}</div>
                                    @endif
                                    <div class="flex justify-between items-end mt-2">
                                        <div class="text-xs text-gray-500 font-mono">ID-{{ $task->id }}</div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="text-green-600 text-xs font-medium flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Done
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $task->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Create Task Modal (Alpine JS Animated Version) -->
    <div x-data="{ open: false }" x-show="open" @open-task-modal.window="open = true" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <form method="POST" action="{{ route('tasks.create') }}">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Create New Activity</h3>
                                    <p class="text-sm text-gray-500 mt-1">Add a new activity to track on the board.</p>
                                    <div class="mt-4 space-y-4">
                                        
                                        <!-- Task Name -->
                                        <div>
                                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Activity Name</label>
                                            <div class="mt-2">
                                                <input type="text" name="name" id="name" required placeholder="e.g. Daily SMS count in comparison to SMS count from logs" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        <!-- Comment -->
                                        <div>
                                            <label for="comment" class="block text-sm font-medium leading-6 text-gray-900">Remark (Optional)</label>
                                            <div class="mt-2">
                                                <textarea id="comment" name="comment" rows="3" placeholder="Any additional notes..." class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Create</button>
                            <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Remark Modal -->
    <div id="remark-modal" style="display: none;" class="relative z-50">
        <!-- Backdrop -->
        <div id="remark-backdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRemarkModal()"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md" style="animation: slideUp 0.3s ease-out;">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div id="remark-modal-icon" class="h-10 w-10 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="remark-modal-title">Update Status</h3>
                                <p class="text-sm text-gray-500" id="remark-modal-subtitle">Moving task to a new status</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="update-remark" class="block text-sm font-medium text-gray-700 mb-1">Add a remark <span class="text-gray-400">(optional)</span></label>
                            <textarea id="update-remark" rows="3" placeholder="Describe what was done or any notes for handover..." class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="confirmStatusUpdate()" id="remark-confirm-btn" class="inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto bg-blue-600 hover:bg-blue-500">Confirm Update</button>
                        <button type="button" onclick="closeRemarkModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>

    <!-- Drag-and-Drop + Remark Modal JS -->
    <script>
        let pendingTaskId = null;
        let pendingNewStatus = null;

        function drag(ev, taskId) {
            ev.dataTransfer.setData("taskId", taskId);
            ev.target.classList.add('opacity-50');
        }

        document.addEventListener('dragend', function(ev) {
            if(ev.target.classList) {
                ev.target.classList.remove('opacity-50');
            }
            // Remove dropzone highlights
            document.querySelectorAll('.dropzone').forEach(z => {
                z.classList.remove('bg-gray-100', 'bg-blue-50', 'bg-green-50');
            });
        });

        function handleDrop(ev, newStatus) {
            ev.preventDefault();
            let taskId = ev.dataTransfer.getData("taskId");
            if (!taskId) return;

            ev.target.closest('.dropzone')?.classList.remove('bg-gray-100', 'bg-blue-50', 'bg-green-50');

            // Store pending info and open remark modal
            pendingTaskId = taskId;
            pendingNewStatus = newStatus;
            openRemarkModal(newStatus);
        }

        function openRemarkModal(status) {
            const modal = document.getElementById('remark-modal');
            const icon = document.getElementById('remark-modal-icon');
            const title = document.getElementById('remark-modal-title');
            const subtitle = document.getElementById('remark-modal-subtitle');
            const btn = document.getElementById('remark-confirm-btn');
            
            // Reset remark field
            document.getElementById('update-remark').value = '';

            // Style based on status
            if (status === 'Active') {
                icon.className = 'h-10 w-10 rounded-full flex items-center justify-center bg-blue-500';
                title.textContent = 'Mark as Active';
                subtitle.textContent = 'This activity is now in progress.';
                btn.className = 'inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto bg-blue-600 hover:bg-blue-500';
            } else if (status === 'Completed') {
                icon.className = 'h-10 w-10 rounded-full flex items-center justify-center bg-green-500';
                title.textContent = 'Mark as Completed';
                subtitle.textContent = 'This activity will be marked as done.';
                btn.className = 'inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto bg-green-600 hover:bg-green-500';
            } else {
                icon.className = 'h-10 w-10 rounded-full flex items-center justify-center bg-gray-400';
                title.textContent = 'Move to New';
                subtitle.textContent = 'Resetting the activity status.';
                btn.className = 'inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto bg-gray-600 hover:bg-gray-500';
            }

            modal.style.display = 'block';
        }

        function closeRemarkModal() {
            document.getElementById('remark-modal').style.display = 'none';
            pendingTaskId = null;
            pendingNewStatus = null;
        }

        function confirmStatusUpdate() {
            if (!pendingTaskId || !pendingNewStatus) return;

            const remark = document.getElementById('update-remark').value;
            const btn = document.getElementById('remark-confirm-btn');
            btn.textContent = 'Updating...';
            btn.disabled = true;

            fetch(`/tasks/${pendingTaskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: pendingNewStatus, remark: remark })
            })
            .then(response => response.json())
            .then(data => {
                closeRemarkModal();
                window.location.reload();
            })
            .catch(() => {
                closeRemarkModal();
                window.location.reload();
            });
        }

        function filterTasks() {
            const filter = document.getElementById('filterInput').value.toLowerCase();
            document.querySelectorAll('.task-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        }
    </script>
</x-app-layout>