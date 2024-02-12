<x-profile :sharedData="$sharedData" docTitle="{{$sharedData['user']}}'s Followers">
    @include('profile-followers-only')
  </x-profile>