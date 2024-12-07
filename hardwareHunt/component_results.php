<?php

// var_dump output variables ot the screen
// request contains all submitted form information
var_dump($_REQUEST);

// echo "<br><br>hello " . $_REQUEST["fullname"];

//step 1 connect to database

$host = "webdev.iyaserver.com";
$userid = "nepo";
$userpw = "BackendMagic1024";
$db = "nepo_hardwareHunt2";

// inlcude "../anvariables.php";

$mysql = new mysqli(
    $host,
    $userid,
    $userpw,
    $db
);

// if I can find an error number then stop because there was a problem
if($mysql->connect_errno) { //if error
    echo "db connection error : " . $mysql->connect_error; //tell me there was an erro
    exit(); //stop running page
} else {
    // echo "db connection success!"; //slaytastic. no errors, removing to get rid of it on page
    //if you mess up username password serve then this error will come up.
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

        .filter-drop-down{
            background-color: transparent;
            color: #d1d1d3;
            border: 2px solid #a1a1a3; 
            width: 20vw;
            border-radius: 20px;
            align-items: center; /* Aligns items vertically */
            padding: 8px;
            font-size: 1em;
            transition: max-height 0.3s ease; 
        }

        .filter-drop-down-header{
            display: flex;
            justify-content: space-between; /* Pushes text and chevron to opposite sides */
        }

        #filter-word-field{
            background-color: transparent;
            color: white;
            border: 2px solid #a1a1a3; 
            width: 20vw;
            height: 2.75vw;
            border-radius: 20px;
            padding: 1vw;
            font-size: 1.2em;
        }

        .filter-chevron{
            width: 2vw;
            height: 1.5vw;
        }

        .filter-chevron-rotated{
            transform: rotate(180deg);
        }

        .filter-drop-down-open{
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
<header>
    <a href="http://nepo.webdev.iyaserver.com/acad276/hardwareHunt/search.php" style="text-decoration: none;">
        <div class="header-left">
            <img src="HHlogo.png" alt="Hardware Hunt Logo" class="logo">
            <h1>Hardware Hunt</h1>
        </div>
    </a>

    <nav class="header-center">
        <a href="#about">About</a>
        <a href="http://nepo.webdev.iyaserver.com/acad276/hardwareHunt/component_results.php">Components</a>
        <a href="#projects">Projects</a>
    </nav>
    <div class="header-right">
        <form action="component_results.php" method="get" >
            <input type="search" id="search-bar" placeholder="Search..." name="search-parts" >
        </form>
        <img src="user-icon.png" alt="User Icon" class="user-icon">
    </div>
</header>

<div class="main-content-wrapper">
    <div class="main-content">
        <aside id="filters">
            <h2>Filters</h2>
            
            <div class="filter-section">
                <label>Keywords</label>
                <input type="text" id="filter-word-field" name="Keywords" placeholder="Type here" oninput="KeywordSearch()">
            </div>
            
            <div class="filter-section">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: .25vw;">
                        <label>Manufacturer</label>
                        <button type="button" onclick="ClearManufacturerCheckBoxes()" class="clear-button">Clear</button>
                    </div>
            
                    <div class="filter-drop-down" id="filter-manufacturer-drop-down">
                        <div class="filter-drop-down-header">  
                            <div class="filter-option">
                                <input type="checkbox" name="manufacturer" value="all" checked> All
                            </div>
                            <img src="chevron.png" alt="Icon that indicates you can expand bar" width="300" class="filter-chevron" id="filter-manufacturer-chevron" onClick="ToggleManufacturerDropdown()">
                        </div>
                    </div>
                    
            </div>
            
            <div class="filter-section">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: .25vw;">
                        <label>Category</label>
                        <button type="button" onclick="ClearCategoryCheckBoxes()" class="clear-button">Clear</button>
                    </div>

                    <div class="filter-drop-down" id="filter-category-drop-down">
                        
                        <div class="filter-drop-down-header">
                            <div class="filter-option">
                                <input type="checkbox" name="category" value="all"> All
                            </div>
                            <img src="chevron.png" alt="Icon that indicates you can expand bar" width="300" class="filter-chevron" id="filter-category-chevron" onClick="ToggleCategoryDropdown()">
                        </div>

                    </div>
            </div>

            <div class="filter-section">
                <label>Price</label>
                <input type="range" min="0" max="200" value="100" id="price-range">
                <span id="price-label">Up to $<span id="price-value">100</span></span>
            </div>
        </aside>

        <main id="product-list">
        </main>
    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        
         manufacturerCheckboxes = document.querySelectorAll("input[name='manufacturer']");
         categoryCheckboxes = document.querySelectorAll("input[name='category']");
    
        window.ToggleManufacturerDropdown = function(){
            const dropchevron = document.querySelector('#filter-manufacturer-chevron');
            const dropdown = document.querySelector('#filter-manufacturer-drop-down');
        
            if (!dropdown) {
                console.error("Element with class 'filter-drop-down' not found.");
                return;
            }
        
            dropdown.classList.toggle('filter-drop-down-open');
            dropchevron.classList.toggle('filter-chevron-rotated');
            const isDropdownOpen = dropdown.classList.contains('filter-drop-down-open');
        
            const manufacturersID = 'manufacturersID';
        
            if (isDropdownOpen) {
                if (!document.getElementById(manufacturersID)) {
                    const manufacturersContainer = document.createElement('div');
                    manufacturersContainer.id = manufacturersID;
                    manufacturersContainer.className = 'filter-option';
        
                    const manufacturersData = <?php
                        $sql = "SELECT * FROM manufacturers";
                        $results = $mysql->query($sql);
        
                        if (!$results) {
                            echo "console.error('SQL Error: " . addslashes($mysql->error) . "');";
                            exit();
                        }
        
                        $manufacturers = [];
                        while ($currentrow = $results->fetch_assoc()) {
                            $manufacturers[] = [
                                'name' => $currentrow['manufacturer_name']
                            ];
                        }
                        echo json_encode($manufacturers);
                    ?>;
        
                    manufacturersData.forEach(manufacturer => {
                        const manufacturerOption = document.createElement('div');
                        manufacturerOption.className = 'filter-option';
                        manufacturerOption.innerHTML = `
                            <input type="checkbox" name="manufacturer" value="${manufacturer.name}"> ${manufacturer.name}
                        `;
                        manufacturersContainer.appendChild(manufacturerOption);
                    });
        
                    dropdown.appendChild(manufacturersContainer);
                    
                    manufacturerCheckboxes = document.querySelectorAll("input[name='manufacturer']");
                        manufacturerCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener("change", filterProducts);
                    });
                }
            } else {
                const existingOption = document.getElementById(manufacturersID);
                if (existingOption) {
                    existingOption.remove();
                }
            }
        }
        
        window.ToggleCategoryDropdown = function(){
            const dropchevron = document.querySelector('#filter-category-chevron');
            const dropdown = document.querySelector('#filter-category-drop-down');
        
            if (!dropdown) {
                console.error("Element with class 'filter-drop-down' not found.");
                return;
            }
        
            dropdown.classList.toggle('filter-drop-down-open');
            dropchevron.classList.toggle('filter-chevron-rotated');
            const isDropdownOpen = dropdown.classList.contains('filter-drop-down-open');
        
            const categoriesID = 'categoryID';
        
            if (isDropdownOpen) {
                if (!document.getElementById(categoriesID)) {
                    const categoriesContainer = document.createElement('div');
                    categoriesContainer.id = categoriesID;
                    categoriesContainer.className = 'filter-option';
        
                    const categoriesData = <?php
                        $sql = "SELECT * FROM componentType";
                        $results = $mysql->query($sql);
        
                        if (!$results) {
                            echo "console.error('SQL Error: " . addslashes($mysql->error) . "');";
                            exit();
                        }
        
                        $categories = [];
                        while ($currentrow = $results->fetch_assoc()) {
                            $categories[] = [
                                'name' => $currentrow['component_type']
                            ];
                        }
                        echo json_encode($categories);
                    ?>;
        
                    categoriesData.forEach(category => {
                        const categoryOption = document.createElement('div');
                        categoryOption.className = 'filter-option';
                        categoryOption.innerHTML = `
                            <input type="checkbox" name="category" value="${category.name}"> ${category.name}
                        `;
                        categoriesContainer.appendChild(categoryOption);
                    });
        
                    dropdown.appendChild(categoriesContainer);
                    
                    categoryCheckboxes = document.querySelectorAll("input[name='category']");
                        categoryCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener("change", filterProducts);
                    });
                }
            } else {
                const existingOption = document.getElementById(categoriesID);
                if (existingOption) {
                    existingOption.remove();
                }
            }
        }
        
        window.ClearCategoryCheckBoxes = function(){
    
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        
            console.log("All category checkboxes cleared.");
            
        }
        
        window.ClearManufacturerCheckBoxes = function(){
    
            manufacturerCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        
            console.log("All manufacturer checkboxes cleared.");
            
        }

        <?php
        $sql = "SELECT * FROM component_details WHERE 1=1";

        if (isset($_REQUEST['search-parts'])) {
            $searchTerm = $mysql->real_escape_string($_REQUEST['search-parts']);

            $sql .= " AND (component_name LIKE '%$searchTerm%' OR component_description LIKE '%$searchTerm%')";
        }

        // echo $sql;
        $results = $mysql->query($sql);
        if (!$results) {
            echo "console.error('SQL Error: " . $mysql->error . "');";
            exit();
        }
        
        //make products array
        $products = [];
        while ($currentrow = $results->fetch_assoc()) {
            // echo json_encode($currentrow, JSON_THROW_ON_ERROR);
            $one = [
                'id' => utf8_encode($currentrow['component_id']),
                'name' => utf8_encode($currentrow['component_name']),
                'price' => (float)$currentrow['price'],
                'imgSrc' => utf8_encode($currentrow['component_image']),
                'description' => utf8_encode(addslashes($currentrow['component_description'])),
                'stock' => (int)$currentrow['stock_quantity'],
                'manufacturer' => utf8_encode($currentrow['manufacturer_name']),
                'category' => utf8_encode($currentrow['component_type'])
            ];
            $products[] = $one;
        }
        $json_data = json_encode($products, JSON_THROW_ON_ERROR);
        // var_dump($json_data);
        // echo json_encode($products);
        echo "const products = " . $json_data . ";";
        ?>

        const productContainer = document.getElementById("product-list");
        
        //if in stock or not.
        function inStock(stock) {
            if (stock > 20)
            {
                return '<span style="color: white;">In Stock</span>';
            }
            else if (stock < 20 && stock > 0)
            {
                return '<span style="color: yellow;">Few Left</span>';
            }
            else{
                return '<span style="color: red;">Out of Stock</span>';
            }
        }

        function displayProducts(filteredProducts) {
            productContainer.innerHTML = "";
            filteredProducts.forEach(product => {
                const productElement = document.createElement("div");
                productElement.className = "product";
//the click to the new spot
                productElement.addEventListener("click", () => {
                    const targetUrl = "component_details.php?id=" + encodeURIComponent(product.id);
                    console.log(`Navigating to: ${targetUrl}`);
                    window.location.href = targetUrl;
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
        }

        function filterProducts() {
            
            const maxPrice = parseFloat(priceRangeInput.value);

            const selectedManufacturers = Array.from(manufacturerCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value.toLowerCase());

            const selectedCategories = Array.from(categoryCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value.toLowerCase());

            const filteredProducts = products.filter(product => {
            
                return (
                    (selectedManufacturers.length === 0 || selectedManufacturers.includes(product.manufacturer.toLowerCase())) &&
                    (selectedCategories.length === 0 || selectedCategories.includes(product.category.toLowerCase())) &&
                    product.price <= maxPrice
                    
                );
            });

            console.log( filteredProducts);
            
            displayProducts(filteredProducts);
        }

        displayProducts(products);
        console.log(products);

        const priceRangeInput = document.getElementById("price-range");
        const priceValueDisplay = document.getElementById("price-value");

        priceRangeInput.addEventListener("input", function(event) {
            const maxPrice = event.target.value; 
            priceValueDisplay.innerText = maxPrice;

            filterProducts();  // Reapply filters with the updated price range
        });
        
        //This function is called when a keyword is inputed in the field
        window.KeywordSearch = function () {
            
            const keyword = document.getElementById("filter-word-field").value.toLowerCase();
            
            if (keyword.trim() === "") {
                displayProducts(products); // Show all products if no keyword
                return;
            }
            
            const filteredProducts = products.filter(product => {
                return (
                    product.name.toLowerCase().includes(keyword) ||
                    product.description.toLowerCase().includes(keyword) ||
                    product.category.toLowerCase().includes(keyword) ||
                    product.manufacturer.toLowerCase().includes(keyword)
                );
            });
            
            displayProducts(filteredProducts);
            
        }
        
    });
</script>
</body>
</html>