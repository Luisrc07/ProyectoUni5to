@props(['headers' => [], 'striped' => false, 'hover' => true])

<div class="w-full rounded-lg shadow-xs overflow-x-auto lg:overflow-visible">
    <div class="w-full rounded-lg shadow-xs overflow-x-auto xl:overflow-visible">
        <table class="w-full whitespace-nowrap min-w-[800px] xl:min-w-full xl:whitespace-normal">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    @foreach($headers as $header)
                        <th class="px-4 py-3">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 {{ $striped ? 'divide-y' : '' }} {{ $hover ? 'hover:bg-gray-50 dark:hover:bg-gray-700' : '' }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>