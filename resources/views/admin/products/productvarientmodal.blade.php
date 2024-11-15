<!-- Add Variant Modal (outside the loop) -->
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
                    <!-- Hidden product_id field that will be dynamically populated -->
                    <input type="hidden" name="product_id" id="modal_product_id">

                    <div class="mb-3">
                        <label for="size" class="form-label">Size</label>
                        <input type="number" name="size" id="size" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control" required>
                    </div>

                    <!-- File input for multiple images -->
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
    // Set product_id when the "Add Variant" button is clicked
    document.querySelectorAll('.add-variant-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            document.getElementById('modal_product_id').value = productId;
        });
    });

    function submitVariantForm() {
        let formData = new FormData(document.getElementById('variantForm'));

        fetch("{{ route('variants.store') }}", {
            method: "POST",
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
            alert('Variant added successfully!');
            document.getElementById('variantForm').reset();
            $('#addVariantModal').modal('hide');
            location.reload();
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