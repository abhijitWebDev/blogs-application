<x-profile :sharedData="$sharedData" docTitle="{{$sharedData['user']}}'s Profile">
  @include('profile-posts-only')
</x-profile>