@props(['tone' => 'slate'])
@php
    $map = ['green' => 'ok', 'amber' => 'warn', 'red' => 'error', 'blue' => 'accent', 'teal' => 'accent', 'slate' => 'neutral'];
@endphp
<span {{ $attributes->class(['badge', 'badge--'.($map[$tone] ?? 'neutral')]) }}>{{ $slot }}</span>
