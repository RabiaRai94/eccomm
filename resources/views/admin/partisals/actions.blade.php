<a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
<form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>
