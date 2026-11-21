<x-app-layout>
    <x-slot name="header">User Management</x-slot>

    <div class="mb-4 flex justify-end">
        <a href="{{ route('users.create') }}" class="rounded-md bg-teal-800 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-700 focus-visible:ring-offset-2">Add user</a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left font-semibold text-slate-600">
                <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Role</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3"><x-badge :tone="$user->isOwner() ? 'blue' : 'slate'">{{ ucfirst($user->role) }}</x-badge></td>
                        <td class="px-4 py-3"><x-badge :tone="$user->is_active ? 'green' : 'red'">{{ $user->is_active ? 'Active' : 'Deactivated' }}</x-badge></td>
                        <td class="px-4 py-3 text-right"><a class="font-medium text-teal-800 hover:underline" href="{{ route('users.edit', $user) }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
