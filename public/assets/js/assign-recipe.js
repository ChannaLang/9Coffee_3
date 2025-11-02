document.addEventListener('DOMContentLoaded', function() {
    console.log('Assign-recipe JS loaded');

    // Dynamically add modal if it doesn't exist
    let modalEl = document.getElementById('assignRecipeModal');
    if (!modalEl) {
        document.body.insertAdjacentHTML('beforeend', `
<div class="modal fade" id="assignRecipeModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="background-color:#2c3e50; color:#ecf0f1; border-radius:1rem;">
      <div class="modal-header" style="border-bottom:2px solid #f39c12;">
        <h5 class="modal-title" style="color:#f39c12; font-weight:bold;">Assign Recipe</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="assignRecipeForm">
        <input type="text" id="materialSearch" placeholder="Search raw materials..." class="form-control mb-2">
          <input type="hidden" id="recipeProductId" name="product_id">
          <table class="table table-hover" style="color:#ecf0f1;">
            <thead style="background-color:#34495e; color:#f1c40f;">
              <tr>
                <th>Raw Material</th>
                <th>In-Stock</th>
                <th>Quantity Required</th>
                <th>Unit</th>
              </tr>
            </thead>
            <tbody id="recipeMaterialsBody"></tbody>
          </table>
          <button type="submit" class="btn btn-warning text-dark fw-bold">Save Recipe</button>
        </form>
      </div>
    </div>
  </div>
</div>

        `);
        modalEl = document.getElementById('assignRecipeModal');
    }

    const modal = new bootstrap.Modal(modalEl);
    const recipeBody = document.getElementById('recipeMaterialsBody');
    const recipeForm = document.getElementById('assignRecipeForm');
    const recipeProductId = document.getElementById('recipeProductId');

    const assignButtons = document.querySelectorAll('.btnAssignRecipe');

assignButtons.forEach(btn => {
    btn.addEventListener('click', async function() {
        recipeProductId.value = this.dataset.productId;

        let rawMaterials = [];

        try {
            const res = await fetch('/admin/raw-materials/list');
            rawMaterials = await res.json();

        } catch (err) {
            console.error(err);
            alert('Could not load raw materials');
            return;
        }

recipeBody.innerHTML = rawMaterials.map(mat => `
    <tr>
        <td>${mat.name}</td>
        <td>${mat.quantity}</td> <!-- Show available stock here -->
        <td>
            <input type="number"
                   name="materials[${mat.id}]"
                   min="0" step="0.01"
                   value="0"
                   class="form-control">
        </td>
        <td>${mat.unit}</td>
    </tr>
`).join('');

    const searchInput = document.getElementById('materialSearch');
        searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
        document.querySelectorAll('#recipeMaterialsBody tr').forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            row.style.display = name.includes(query) ? '' : 'none';
    });
});



        modal.show();
    });
});


    recipeForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(recipeForm);
        const productId = recipeProductId.value;


        try {
            const res = await fetch(`/admin/product/${productId}/add-materials`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });
            if (!res.ok) throw new Error('Failed to save recipe');

            alert('Recipe saved successfully!');
            modal.hide();
            location.reload();
        } catch(err) {
            alert(err.message);
        }
    });
});
