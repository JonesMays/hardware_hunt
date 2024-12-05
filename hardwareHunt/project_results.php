<?php

// var_dump output variables ot the screen
// request contains all submitted form information//
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
    echo "db connection success!"; //slaytastic. no errors, removing to get rid of it on page
    //if you mess up username password serve then this error will come up.
}

// Get total number of products for pagination
$countSql = "SELECT COUNT(*) as total FROM projects WHERE 1=1";
if (isset($_REQUEST['search-parts'])) {
    $searchTerm = $mysql->real_escape_string($_REQUEST['search-parts']);
    $countSql .= " AND (project_name LIKE '%$searchTerm%' OR project_description LIKE '%$searchTerm%')";
}
$countResult = $mysql->query($countSql);
$totalProducts = $countResult->fetch_assoc()['total'];

// Set pagination variables
$productsPerPage = 10; // Adjust this number as needed
$totalPages = ceil($totalProducts / $productsPerPage);
$currentPage = isset($_REQUEST['page']) ? max(1, min($totalPages, intval($_REQUEST['page']))) : 1;
$offset = ($currentPage - 1) * $productsPerPage;

// Modify your existing SQL query to include LIMIT and OFFSET
$sql = "SELECT * FROM projects WHERE 1=1";
if (isset($_REQUEST['search-parts'])) {
    $searchTerm = $mysql->real_escape_string($_REQUEST['search-parts']);
    $sql .= " AND (project_name LIKE '%$searchTerm%' OR project_description LIKE '%$searchTerm%')";
}
$sql .= " LIMIT $productsPerPage OFFSET $offset";
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
            width: 60%;
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
        }

        .filter-section label {
            font-weight: bold;
            color: #a1a1a3;
            display: block;
            margin-bottom: 0.5em;
        }

        .filter-option {
            margin-left: 1em;
            color: #d1d1d3;
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
            width: 100%;
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
        <a href="component_results.php">Components</a>
        <a href="project_results.php">Projects</a>
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

        <?php
        $sql = "SELECT * FROM projects WHERE 1=1";

        if (isset($_REQUEST['search-parts'])) {
            $searchTerm = $mysql->real_escape_string($_REQUEST['search-parts']);

            $sql .= " AND (project_name LIKE '%$searchTerm%' OR project_description LIKE '%$searchTerm%')";
        }

        $sql .= " LIMIT $productsPerPage OFFSET $offset";

        $results = $mysql->query($sql);
        if (!$results) {
            echo "console.error('SQL Error: " . $mysql->error . "');";
            exit();
        }
//make products array
        $products = [];
        while ($currentrow = $results->fetch_assoc()) {
            $one = [
                'id' => mb_convert_encoding($currentrow['project_id'], "UTF-8", mb_detect_encoding($currentrow['project_id'])),
                'name' => mb_convert_encoding($currentrow['project_name'], "UTF-8", mb_detect_encoding($currentrow['project_name'])),
                'description' => mb_convert_encoding($currentrow['project_description'], "UTF-8", mb_detect_encoding($currentrow['project_description'])),
                'documentation' => mb_convert_encoding($currentrow['project_documentation_url'], "UTF-8", mb_detect_encoding($currentrow['project_documentation_url'])),
                'date' => mb_convert_encoding($currentrow['date'], "UTF-8", mb_detect_encoding($currentrow['date'])),
                'forks' => (int)$currentrow['fork_count'],
                'cost' => (int)$currentrow['cost'],
                // Using a placeholder image since project_image isn't in the database
                'imgSrc' => 'placeholder.jpg'
            ];
            $products[] = $one;
        }
        $json_data = json_encode($products, JSON_THROW_ON_ERROR);
        // var_dump($json_data);
        // echo json_encode($products);
        echo "const products = " . $json_data . ";";
        ?>

        const productContainer = document.getElementById("product-list");

        function displayProducts(products) {
            const productContainer = document.getElementById("product-list");
            const paginationElement = productContainer.querySelector(".pagination");
            productContainer.innerHTML = "";
            
            products.forEach(product => {
                const productElement = document.createElement("div");
                productElement.className = "product";
                productElement.addEventListener("click", () => {
                    const targetUrl = "project_description.php?id=" + encodeURIComponent(product.id);
                    console.log(`Navigating to: ${targetUrl}`);
                    window.location.href = targetUrl;
                });

                productElement.innerHTML = `
                    <img src="${product.imgSrc}" alt="${product.name}">
                    <div class="product-details">
                        <div class="product-info">
                            <h3>${product.name}</h3>
                            <p>${product.description}</p>
                            <p>Cost: $${product.cost}</p>
                            <p>Forks: ${product.forks}</p>
                            <p>Date: ${product.date}</p>
                        </div>
                    </div>
                `;
                productContainer.appendChild(productElement);
            });
            
            if (paginationElement) {
                productContainer.appendChild(paginationElement);
            }
        }

        displayProducts(products);
        console.log(products);
    });
</script>
</body>
</html>