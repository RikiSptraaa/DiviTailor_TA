<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 mb-10">
        <div class="card w-full bg-base-100 shadow-xl text-center">
            <div class="card-body">
              <h2 class="card-title flex justify-center font-bold">Profile</h2>
              <div class="avatar flex justify-center">
                <div class="w-28 rounded-full">
                    <img src="{{ asset('img/blank-pfp.webp') }}" />
                </div>
              </div>
              <p class="text-lg mt-2 font-bold">{{ $profile->name }}</p>
              <p class="text-md font-bold"">{{ $profile->institute }}</p>
              <p class="text-sm">{{ $profile->email }}</p>
              <p class="text-sm">{{ $profile->phone_number }}</p>
              <p class="text-sm">{{ $profile->address }}</p>
              <p class="text-sm">{{ $profile->city }}</p>
            </div>
          </div>
    </div>
</x-app-layout>