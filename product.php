<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Set BASE_DIR to the absolute path of the root directory
define('BASE_DIR', __DIR__ . '/');
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>คำสั่งซื้อ - ART TOYS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" />
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include BASE_DIR . 'layout/nav.php'; ?> <!-- Using BASE_DIR to include nav.php -->
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '9' : '12'; ?> ms-sm-auto col-lg-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '10' : '12'; ?> px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h3>รายการสินค้า</h3>
                </div>
                <!-- Content Area for Orders -->
                <div class="table-responsive">
                    <div class="container mt-5">
                        <div class="row" id="productGrid">
                            <!-- สินค้าจะแสดงที่นี่ -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


    <script>
        // ฟังก์ชันสำหรับดึงข้อมูลจาก API
        async function fetchProducts() {
            try {
                // เรียกใช้ API
                const response = await fetch('http://localhost/ART_TOYS_RANDOMIZE/Controller/Admin/product/index.php'); // เปลี่ยน URL API ตามจริง
                const data = await response.json();

                // ตรวจสอบผลลัพธ์และแสดงผล
                if (data.result === 1) {
                    displayProducts(data.dataList);
                } else {
                    document.getElementById('productGrid').innerHTML = '<p>ไม่พบข้อมูลสินค้า</p>';
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                document.getElementById('productGrid').innerHTML = '<p>เกิดข้อผิดพลาดในการดึงข้อมูล</p>';
            }
        }

        // ฟังก์ชันสำหรับแสดงสินค้าบนหน้าเว็บ
        function displayProducts(products) {
            const productGrid = document.getElementById('productGrid');
            productGrid.innerHTML = ''; // ล้างข้อมูลเก่าทั้งหมด

            products.forEach(product => {
                if (product.prod_amount > 0) { // แสดงเฉพาะสินค้าที่จำนวนมากกว่า 0
                    const productHTML = `
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="http://localhost/ART_TOYS_RANDOMIZE/Controller/admin/product/uploads/${product.prod_img}" class="card-img-top" alt="${product.prod_name}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${product.prod_name}</h5>
                                    <p class="card-text">ขนาด: ${product.prod_size}</p>
                                    <p class="card-text">ราคา: ${new Intl.NumberFormat().format(product.prod_price)} บาท</p>
                                    <p class="card-text">จำนวนที่เหลือ: ${product.prod_amount}</p>

                                    <!-- แถวสำหรับจำนวนและปุ่มเพิ่มลงตะกร้า -->
                                    <div class="d-flex align-items-center">
                                        <!-- input สำหรับจำนวนสินค้า -->
                                        <label for="quantity-${product.prod_id}" class="me-2 mb-0">จำนวน: </label>
                                        <input type="number" id="quantity-${product.prod_id}" class="form-control me-3" value="1" min="1" max="${product.prod_amount}" style="width: 80px;">
                                        
                                        <!-- ปุ่มเพิ่มลงตะกร้า -->
                                        <button class="btn btn-primary add-to-cart" data-id="${product.prod_id}" data-name="${product.prod_name}" data-price="${product.prod_price}">เพิ่มลงตะกร้า</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    productGrid.innerHTML += productHTML;
                }
            });

            // เพิ่ม Event Listener ให้กับปุ่ม "เพิ่มลงตะกร้า"
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    const product = {
                        id: productId,
                        name: this.getAttribute('data-name'),
                        price: this.getAttribute('data-price'),
                        amount: document.getElementById(`quantity-${productId}`).value // ใช้ค่าจาก input
                    };
                    addToCart(product);
                });
            });
        }

        // ฟังก์ชันเพิ่มสินค้าลงตะกร้า (เก็บใน localStorage)
        function addToCart(product) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // ตรวจสอบว่าสินค้ามีอยู่แล้วหรือไม่
            let existingProduct = cart.find(item => item.id === product.id);
            if (existingProduct) {
                // ถ้ามีอยู่แล้ว เพิ่มจำนวนสินค้า
                existingProduct.amount = parseInt(existingProduct.amount) + parseInt(product.amount);
            } else {
                // ถ้ายังไม่มี ให้เพิ่มสินค้าใหม่
                cart.push(product);
            }

            // อัปเดตตะกร้าใน localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // แจ้งเตือนผู้ใช้
            Swal.fire({
                title: 'เพิ่มลงตะกร้าสำเร็จ',
                text: product.name + ' ถูกเพิ่มลงในตะกร้า!',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        }

        // เรียกใช้ฟังก์ชันเมื่อหน้าเว็บโหลดเสร็จ
        document.addEventListener('DOMContentLoaded', fetchProducts);
    </script>
</body>

</html>