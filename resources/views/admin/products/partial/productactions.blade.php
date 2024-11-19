<div class="d-flex gap-2">
     <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
          <i class="fa fa-edit"></i> Edit
     </a>
     <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">
          <i class="fa fa-eye"></i> View
     </a>
     <button class="btn btn-danger btn-sm" onclick="deleteProduct({{ $product->id }})">
          <i class="fa fa-trash"></i> Delete
     </button>
</div>