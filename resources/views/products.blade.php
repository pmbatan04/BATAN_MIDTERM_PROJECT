<x-layouts.app :title="__('Products')">
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

        {{-- Main Inventory Management Container --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/50">
            <div class="flex h-full flex-col p-6">

                {{-- Add New Product Form (Form remains similar, just contextual changes) --}}
                <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Record New Inventory Item</h2>

                    <form action="{{ route('products.store') }}" method="POST" class="grid gap-4 md:grid-cols-4">
                        @csrf

                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name / SKU</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Product Name or SKU" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                            <select name="category_id" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Select Category</option>
                                @foreach($categories as $cate)
                                    <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Quantity</label>
                            <input type="number" name="unit" value="{{ old('unit') }}" placeholder="Current stock count" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cost/Unit (₱)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="Price per unit" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Description / Notes</label>
                            <textarea name="description" rows="2" placeholder="Detailed product description or location" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-4 mt-2">
                            <button type="submit" class="rounded-lg bg-indigo-600 px-8 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-md shadow-indigo-500/20">
                                Add Item to Inventory
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Product List Table --}}
                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Current Inventory Stock</h2>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800/70">
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Item Name / SKU</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Category</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Cost/Unit</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Description</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($products as $prod)
                                    <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50" id="room-row-{{ $prod->id }}">
                                        <td class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-100">{{ $prod->name }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">{{ $prod->category ? $prod->category->name : 'N/A' }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-300">₱{{ number_format($prod->price, 2) }}</td>
                                        <td class="px-4 py-3 text-center text-sm font-bold text-teal-600 dark:text-teal-400">{{ $prod->unit }}</td>
                                        <td class="px-4 py-3 text-left text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($prod->description, 35) ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-center text-sm whitespace-nowrap">
                                            <button onclick="editProduct(
                                                {{ $prod->id }},
                                                '{{ addslashes($prod->name) }}',
                                                '{{ $prod->category_id }}',
                                                '{{ $prod->price }}',
                                                '{{ $prod->unit }}',
                                                '{{ addslashes($prod->description) }}'
                                            );" class="text-indigo-600 transition-colors hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                                Edit Stock
                                            </button>
                                            <span class="mx-1 text-gray-400">|</span>
                                            <form action="{{ route('products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: Deleting this item will remove it permanently from inventory. Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Inventory is empty. Use the form above to add your first item!
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

    {{-- Edit Product Modal --}}
    <div id="editProductModal" class="fixed inset-0 hidden items-center justify-center bg-gray-900/60 z-[9999]">
        <div class="w-full max-w-xl rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Update Inventory Item</h2>

            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name / SKU</label>
                        <input type="text" id="edit_product_name" name="name" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select id="edit_category_id" name="category_id" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Select a category</option>
                            @foreach($categories as $cate)
                                <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cost/Unit (₱)</label>
                        <input type="number" step="0.01" id="edit_price" name="price" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Quantity</label>
                        <input type="number" id="edit_unit" name="unit" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Description / Notes</label>
                        <textarea id="edit_description" name="description" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"></textarea>
                    </div>
                    <div class="md:col-span-2 mt-4 flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                            Update Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editProduct(id, name, category_id, price, unit, description) {
            document.getElementById('editProductModal').classList.remove('hidden');
            document.getElementById('editProductModal').classList.add('flex');
            // Update the form action dynamically
            document.getElementById('editProductForm').action = `{{ url('products') }}/${id}`;
            document.getElementById('edit_product_name').value = name;
            document.getElementById('edit_category_id').value = category_id;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_unit').value = unit;
            document.getElementById('edit_description').value = description || '';
        }

        function closeEditModal() {
            document.getElementById('editProductModal').classList.add('hidden');
            document.getElementById('editProductModal').classList.remove('flex');
            document.getElementById('editProductForm').reset();
        }

        // Close modal on escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
</x-layouts.app>