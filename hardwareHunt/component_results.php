<?php
// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection setup
$host = "webdev.iyaserver.com";
$userid = "nepo";
$userpw = "BackendMagic1024";
$db = "nepo_hardwareHunt2";

$mysql = new mysqli(
    $host,
    $userid,
    $userpw,
    $db
);

if (!$mysql->set_charset("utf8")) {
    echo "Error loading character set utf8: " . $mysql->error;
    exit();
}

if($mysql->connect_errno) {
    echo "db connection error : " . $mysql->connect_error;
    exit();
}

// Fetch all products without pagination limits
$sql = "SELECT * FROM component_details";
if (isset($_REQUEST['search-parts'])) {
    $searchTerm = $mysql->real_escape_string($_REQUEST['search-parts']);
    $sql .= " WHERE component_name LIKE '%$searchTerm%' OR component_description LIKE '%$searchTerm%'";
}

$results = $mysql->query($sql);
if (!$results) {
    echo "SQL error: ". $mysql->error;
    exit();
}

// Initialize products array
$products = [];
while ($currentrow = $results->fetch_assoc()) {
    $products[] = [
        'id' => mb_convert_encoding($currentrow['component_id'], "UTF-8", mb_detect_encoding($currentrow['component_id'])),
        'name' => mb_convert_encoding($currentrow['component_name'], "UTF-8", mb_detect_encoding($currentrow['component_name'])),
        'price' => (float)$currentrow['price'],
        'imgSrc' => mb_convert_encoding($currentrow['component_image'], "UTF-8", mb_detect_encoding($currentrow['component_image'])),
        'description' => mb_convert_encoding($currentrow['component_description'], "UTF-8", mb_detect_encoding($currentrow['component_description'])),
        'stock' => (int)$currentrow['stock_quantity'],
        'manufacturer' => mb_convert_encoding($currentrow['manufacturer_name'], "UTF-8", mb_detect_encoding($currentrow['manufacturer_name'])),
        'category' => mb_convert_encoding($currentrow['component_type'], "UTF-8", mb_detect_encoding($currentrow['component_type']))
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Hunt</title>
    <link rel="stylesheet" href="menu.css">
    <style>
        .main-content-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            padding: 1.5em 0;
        }

        .main-content {
            display: flex;
            gap: 1.5em;
            width: 85%;
            max-width: 1400px;
        }

        #filters {
            width: 30%;
            padding: 1.5em;
            background-color: #2c2c2e;
            color: #ffffff;
            border-radius: 10px;
            max-height: 80vh;
            overflow-y: auto;
        }

        #filters h2 {
            margin-bottom: 1em;
            font-size: 1.2em;
            color: #a1a1a3;
        }

        .filter-section {
            margin-bottom: 1.5em;
            padding: 0;
        }

        .filter-section label {
            font-weight: bold;
            color: #a1a1a3;
            display: block;
            margin-bottom: 0.5em;
        }

        .filter-option {
            color: #d1d1d3;
        }

        .filter-dropdown {
            width: 100%;
            padding: 0.8em;
            border: 2px solid #a1a1a3;
            border-radius: 10px;
            background-color: #2c2c2e;
            color: #ffffff;
            font-size: 1em;
            margin-top: 0.5em;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .filter-dropdown:hover, .filter-dropdown:focus {
            border-color: #ffffff;
            outline: none;
        }

        .filter-dropdown option {
            background-color: #2c2c2e;
            color: #ffffff;
            padding: 0.5em;
        }

        .filter-drop-down-header {
            display: flex;
            justify-content: space-between;
        }

        #filter-word-field {
            background-color: transparent;
            color: white;
            border: 2px solid #a1a1a3;
            width: 100%;
            height: 2.75vw;
            border-radius: 20px;
            padding: 1em;
            font-size: 1em;
        }

        .filter-chevron {
            width: 2vw;
            height: 1.5vw;
        }

        .filter-chevron-rotated {
            transform: rotate(180deg);
        }

        .filter-drop-down-open {
            height: auto;
        }

        .clear-button {
            background-color: transparent;
            color: #a1a1a3;
            border: 2px solid #a1a1a3;
            border-radius: 20px;
            width: 3.5vw;
        }

        #price-label {
            font-size: 1em;
            color: #a1a1a3;
            display: inline-block;
            margin-top: 0.5em;
        }

        #price-value {
            font-weight: bold;
            color: #ffffff;
        }

        #product-list {
            width: 70%;
            padding: 1.5em;
            background-color: #1c1c1e;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
        }

        .products-container {
            flex: 1;
        }

        .product {
            display: flex;
            align-items: center;
            background-color: #2c2c2e;
            border-radius: 10px;
            padding: 1em;
            margin-bottom: 1em;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            transition: transform 0.2s ease-in-out;
        }

        .product:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        .product img {
            width: 80px;
            height: auto;
            border-radius: 5px;
            margin-right: 1em;
        }

        .product-details {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-right: 30px
        }

        .product-info {
            display: flex;
            flex-direction: column;
            margin-right: 60px
        }

        .product h3 {
            font-size: 1.1em;
            color: #ffffff;
            margin: 0;
        }

        .product p {
            font-size: 0.9em;
            color: #a1a1a3;
            margin: 0.3em 0;
        }

        .product-price-stock {
            text-align: right;
            font-size: 1em;
            color: #ffffff;
        }

        .product-price {
            font-weight: bold;
            color: #ffffff;
        }

        .stock-status {
            color: #ff3b30;
            font-size: 0.9em;
        }

        .pagination {
            margin-top: 2em;
            display: flex;
            justify-content: center;
            gap: 0.5em;
            width: 100%;
        }

        .page-link {
            padding: 0.5em 1em;
            background-color: #2c2c2e;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .page-link:hover {
            background-color: #3c3c3e;
        }

        .page-link.active {
            background-color: #0a84ff;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                width: 90%;
            }
            #filters {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #ccc;
                margin-bottom: 1.5em;
            }
            #product-list {
                width: 100%;
            }
            .product {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .product img {
                margin-bottom: 1em;
            }
            .product-details {
                flex-direction: column;
                align-items: center;
            }
            .product-price-stock {
                text-align: center;
                margin-top: 0.5em;
            }
        }
    </style>
