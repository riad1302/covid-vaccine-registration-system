<x-guest-layout>
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
        @if (Route::has('search'))
            <a href="{{ route('search') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Search Status</a>
        @endif
    </div>
    @if(!empty($data) && $data['status'])
        <div class="mt-4">
            @if($data && $data['status'] === 'Not Registered')
                <a href="{{ route('register') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">{{$data['status']}}</a>
            @elseif($data['status'] === 'Scheduled')
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                            {{$data['status']}} <br>
                            Vaccination Date: {{$data['vaccine_center_info']['date']}}<br>
                            Address: {{$data['vaccine_center_info']['address']}}
                        </p>
                    </div>
                </div>
            @else
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                        {{$data['status']}}
                    </p>
                </div>
            @endif
        </div>
    @endif
</x-guest-layout>
