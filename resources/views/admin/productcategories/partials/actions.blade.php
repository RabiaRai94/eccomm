<div class="d-flex gap-2">
    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
        <i class="fa fa-edit"></i> Edit
    </a>
    <button class="btn btn-danger btn-sm" onclick="deleteCategory({{ $category->id }})">
        <i class="fa fa-trash"></i> Delete
    </button>
</div>

