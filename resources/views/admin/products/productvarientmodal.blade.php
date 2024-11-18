<div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="addVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVariantModalLabel">Add Product Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="variantForm">
                    @csrf
                    <input type="hidden" name="product_id" id="modal_product_id">
                    <input type="hidden" name="variant_id" id="variant_id"> <!-- Hidden field for the variant ID when editing -->

                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <select name="size" id="size" class="form-select" required>
                            <option value="" disabled selected>Select Size</option>
                            <option value="{{ ProductSizeEnum::EXTRA_SMALL }}">Extra Small</option>
                            <option value="{{ ProductSizeEnum::SMALL }}">Small</option>
                            <option value="{{ ProductSizeEnum::MEDIUM }}">Medium</option>
                            <option value="{{ ProductSizeEnum::LARGE }}">Large</option>
                            <option value="{{ ProductSizeEnum::EXTRA_LARGE }}">Extra Large</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments (Images)</label>
                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="submitVariantForm()">Save Variant</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
   // Open modal for adding a variant
document.querySelectorAll('.add-variant-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Reset the form when the modal is opened for adding
        document.getElementById('variantForm').reset();
        document.getElementById('modal_product_id').value = this.getAttribute('data-product-id');
        document.getElementById('variant_id').value = ''; // Clear the variant ID for adding
        document.getElementById('addVariantModalLabel').textContent = 'Add Product Variant';
        document.querySelector('.btn-primary').textContent = 'Save Variant';
    });
});

// Open modal for editing a variant
function openEditVariantModal(variant) {
    document.getElementById('modal_product_id').value = variant.product_id;
    document.getElementById('variant_id').value = variant.id;
    document.getElementById('size').value = variant.size;
    document.getElementById('price').value = variant.price;
    document.getElementById('stock').value = variant.stock;

    // Change modal title and button text for editing
    document.getElementById('addVariantModalLabel').textContent = 'Edit Product Variant';
    document.querySelector('.btn-primary').textContent = 'Update Variant';

    // Show the modal
    $('#addVariantModal').modal('show');
}

// Handle form submission for both adding and editing
function submitVariantForm() {
    let formData = new FormData(document.getElementById('variantForm'));
    const variantId = document.getElementById('variant_id').value;
    
    // Set the URL and HTTP method based on whether it's add or edit
    let url = "{{ route('variants.store') }}";
    let method = "POST";

    // If we're editing, update the method and URL
    if (variantId) {
        url = `/variants/${variantId}`;
        method = "PUT"; // Use PUT for updating
    }

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw response;
        }
        return response.json();
    })
    .then(data => {
        alert(data.message || 'Variant saved successfully!');
        document.getElementById('variantForm').reset();
        $('#addVariantModal').modal('hide');
        location.reload(); // Reload the page to show the updated list of variants
    })
    .catch(error => {
        if (error.status === 422) {
            error.json().then(errors => {
                alert('Validation error occurred');
            });
        } else {
            alert('An error occurred. Please try again.');
        }
    });
}

</script>