<x-profile :sharedData="$sharedData" docTitle="{{$sharedData['user']}}'s Followers">
    <div class="list-group">
      @foreach($followers as $follow)
      <a href="/profile/{{$follow->userFollowing->username}}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="{{$follow->userFollowing->avatar}}" />
        {{$follow->userFollowing->username}}
      </a>
      @endforeach()
    </div>
  </x-profile>