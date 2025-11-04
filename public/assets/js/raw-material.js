document.addEventListener("DOMContentLoaded", () => {

    // ===== HELPER FUNCTION TO ATTACH BUTTON LISTENERS =====
    function attachMaterialListeners(row) {
        const btnAddStock = row.querySelector('.btnAddStock');
        const btnReduceStock = row.querySelector('.btnReduceStock');
        const btnUpdateMaterial = row.querySelector('.btnUpdateMaterial');
        const btnDeleteMaterial = row.querySelector('.btnDeleteMaterial');

        if (btnAddStock) {
            btnAddStock.addEventListener('click', () => {
                const id = btnAddStock.dataset.id;
                const name = btnAddStock.dataset.name;
                const unit = btnAddStock.dataset.unit;

                Swal.fire({
                    title: `Add Stock: ${name}`,
                    input: 'number',
                    inputLabel: `Enter amount (${unit})`,
                    inputAttributes: { min: 1 },
                    showCancelButton: true,
                    confirmButtonText: "Add Stock",
                    preConfirm: qty => {
                        if (!qty || qty <= 0) {
                            Swal.showValidationMessage("Enter a valid quantity");
                            return false;
                        }
                        return qty;
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        const qty = result.value;
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/raw-material/${id}/add-stock`;
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                            <input type="hidden" name="quantity" value="${qty}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        }

        if (btnReduceStock) {
            btnReduceStock.addEventListener('click', () => {
                const id = btnReduceStock.dataset.id;
                const name = btnReduceStock.dataset.name;
                const unit = btnReduceStock.dataset.unit;

                Swal.fire({
                    title: `Reduce Stock: ${name}`,
                    input: 'number',
                    inputLabel: `Enter amount to deduct (${unit})`,
                    inputAttributes: { min: 1 },
                    showCancelButton: true,
                    confirmButtonText: "Reduce Stock",
                    preConfirm: qty => {
                        if (!qty || qty <= 0) {
                            Swal.showValidationMessage("Enter a valid quantity");
                            return false;
                        }
                        return qty;
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        const qty = result.value;
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/raw-material/${id}/reduce-stock`;
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                            <input type="hidden" name="quantity" value="${qty}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        }

if (btnUpdateMaterial) {
    btnUpdateMaterial.addEventListener('click', () => {
        const id = btnUpdateMaterial.dataset.id;
        const name = btnUpdateMaterial.dataset.name;
        const unit = btnUpdateMaterial.dataset.unit;

        Swal.fire({
            title: 'Update Raw Ingredient',
            html: `
                <input type="number" id="update_id" class="swal2-input" placeholder="ID" value="${id}">
                <input type="text" id="update_name" class="swal2-input" placeholder="Material Name" value="${name}">
                <select id="update_unit" class="swal2-input">
                    <option value="g" ${unit === 'g' ? 'selected' : ''}>Gram (g)</option>
                    <option value="ml" ${unit === 'ml' ? 'selected' : ''}>Milliliter (ml)</option>
                    <option value="pcs" ${unit === 'pcs' ? 'selected' : ''}>Pieces (pcs)</option>
                </select>
            `,
            confirmButtonText: 'Update',
            showCancelButton: true,
            preConfirm: () => {
                const newId = parseInt(document.getElementById('update_id').value);
                const newName = document.getElementById('update_name').value.trim();
                const newUnit = document.getElementById('update_unit').value;

                if (!newId || newId <= 0) Swal.showValidationMessage('Please enter a valid ID');
                if (!newName) Swal.showValidationMessage('Please enter a material name');

                return { newId, newName, newUnit };
            }
        }).then(result => {
            if (!result.isConfirmed) return;

            const { newId, newName, newUnit } = result.value;

            // Only send new_id if it has changed
            const payload = { name: newName, unit: newUnit };
            if (newId !== parseInt(id)) payload.new_id = newId;

            fetch(`/admin/raw-material/update/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(res => {
                if (!res.ok) throw new Error('Update failed');
                return res.json();
            })
            .then(data => {
                const row = btnUpdateMaterial.closest('tr');

                // Update table row
                row.cells[0].textContent = data.id;
                row.cells[1].textContent = data.name;
                row.cells[1].id = `displayName${data.id}`;
                row.cells[3].textContent = data.unit;
                row.cells[3].id = `displayUnit${data.id}`;

                // Update dataset for buttons
                row.querySelectorAll('button').forEach(button => {
                    button.dataset.id = data.id;
                    button.dataset.name = data.name;
                    button.dataset.unit = data.unit;
                });

                Swal.fire('Success', 'Ingredient updated!', 'success');
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        });
    });
}

         // --- Delete Material ---
        if (btnDeleteMaterial) {
            btnDeleteMaterial.addEventListener('click', () => {
                const id = btnDeleteMaterial.dataset.id;
                const name = btnDeleteMaterial.dataset.name;
                const qtyCell = btnDeleteMaterial.closest('tr').querySelector(`#displayQty${id}`);
                const quantity = parseFloat(qtyCell.textContent);

                if (quantity > 0) {
                    Swal.fire('Error', 'Cannot delete a material that still has stock!', 'error');
                    return;
                }

                Swal.fire({
                    title: `Delete "${name}"?`,
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/raw-material/${id}`;
                        form.innerHTML = `
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        }
    }

    // ===== ADD NEW MATERIAL =====
    const addBtn = document.getElementById('btnAddMaterial');
    if (addBtn) {
        addBtn.addEventListener('click', () => {
            Swal.fire({
                title: 'Add Raw Ingredient',
                html: `
                    <input type="number" id="rm_id" class="swal2-input" placeholder="ID">
                    <input type="text" id="rm_name" class="swal2-input" placeholder="Material Name">
                    <select id="rm_unit" class="swal2-input">
                        <option value="g">Gram (g)</option>
                        <option value="ml">Milliliter (ml)</option>
                        <option value="pcs">Pieces (pcs)</option>
                    </select>
                `,
                confirmButtonText: 'Save',
                showCancelButton: true,
                preConfirm: () => {
                    const id = parseInt(document.getElementById('rm_id').value);
                    const name = document.getElementById('rm_name').value.trim();
                    const unit = document.getElementById('rm_unit').value;

                    if (!id || id <= 0) Swal.showValidationMessage('Please enter a valid ID');
                    if (!name) Swal.showValidationMessage('Please enter material name');

                    return { id, name, unit };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const { id, name, unit } = result.value;

                    fetch(addBtn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ id, name, unit, quantity: 0 })
                    })
                    .then(res => res.json())
                    .then(data => {
                        const tbody = document.querySelector('table tbody');
                        const newRow = document.createElement('tr');
                        newRow.innerHTML = `
                            <td>${data.id}</td>
                            <td id="displayName${data.id}">${data.name}</td>
                            <td id="displayQty${data.id}">${data.quantity.toFixed(2)}</td>
                            <td id="displayUnit${data.id}">${data.unit}</td>
                            <td><span class="badge ${data.quantity < 5 ? 'bg-danger' : 'bg-success'}">${data.quantity < 5 ? 'Low' : 'OK'}</span></td>
                            <td>
                                <button class="btn btn-success btnAddStock" data-id="${data.id}" data-name="${data.name}" data-unit="${data.unit}">➕ Add</button>
                                <button class="btn btn-warning btnReduceStock" data-id="${data.id}" data-name="${data.name}" data-unit="${data.unit}">➖ Reduce</button>
                                <button class="btn btn-primary btnUpdateMaterial" data-id="${data.id}" data-name="${data.name}" data-unit="${data.unit}">🔄 Update</button>
                                <button class="btn btn-sm btn-danger btnDeleteMaterial" data-id="${data.id}" data-name="${data.name}">🗑 Delete</button>
                            </td>
                        `;
                        tbody.appendChild(newRow);
                        attachMaterialListeners(newRow);
                        Swal.fire('Success', 'Raw material added!', 'success');
                    })
                    .catch(err => Swal.fire('Error', err.message, 'error'));
                }


            });

        });
    }
    // ✅ DELETE BUTTON


    // Attach listeners to existing rows
    document.querySelectorAll('table tbody tr').forEach(row => attachMaterialListeners(row));
});
