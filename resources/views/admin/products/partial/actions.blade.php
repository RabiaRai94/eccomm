<a href="{{ route('variants.edit', $variant->id) }}" class="btn btn-primary btn-sm">Edit</a>
<form action="{{ route('variants.destroy', $variant->id) }}" method="POST" style="display: inline;">
     @csrf
     @method('DELETE')
     <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
</form>