@extends('layouts.admin')

@section('content')
<div class="mb-4">
<button id="btnAddMaterial" class="btn btn-primary" data-url="{{ route('admin.raw-material.store') }}">
    ➕ Add New Raw ingredient
</button>



<div class="container-fluid mt-5">
    <div class="card shadow-sm border-0 rounded-4 w-100" style="background-color: #3e2f2f; color: #f5f5f5;">
        <div class="card-header" style="background-color: #db770c; color: #fff;">
            <h4 class="mb-0">🧾 Raw ingredient stock</h4>

        </div>
        <div class="card-body">

            {{-- Stock Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="color:#f5f5f5; min-width:100%; border:1px solid #6b4c3b;">
                    <thead style="background-color: #5a3d30;" class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Raw ingredient</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($rawMaterials as $material)
<tr>

@php
    $displayQty = $material->quantity;
    $displayUnit = $material->unit;

    // if ($displayUnit == 'g' && $displayQty >= 1000) {
    //     $displayQty = $displayQty / 1000;
    //     $displayUnit = 'kg';
    // }

    // if ($displayUnit == 'ml' && $displayQty >= 1000) {
    //     $displayQty = $displayQty / 1000;
    //     $displayUnit = 'L';
    // }
@endphp
<td id="displayId{{ $material->id }}">{{ $material->id }}</td>
<td id="displayName{{ $material->id }}">{{ $material->name }}</td>
<td id="displayQty{{ $material->id }}">{{ number_format($displayQty, 2) }}</td>
<td id="displayUnit{{ $material->id }}">{{ $material->unit }}</td>



    <td>
        <span class="badge {{ $material->quantity < 5 ? 'bg-danger' : 'bg-success' }}">
            {{ $material->quantity < 5 ? 'Low' : 'OK' }}
        </span>
    </td>
            <td>
                <button
                    class="btn btn-success btnAddStock"
                    data-id="{{ $material->id }}"
                    data-name="{{ $material->name }}"
                    data-unit="{{ $material->unit }}"
                >➕ Add</button>

                <button
                    class="btn btn-warning btnReduceStock"
                    data-id="{{ $material->id }}"
                    data-name="{{ $material->name }}"
                    data-unit="{{ $material->unit }}"
                >➖ Reduce</button>

                <button
                    class="btn btn-primary btnUpdateMaterial"
                    data-id="{{ $material->id }}"
                    data-name="{{ $material->name }}"
                    data-unit="{{ $material->unit }}"
                >🔄 Update</button>


                <button
                    type="button"
                    class="btn btn-sm btn-danger btnDeleteMaterial"
                    data-id="{{ $material->id }}"
                    data-name="{{ $material->name }}"
                >🗑 Delete</button>

            </td>

</tr>
@endforeach   </tbody>
                </table>
            </div>

            <a href="{{ route('admins.dashboard') }}" class="btn btn-light mt-3" style="color:#3e2f2f;">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(Session::has('success'))
<script>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '{{ Session::get('success') }}',
  confirmButtonColor: '#db770c'
});
</script>
@endif
<script src="{{ asset('assets/js/raw-material.js') }}"></script>

{{-- ✅ SweetAlert Library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ Custom JS File --}}
<script src="{{ asset('assets/js/raw-material.js') }}"></script>

{{-- ✅ Success Toast --}}
@if(Session::has('success'))
<script>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '{{ Session::get('success') }}',
  confirmButtonColor: '#db770c'
});
</script>
@endif


@endsection
