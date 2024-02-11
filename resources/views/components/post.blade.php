<a href="/post/{{$post->id}}" class="list-group-item list-group-item-action" style="border: 1px solid #000; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);">
    <img class="avatar-tiny" src="{{$post->user->avatar}}" />
    <strong>{{$post->title}}</strong>
    <span class="text-muted small"> 
        @if(!isSet($hideAuthor))
        by 
        {{ucfirst($post->user->username)}}
        @endif
        on 
        {{$post->created_at->format('F j, Y')}}
    </span> 
  </a>