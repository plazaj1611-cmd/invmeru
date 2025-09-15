@props(['type'])

@php
    switch ($type) {
        case 'info':
            $class = 'bg-blue-50 text-blue-800 dark:bg-gray-800 dark:text-blue-400';
            break;

        case 'danger':
            $class = 'bg-red-50 text-red-800 dark:bg-gray-800 dark:text-red-400';
            break;
        
        case 'success':
            $class = 'bg-green-50 text-green-800 dark:bg-gray-800 dark:text-green-400';     
            break;

        case 'warning':
            $class = 'bg-yellow-50 text-yellow-800 dark:bg-gray-800 dark:text-yellow-400';
            break;
        
        case 'dark':
            $class = 'bg-gray-800 text-gray-400 dark:bg-gray-800 dark:text-gray-400';
            break;

        default:
            # code...
            break;
    }
@endphp

<div class="p-4 mb-4 text-sm rounded-lg {{$class}}" role="alert">
        <span class="font-medium">{{$title ?? 'alerta' }}</span> {{$slot}}.
</div>
