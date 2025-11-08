document.addEventListener("DOMContentLoaded", function () {
    const btnCreate = document.getElementById("btnCreateProduct");
    if (!btnCreate) return;

    // Get the URL dynamically from meta tag
    const storeProductUrl = document.querySelector('meta[name="store-products-url"]').content;

    btnCreate.addEventListener("click", function () {
        Swal.fire({
            title: "🛒 Create New Product",
            html: `
                <form id="createProductForm" enctype="multipart/form-data">
                    <div class="mb-3 text-start">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Select type</option>
                            <option value="food">Food</option>
                            <option value="drink">Drink</option>
                        </select>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: "Create",
            cancelButtonText: "Cancel",
            width: "600px",
            focusConfirm: false,
            preConfirm: () => {
                const form = document.getElementById("createProductForm");
                const formData = new FormData(form);

                return fetch(storeProductUrl, {
                    method: "POST",
                    body: formData, // Do NOT set Content-Type manually for FormData
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.message || "Server error");
                            });
                        }
                        return response.json();
                    })
                    .then((data) => {
                        Swal.fire({
                            icon: "success",
                            title: "✅ Product Created!",
                            text: "Your product has been added successfully.",
                        }).then(() => {
                            location.reload(); // refresh to show new product
                        });
                    })
                    .catch((error) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops!",
                            text: error.message || "Something went wrong while creating product.",
                        });
                    });
            },
        });
    });
});
