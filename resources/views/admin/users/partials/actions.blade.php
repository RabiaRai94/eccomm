<div class="d-flex gap-2">
    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">
        <i class="fas fa-eye"></i> 
    </a>
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i> 
    </a>
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</div>
