@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'font-inter border-2 border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-2']) !!}>