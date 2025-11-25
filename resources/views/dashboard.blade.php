<x-layouts.app :title="__('Inventory Dashboard')">
    <div class="space-y-6">

        @if(session('success'))
            {{-- Success message with a softer green --}}
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 3000)"
                class="rounded-lg bg-green-50 p-4 text-green-700 border border-green-200 dark:bg-green-900/40 dark:text-green-300 dark:border-green-800 transition-all duration-500"
            >
                {{ session('success') }}
            </div>
        @endif

        {{-- Top cards: Inventory Metrics --}}
        <div class="flex flex-col gap-4 md:flex-row">
            
            {{-- Total Items (Products) Card --}}
            <div class="flex-1 relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between py-5">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Total Products</p>
                        <h3 class="mt-1 text-4xl font-extrabold text-gray-900 dark:text-gray-100">{{ $products->count() }}</h3>
                    </div>
                    <div class="rounded-full bg-indigo-100 p-3 dark:bg-indigo-900/30">
                        {{-- Cube Icon --}}
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m-8-4v10l8 4m8-10v10l-8 4m0-10l-8 4m8-4l8-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Product Categories Card --}}
            <div class="flex-1 relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between py-5">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Categories Used</p>
                        <h3 class="mt-1 text-4xl font-extrabold text-gray-900 dark:text-gray-100">{{ $categories->count() }}</h3>
                    </div>
                    <div class="rounded-full bg-teal-100 p-3 dark:bg-teal-900/30">
                        {{-- Folder Icon --}}
                        <svg class="h-6 w-6 text-teal-600 dark:text-teal-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Inventory Value Card (Dynamic Filter) --}}
            <div class="flex-1 relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="GET" class="mb-3">
                    <label for="category_id_filter" class="sr-only">Filter by Category</label>
                    <select id="category_id_filter" name="category_id"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:ring-indigo-500/20 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        <option value="">All Categories</option>

                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ ($categoryId == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Inventory Value ({{ $categoryId ? 'Filtered' : 'Total' }})
                        </p>
                        <h3 class="mt-1 text-2xl font-extrabold text-gray-900 dark:text-gray-100">₱{{ number_format($total, 2) }}</h3>
                    </div>
                    <div class="rounded-full bg-amber-100 p-3 dark:bg-amber-900/30 self-start">
                        {{-- Chart Bar Icon --}}
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2v6c0 1.105 1.343 2 3 2s3-.895 3-2v-6c0-1.105-1.343-2-3-2zM4 5h16M7 9h10"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom image section -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 px-4">
    
        <!-- Image 1 -->
        <div class="rounded-xl overflow-hidden bg-blue-500 shadow-xl ">
            <img src="{{ asset('images/monitor.png') }}" class="w-full h-40 object-cover" alt="">
            <p class="mt-2 text-center text-neutral-700 dark:text-neutral-300 font-medium">
                Monitor
            </p>
        </div>
    
        <!-- Image 2 -->
        <div class="rounded-xl overflow-hidden bg-blue-500 shadow-xl">
            <img src="{{ asset('images/desktop.webp') }}" class="w-full h-40 object-cover" alt="">
            <p class="mt-2 text-center text-neutral-700 dark:text-neutral-300 font-medium">
                System Unit
            </p>
        </div>
    
        <!-- Image 3 -->
        <div class="rounded-xl overflow-hidden shadow-xl bg-blue-500">
            <img src="{{ asset('images/mouse.webp') }}" class="w-full h-40 object-cover" alt="">
            <p class="mt-2 text-center text-neutral-700 dark:text-neutral-300 font-medium">
                Mouse
            </p>
        </div>

        <!-- Image 4 -->
        <div class="rounded-xl overflow-hidden bg-blue-500 shadow-xl">
            <img src="{{ asset('images/keyboard.jpg') }}" class="w-full h-40 object-cover" alt="">
            <p class="mt-2 text-center text-neutral-700 dark:text-neutral-300 font-medium">
                Keyboard
            </p>
        </div>

        <!-- Image 5 -->
        <div class="rounded-xl overflow-hidden bg-blue-500 shadow-xl">
            <img src="{{ asset('images/laptop.avif') }}" class="w-full h-40 object-cover" alt="">
            <p class="mt-2 text-center text-neutral-700 dark:text-neutral-300 font-medium">
                Laptop
            </p>
        </div>

        <!-- Image 6 -->
        <div class="rounded-xl overflow-hidden bg-blue-500 shadow-xl">
            <img src="{{ asset('images/headphone.jpg') }}" class="w-full h-40 object-cover" alt="">
            <p class="mt-2 text-center text-neutral-700 dark:text-neutral-300 font-medium">
                Headphone
            </p>
        </div>
    
    </div>
               
</x-layouts.app>