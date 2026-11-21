@php $editing = $user->exists; $input = 'mt-1 block w-full rounded-md border-stone-300 text-sm shadow-sm focus:border-teal-700 focus:ring-teal-700'; @endphp
<x-app-layout>
    <x-slot name="header">{{ $editing ? 'Edit user' : 'Add user' }}</x-slot>

    <form method="POST" action="{{ $editing ? route('users.update', $user) : route('users.store') }}"
          class="max-w-xl space-y-5 rounded-lg border border-stone-200 bg-white p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div>
            <label for="name" class="text-sm font-medium">Full name</label>
            <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="{{ $input }}">
            @error('name') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="text-sm font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $input }}">
            @error('email') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="role" class="text-sm font-medium">Role</label>
            <select id="role" name="role" class="{{ $input }}">
                @foreach (\App\Models\User::ROLES as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('role') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="text-sm font-medium">Password</label>
            <input id="password" type="password" name="password" autocomplete="new-password" @required(! $editing) class="{{ $input }}">
            @if ($editing) <p class="mt-1 text-xs text-slate-500">Leave blank to keep the current password.</p> @endif
            @error('password') <p class="mt-1 text-sm text-rose-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="text-sm font-medium">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="{{ $input }}">
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) class="rounded border-stone-300 text-teal-800 focus:ring-teal-700">
            Account is active
        </label>

        <div class="flex gap-3">
            <button class="rounded-md bg-teal-800 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-700 focus-visible:ring-offset-2">{{ $editing ? 'Save changes' : 'Add user' }}</button>
            <a href="{{ route('users.index') }}" class="rounded-md px-4 py-2 text-sm font-medium text-slate-600 hover:bg-stone-100">Cancel</a>
        </div>
    </form>
</x-app-layout>
