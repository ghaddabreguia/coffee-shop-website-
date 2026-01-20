<?php
require_once "admin_auth.php";
include "db.php";
$current_page = basename($_SERVER['PHP_SELF']);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product_dashbord.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Product Hasna Labed</title>
</head>
<body>

<div class="menu">
    <ul>
        <li class="profile">
            <div class="img-box">
                <img src="admin" alt="profile">
            </div>
            <h2>Admin</h2>
        </li>
        <li><a href="admin-dashboard.php" class="<?= $current_page == 'admin-dashboard.php' ? 'active' : '' ?>"><i class="fas fa-home"></i><p>Dashboard</p></a></li>
        <li><a href="client_dashbord.php" class="<?= $current_page == 'client_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-user"></i><p>Clients</p></a></li>
        <li><a href="product_dashbord.php"class="<?= $current_page == 'product_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-table"></i><p>Products</p><?php if ($unread_count > 0): ?>
                    <span class="badge"><?php echo $unread_count; ?></span>
                <?php endif; ?></a></li>
        <li><a href="chart_dashbord.php" class="<?= $current_page == 'chart_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-chart-pie" ></i><p>Chart</p></a></li>
        <li><a href="order_dashbord.php"  class="<?= $current_page == 'order_dashbord.php' ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i><p>Order</p></a></li>
        <li><a href="contact_dashbord.php"><i class="fas fa-envelope"></i><p>Contact</p></a></li>
        <li class="log-out"><a href="logout.php"><i class="fas fa-sign-out"></i><p>Log-out</p></a></li>
    </ul>
</div>


<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Product</h2>
        <form id="addProductForm" enctype="multipart/form-data">
            <label>Name:</label><input type="text" name="name" required>
            <label>Price:</label><input type="number" name="price" required>
            <label>Description:</label><textarea name="description"></textarea>
            <label>Category:</label>
            <select name="category" required>
                <option value="cold">Cold Drinks</option>
                <option value="hot">Hot Drinks</option>
                <option value="sweets">Sweets & Breads</option>
            </select>
            <label>Calories:</label><input type="number" name="calories" value="100">
            <select name="hasMilk">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
            <label>Product Image:</label><input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Product</button>
        </form>
    </div>
</div>

<div class="contents">
    <div class="title-info"><p>Dashboard</p><i class="fas fa-chart-bar"></i></div>

    <div class="buttons">
        <button id="openModal" class="box"><i class="fas fa-plus"></i><div class="add-product"><p>Add Product</p></div></button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
const modal = document.getElementById("addProductModal");
const btn = document.getElementById("openModal");
const closeBtn = document.querySelector(".close");
const form = document.getElementById("addProductForm");

btn.onclick = () => { modal.style.display = "block"; };
closeBtn.onclick = () => { modal.style.display = "none"; };
window.onclick = (e) => { if(e.target === modal) modal.style.display = "none"; };

form.addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(form);
    fetch("add_product.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert("Product added successfully");
            modal.style.display = "none";
            form.reset();
            loadProducts();
        } else { alert(data.message); }
    });
});




function deleteProduct(id, image) {
    if(!confirm("Are you sure you want to delete this product?")) return;

    const formData = new FormData();
    formData.append("id", id);
    formData.append("image", decodeURIComponent(image));

    fetch("delete_products.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if(data.success){
            alert("Deleted successfully");
            loadProducts();
        } else { 
            alert("Delete failed: " + (data.message || ""));
        }
    });
}


function loadProducts() {
    fetch("get_products.php")
    .then(res => res.json())
    .then(products => {
        const tbody = document.querySelector("table tbody");
        tbody.innerHTML = "";
        products.forEach(p => {
            tbody.innerHTML += `
                <tr>
                    <td>${p.name}</td>
                    <td>${p.price}</td>
                    <td>${p.description}</td>
                    <td>${p.category}</td>
                    <td><img src="image/${p.image_url}" width="60"></td>
                    <td>
                        <button onclick="deleteProduct(${p.id}, '${encodeURIComponent(p.image_url)}')" 
                            style="background:red;color:white;border:none;padding:6px 10px;border-radius:5px;cursor:pointer;">
                            Delete
                        </button>
                    </td>
                </tr>
            `;
        });
    });
}


loadProducts();
</script>

</body>
</html>
