<x-layout docTitle="Manage avatar">
    <div class='container container--narrow py-md-5'>
        <h2 class='text-center mb-3'>Upload a new Avatar</h2>
        <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="avatar">Choose a new avatar</label>
                <input type="file" class="form-control-file" id="avatar" name="avatar" required>
                @error('avatar')
                <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
</x-layout>