<x-layouts.app title="Products">
    <div class="flex h-full w-full flex-1 flex-col gap-2 rounded-xl">
        <div class="text-2xl text-grey-800 dark:text-white font-bold">Products</div>
        <div class="text-sm text-grey-800 dark:text-white"><a class="text-gray-400" wire:navigate href="{{ route('dashboard') }}">Dashboard</a> / Products</div>

        <div class="relative mt-4 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <table id="productTable" class="w-full border rounded-lg display" style="width:100%">
                <thead>
                    <tr class="bg-sky-600 text-white dark:bg-sky-200 dark:text-black">
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Price</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#productTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('products.data') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'price', name: 'price' },
                    ]
                });
            });
        </script>
    @endpush
</x-layouts.app>
