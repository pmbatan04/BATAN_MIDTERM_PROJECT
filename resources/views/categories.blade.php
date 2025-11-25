<x-layouts.app :title="__('Categories')">
    <div class="space-y-6">

        @if(session('success'))
            {{-- Success message uses the softer green from the inventory dashboard --}}
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 3000)"
                class="rounded-lg bg-green-50 p-4 text-green-700 border border-green-200 dark:bg-green-900/40 dark:text-green-300 dark:border-green-800 transition-all duration-500"
            >
                {{ session('success') }}
            </div>
        @endif

        {{-- Main container uses the standard gray palette and subtle background --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/50">
            <div class="flex h-full flex-col p-6">

                <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Add New Category</h2>

                    <form action="{{ route('categories.store') }}" method="POST" class="grid gap-4 md:grid-cols-2">
                        @csrf

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter category name (e.g., Electronics)" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" rows="2" placeholder="Describe the types of items in this category" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-end">
                             <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-md shadow-indigo-500/20">
                                Add Category
                            </button>
                        </div>

                    </form>
                </div>

                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Existing Categories</h2>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800/70">
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Category Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Description</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($categories as $cate)
                                    <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50" id="room-row-{{ $cate->id }}">
                                        <td class="px-4 py-3 text-center text-sm text-gray-600 dark:text-gray-400">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cate->name }}</td>
                                        <td class="px-4 py-3 text-left text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($cate->description, 70) ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-center text-sm whitespace-nowrap">
                                            <button onclick="editCategory(
                                                {{ $cate->id }},
                                                '{{ addslashes($cate->name) }}',
                                                '{{ addslashes($cate->description) }}'
                                            );" class="text-indigo-600 transition-colors hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                                Edit
                                            </button>
                                            <span class="mx-1 text-gray-400">|</span>
                                            <form action="{{ route('categories.destroy', $cate->id) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: Deleting this category will affect all associated products. Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No categories found. Add your first category above!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editCategoryModal" class="fixed inset-0 hidden items-center justify-center bg-gray-900/60 z-[9999]">
        <div class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Update Category Details</h2>

            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                        <input type="text" id="edit_category" name="name" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea id="edit_description" name="description" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"></textarea>
                    </div>

                    <div class="md:col-span-2 mt-4 flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                            Update Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editCategory(id, name, description) {
            document.getElementById('editCategoryModal').classList.remove('hidden');
            document.getElementById('editCategoryModal').classList.add('flex');
            document.getElementById('editCategoryForm').action = `/categories/${id}`;
            document.getElementById('edit_category').value = name;
            document.getElementById('edit_description').value = description || '';
        }

        function closeEditModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
            document.getElementById('editCategoryModal').classList.remove('flex');
            document.getElementById('editCategoryForm').reset();
        }

        // Optional: Close modal on escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
</x-layouts.app>