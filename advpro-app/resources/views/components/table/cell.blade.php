@props(['truncate' => false, 'breakWords' => false, 'status' => null])

<td {{ $attributes->class([
    'px-4 py-3 text-sm',
    'truncate' => $truncate,
    'break-words' => $breakWords,
]) }}>
    @if($status)
        <span @class([
            'inline-block max-w-full truncate px-2 py-1 font-semibold leading-tight rounded-full',
            'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' => $status === 'success',
            'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' => $status === 'danger',
        ])>
            {{ $slot }}
        </span>
    @else
        {{ $slot }}
    @endif
</td>