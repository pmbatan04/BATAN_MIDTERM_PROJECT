<x-layouts.app :title="__('Product Trash')">
    <div class="space-y-6">

        {{-- Success Message --}}
        @if(session('success'))
            <div
                class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200
                       dark:bg-green-900/40 dark:text-green-300 dark:border-green-800"
                x-data="{ show:true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 3000)">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    Product Trash
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Restore or permanently delete products
                </p>
            </div>
            <a href="{{ route('products.index') }}"
               class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Back to Inventory
            </a>
        </div>

        {{-- Summary Card --}}
        <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-5 shadow-sm
                    dark:border-indigo-800 dark:bg-indigo-900/30">
            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                Products in Trash
            </p>
            <p class="mt-1 text-3xl font-bold text-indigo-900 dark:text-indigo-100">
                {{ $products->count() }}
            </p>
        </div>

        {{-- Table Container --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white
                    dark:border-gray-700 dark:bg-gray-900">
            <div class="p-6">

                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Deleted Products
                </h2>

                @if($products->isEmpty())
                    <div class="flex items-center justify-center rounded-lg border border-dashed
                                border-gray-300 p-12 dark:border-gray-700">
                        <div class="text-center">
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                Trash is empty
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-500">
                                No deleted products found.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100 border-b border-gray-200
                                         dark:bg-gray-800 dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300">Image</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300">Product</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300">Category</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300">Price</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300">Stock</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300">Deleted At</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300 text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($products as $product)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800">

                                        {{-- Image --}}
                                        <td class="px-4 py-3">
                                            @if($product->photo)
                                                <img
                                                    src="{{ Storage::url($product->photo) }}"
                                                    class="h-10 w-10 rounded-full object-cover
                                                           ring-2 ring-indigo-500/40"
                                                >
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full
                                                            bg-indigo-100 text-sm font-semibold text-indigo-700
                                                            ring-2 ring-indigo-300
                                                            dark:bg-indigo-900/40 dark:text-indigo-300 dark:ring-indigo-700">
                                                    {{ strtoupper(substr($product->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Name --}}
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $product->name }}
                                        </td>

                                        {{-- Category --}}
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $product->category?->name ?? 'N/A' }}
                                        </td>

                                        {{-- Price --}}
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            ₱{{ number_format($product->price, 2) }}
                                        </td>

                                        {{-- Stock --}}
                                        <td class="px-4 py-3 text-sm font-semibold text-teal-600 dark:text-teal-400">
                                            {{ $product->unit }}
                                        </td>

                                        {{-- Deleted At --}}
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->deleted_at->format('M d, Y') }}
                                            <div class="text-xs">
                                                {{ $product->deleted_at->format('h:i A') }}
                                            </div>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-4 py-3">
                                            <div class="flex justify-end gap-2">

                                                {{-- Restore --}}
                                                <form method="POST" action="{{ route('products.restore', $product->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return confirm('Restore this product?')"
                                                        class="rounded-lg bg-green-600 px-3 py-1.5 text-sm
                                                               font-medium text-white hover:bg-green-700">
                                                        Restore
                                                    </button>
                                                </form>

                                                {{-- Delete Forever --}}
                                                <form method="POST" action="{{ route('products.force-delete', $product->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Permanently delete this product? This cannot be undone!')"
                                                        class="rounded-lg bg-red-600 px-3 py-1.5 text-sm
                                                               font-medium text-white hover:bg-red-700">
                                                        Delete Forever
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layouts.app>