</head>
<body>
<?php
    $showPartsSearchBar = true;
    $showProjectsSearchBar = false;
    include 'navbar.php';
?>

<div class="main-content-wrapper">
    <div class="main-content">
        <aside id="filters">
            <h2>Filters</h2>

            <div class="filter-section">
                <label>Keywords</label>
                <input type="text" id="filter-word-field" name="Keywords" placeholder="Type here" oninput="KeywordSearch()">
            </div>

            <div class="filter-section">
                <label>Manufacturer</label>
                <select id="manufacturer-select" class="filter-dropdown" onchange="filterProducts()">
                    <option value="">All Manufacturers</option>
                    <?php
                    $sql = "SELECT * FROM manufacturers";
                    $results = $mysql->query($sql);

                    if(!$results) {
                        echo "SQL error: ". $mysql->error;
                        exit();
                    }

                    while ($currentrow = $results->fetch_assoc()) {
                        echo '<option value="' . $currentrow['manufacturer_name'] . '">' . 
                             $currentrow['manufacturer_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filter-section">
                <label>Category</label>
                <select id="category-select" class="filter-dropdown" onchange="filterProducts()">
                    <option value="">All Categories</option>
                    <?php
                    $sql = "SELECT * FROM componentType";
                    $results = $mysql->query($sql);

                    if(!$results) {
                        echo "SQL error: ". $mysql->error;
                        exit();
                    }

                    while ($currentrow = $results->fetch_assoc()) {
                        echo '<option value="' . $currentrow['component_type'] . '">' . 
                             $currentrow['component_type'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filter-section">
                <label>Price</label>
                <input type="range" min="0" max="200" value="100" id="price-range">
                <span id="price-label">Up to $<span id="price-value">100</span></span>
            </div>
        </aside>

        <main id="product-list">
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo isset($_REQUEST['search-parts']) ? '&search-parts=' . urlencode($_REQUEST['search-parts']) : ''; ?>"
                       class="page-link <?php echo $i === $currentPage ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Get UI elements
    const productContainer = document.getElementById("product-list");
    const priceRangeInput = document.getElementById("price-range");
    const priceValueDisplay = document.getElementById("price-value");
    const manufacturerSelect = document.getElementById('manufacturer-select');
    const categorySelect = document.getElementById('category-select');

    // Pagination settings
    const productsPerPage = 10;
    let currentPage = 1;

    // Initialize with PHP data
    const allProducts = <?php echo json_encode($products, JSON_THROW_ON_ERROR); ?>;
    let filteredProducts = [...allProducts];

    function inStock(stock) {
        if (stock > 20) {
            return '<span style="color: white;">In Stock</span>';
        } else if (stock < 20 && stock > 0) {
            return '<span style="color: yellow;">Few Left</span>';
        } else {
            return '<span style="color: red;">Out of Stock</span>';
        }
    }

    function createPagination(totalPages) {
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination';

        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.className = `page-link ${i === currentPage ? 'active' : ''}`;
            pageLink.textContent = i;
            pageLink.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                displayProducts();
            });
            paginationContainer.appendChild(pageLink);
        }

        return paginationContainer;
    }

    function displayProducts() {
        productContainer.innerHTML = "";

        // Calculate pagination
        const startIndex = (currentPage - 1) * productsPerPage;
        const endIndex = startIndex + productsPerPage;
        const paginatedProducts = filteredProducts.slice(startIndex, endIndex);
        const totalPages = Math.ceil(filteredProducts.length / productsPerPage);

        // Display products
        paginatedProducts.forEach(product => {
            const productElement = document.createElement("div");
            productElement.className = "product";
            productElement.addEventListener("click", () => {
                window.location.href = "component_details.php?id=" + encodeURIComponent(product.id);
            });

            productElement.innerHTML = `
                <img src="${product.imgSrc}" alt="${product.name}">
                <div class="product-details">
                    <div class="product-info">
                        <h3>${product.name}</h3>
                        <p>${product.description}</p>
                        <p>Category: ${product.category}</p>
                        <p>Manufacturer: ${product.manufacturer}</p>
                    </div>
                    <div class="product-price-stock">
                        <p class="product-price">$${product.price.toFixed(2)}</p>
                        <p class="stock-status">${inStock(product.stock)}</p>
                    </div>
                </div>
            `;
            productContainer.appendChild(productElement);
        });

        // Add pagination
        const paginationElement = createPagination(totalPages);
        productContainer.appendChild(paginationElement);
    }

    function filterProducts() {
        const maxPrice = parseFloat(priceRangeInput.value);
        const selectedManufacturer = manufacturerSelect.value;
        const selectedCategory = categorySelect.value;
        const keyword = document.getElementById("filter-word-field").value.toLowerCase();

        filteredProducts = allProducts.filter(product => {
            const manufacturerMatch = !selectedManufacturer || 
                                   product.manufacturer === selectedManufacturer;
            const categoryMatch = !selectedCategory || 
                                product.category === selectedCategory;
            const priceMatch = product.price <= maxPrice;
            const keywordMatch = !keyword || 
                               product.name.toLowerCase().includes(keyword) ||
                               product.description.toLowerCase().includes(keyword) ||
                               product.category.toLowerCase().includes(keyword) ||
                               product.manufacturer.toLowerCase().includes(keyword);

            return manufacturerMatch && categoryMatch && priceMatch && keywordMatch;
        });

        // Reset to first page when filtering
        currentPage = 1;
        displayProducts();
    }

    // Initialize keyword search function
    window.KeywordSearch = function() {
        filterProducts();
    }

    // Event listeners
    priceRangeInput.addEventListener("input", function(event) {
        priceValueDisplay.innerText = event.target.value;
        filterProducts();
    });

    manufacturerSelect.addEventListener('change', filterProducts);
    categorySelect.addEventListener('change', filterProducts);

    // Initial display
    displayProducts();
});
</script>
</body>
</html>
