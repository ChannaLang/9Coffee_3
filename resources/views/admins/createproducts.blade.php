{{-- @extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">🛒 Create New Product</h4>
        </div>

        <div class="card-body">
            <form id="createProductForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter product name" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" id="price" class="form-control" placeholder="Enter price" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">Select type</option>
                        <option value="food">Food</option>
                        <option value="drink">Drink</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Enter quantity">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Enter description (optional)"></textarea>
                </div>

                <button type="submit" class="btn btn-warning text-dark">Create Product</button>
                <a href="{{ route('all.products') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/store-product.js') }}"></script>
@endsection --}}
