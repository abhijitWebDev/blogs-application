<x-profile  :sharedDara="$sharedData" docTitle="who {{$sharedData['user']}} Follows">
    <div class="list-group">
      @foreach($followings as $following)
      <a href="/profile/{{$following->userFollowers->username}}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="{{$following->userFollowers->avatar}}" />
        {{$following->userFollowers->username}}
      </a>
      @endforeach()
    </div>
  </x-profile>