<x-profile  :sharedDara="$sharedData" docTitle="who {{$sharedData['user']}} Follows">
    @include('profile-followings-only')
  </x-profile>