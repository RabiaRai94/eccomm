<div class="card" style="width: 18rem; height: 400px; display: flex; flex-wrap: wrap; margin-bottom: 1rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <div class="card-body">
        @if($variant->attachments->isNotEmpty())
        <div id="carousel-{{ $variant->id }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($variant->attachments as $index => $attachment)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $attachment->file_path) }}"
                        alt="{{ $attachment->file_name }}"
                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $variant->id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $variant->id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        @endif
        <h5 class="card-title mt-3">Size: {{ $variant->size }}</h5>
        <p class="card-text">Price: ${{ $variant->price }}</p>
        <p class="card-text">Stock: {{ $variant->stock }}</p>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" onclick="openEditVariantModal({{ json_encode($variant) }})">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-danger btn-sm delete-variant-btn" data-id="{{ $variant->id }}">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
        </div>

    </div>
</div>