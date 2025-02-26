<div class="d-flex gap-2">
    <a href="{{ route('variants.show', $variant->id) }}" class="btn btn-info btn-sm">
        <i class="fas fa-eye"></i> 
    </a>
    <a href="{{ route('variants.edit', $variant->id) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i> 
    </a>
    <form action="{{ route('variants.destroy', $variant->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</div>
