@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col">
        {{-- CSRF Token for AJAX --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="card shadow-sm rounded-4" style="background-color: #3e2f2f; color: #f5f5f5; border:1px solid #6b4c3b;">
            <div class="card-body">

                {{-- Flash Messages --}}
                @if (Session::has('success'))
                    <p class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </p>
                @endif

                @if (Session::has('delete'))
                    <p class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('delete') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </p>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">All Products</h5>
                    <a href="{{ route('create.products') }}" class="btn btn-warning text-dark">Create Product</a>
                </div>

                {{-- Products Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center" style="color:#f5f5f5;">
                        <thead style="background-color:#6b4c3b;">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Type</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Ingrediant</th>
                            </tr>
                        </thead>
                       <tbody>
                            @php $counter = 1; @endphp
                            @foreach ($products as $product)
                            <tr style="border-bottom:1px solid #5a3d30;">
                                {{-- Sequential number instead of DB id --}}
                                <th scope="row">{{ $counter }}</th>

                                <td>{{ $product->name }}</td>
                                <td>
                                    <div style="display: flex; justify-content: center; align-items: center;">
                                        <img
                                            src="{{ asset('assets/images/'.$product->image) }}"
                                            alt="{{ $product->name }}"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #6b4c3b;"
                                        >
                                    </div>
                                </td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->type }}</td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-info btn-sm rounded-pill btn-edit"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        data-type="{{ $product->type }}">
                                        Edit
                                    </button>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm rounded-pill btn-delete"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ number_format($product->price, 2) }}">
                                        Delete
                                    </button>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-primary btn-sm btnAssignRecipe"
                                        data-product-id="{{ $product->id }}">
                                        Assign Recipe
                                    </button>
                                    <button class="btn btn-sm btn-info btnViewRecipe"
                                            data-url="{{ route('admin.product.getAssignedMaterials', $product->id) }}">
                                        View Assigned
                                    </button>


                                </td>
                          </tr>
                            @php $counter++; @endphp
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/assign-recipe.js') }}"></script>
<script src="/js/assigned-recipe.js"></script>

{{-- SweetAlert --}}
<script src="{{ asset('assets/js/all-product.js') }}"></script>

{{-- <script src="{{ asset('assets/js/assign-recipe.js') }}"></script> --}}


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- CSRF Setup --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
});
</script>


@endsection
