<x-guest-layout>
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
