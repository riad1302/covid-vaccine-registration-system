<x-guest-layout>
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
        @endif
    </div>
    <form action="{{ route('search.status') }}" method="GET">
        <div class="mt-4">
            <x-input-label for="nid" :value="__('NID')" />
            <x-text-input id="nid" class="block mt-1 w-full" type="number" name="nid" :value="old('nid')" required />
            <x-input-error :messages="$errors->get('nid')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Search Status') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
