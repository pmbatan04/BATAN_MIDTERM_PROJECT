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

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-4">
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

                        <!-- Product Image Upload -->
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Product Image (Optional)
                            </label>

                            <input
                                type="file"
                                name="photo"
                                accept="image/jpeg,image/png,image/jpg"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm
                                    file:mr-4 file:rounded-md file:border-0
                                    file:bg-indigo-50 file:px-4 file:py-2
                                    file:text-sm file:font-medium file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100
                                    dark:file:bg-indigo-900/30 dark:file:text-indigo-400"
                            >

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                JPG, PNG, or JPEG. Max size 2MB.
                            </p>

                            @error('photo')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="md:col-span-4 mt-2">
                            <button type="submit" class="rounded-lg bg-indigo-600 px-8 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 shadow-md shadow-indigo-500/20">
                                Add Item to Inventory
                            </button>
                        </div>
                    </form>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gradient-to-b from-gray-50 via-white to-gray-100 p-6
            dark:border-gray-700 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

            {{-- Header + Export --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Search & Filter Products
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Find products by name or category
                    </p>
                </div>

                <form method="GET" action="{{ route('products.export') }}" enctype="multipart/form-data">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="category_filter" value="{{ request('category_filter') }}">

                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg
                            bg-indigo-600 px-4 py-2 text-sm font-medium text-white
                            transition hover:bg-indigo-700
                            focus:ring-2 focus:ring-indigo-500/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
                    </button>
                </form>
            </div>

            {{-- Filters --}}
            <form action="{{ route('products.index') }}" method="GET" enctype="multipart/form-data"
                class="grid gap-4 md:grid-cols-3">

                {{-- Search --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Search Product
                    </label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by product name"
                        class="w-full rounded-lg border border-gray-300 bg-white
                            px-4 py-2 text-sm text-gray-900 placeholder-gray-400
                            focus:border-indigo-500 focus:outline-none
                            focus:ring-2 focus:ring-indigo-500/30
                            dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                </div>

                {{-- Category --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Filter by Category
                    </label>
                    <select
                        name="category_filter"
                        class="w-full rounded-lg border border-gray-300 bg-white
                            px-4 py-2 text-sm text-gray-900
                            focus:border-indigo-500 focus:outline-none
                            focus:ring-2 focus:ring-indigo-500/30
                            dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_filter') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 rounded-lg bg-indigo-600 px-4 py-2
                            text-sm font-medium text-white
                            transition hover:bg-indigo-700
                            focus:ring-2 focus:ring-indigo-500/40">
                        Apply
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2
                            text-sm font-medium text-gray-700
                            transition hover:bg-gray-100
                            dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Clear
                    </a>
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
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Photo</th>
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
                                        <td class="px-4 py-3">
                                            @if($prod->photo)
                                                <img
                                                    src="{{ Storage::url($prod->photo) }}"
                                                    alt="{{ $prod->name }}"
                                                    class="h-12 w-12 rounded-full object-cover
                                                        ring-2 ring-indigo-500/40"
                                                >
                                            @else
                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-full
                                                        bg-indigo-100 text-sm font-semibold text-indigo-700
                                                        ring-2 ring-indigo-300
                                                        dark:bg-indigo-900/40 dark:text-indigo-300 dark:ring-indigo-700"
                                                >
                                                    {{ strtoupper(substr($prod->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </td>
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
                                                '{{ addslashes($prod->description) }}',
                                                '{{ $prod->photo }}'
                                            );" class="text-indigo-600 transition-colors hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                                Edit Stock
                                            </button>
                                            <span class="mx-1 text-gray-400">|</span>
                                            <form action="{{ route('products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Do you want to transfer this to trash?')">
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

            <form id="editProductForm" method="POST" enctype="multipart/form-data">
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
                    <!-- Current Product Image Preview -->
                    <div id="currentPhotoPreview" class="mb-3"></div>

                    <input
                        type="file"
                        id="edit_photo"
                        name="photo"
                        accept="image/jpeg,image/png,image/jpg"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700
                            file:mr-4 file:rounded-md file:border-0
                            file:bg-indigo-50 file:px-4 file:py-2
                            file:text-sm file:font-medium file:text-indigo-700
                            hover:file:bg-indigo-100
                            focus:border-indigo-500 focus:outline-none
                            focus:ring-2 focus:ring-indigo-500/30
                            dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200
                            dark:file:bg-indigo-900/30 dark:file:text-indigo-400"
                    />

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Leave empty to keep current product image. JPG, PNG, or JPEG. Max 2MB.
                    </p>
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
        function editProduct(id, name, category_id, price, unit, description, photo) {
            document.getElementById('editProductModal').classList.remove('hidden');
            document.getElementById('editProductModal').classList.add('flex');
            // Update the form action dynamically
            document.getElementById('editProductForm').action = `{{ url('products') }}/${id}`;
            document.getElementById('edit_product_name').value = name;
            document.getElementById('edit_category_id').value = category_id;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_unit').value = unit;
            document.getElementById('edit_description').value = description || '';

            const photoPreview = document.getElementById('currentPhotoPreview');
            if (photo) {
                photoPreview.innerHTML = `
                    <div class="flex items-center gap-3 rounded-lg border border-neutral-200 p-3 dark:border-neutral-700">
                        <img src="/storage/${photo}" alt="${name}" class="h-16 w-16 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Current Photo</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Upload new photo to replace</p>
                        </div>
                    </div>
                `;
            } else {
                photoPreview.innerHTML = `
                    <div class="rounded-lg border border-dashed border-neutral-300 p-4 text-center dark:border-neutral-600">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">No photo uploaded</p>
                    </div>
                `;
            }
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