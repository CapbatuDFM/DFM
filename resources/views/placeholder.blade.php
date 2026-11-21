<x-app-layout>
    <x-slot name="header">{{ $title }}</x-slot>

    <div class="empty">
        <h2>{{ $title }} is coming next</h2>
        <p>This module is built in an upcoming step. Its navigation link and permissions are already in place.</p>
        <a href="{{ route('dashboard') }}" class="link">Back to dashboard</a>
    </div>
</x-app-layout>
