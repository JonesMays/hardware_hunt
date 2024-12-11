<?php
// Start the session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

if($mysql->connect_errno) {
    echo "db connection error : " . $mysql->connect_error;
    exit();
}

// Fetch all projects without pagination
$sql = "SELECT * FROM projects";
if (isset($_REQUEST['search-projects'])) {
    $searchTerm = $mysql->real_escape_string($_REQUEST['search-projects']);
    $sql .= " WHERE project_name LIKE '%$searchTerm%' OR project_description LIKE '%$searchTerm%'";
}

$results = $mysql->query($sql);
if (!$results) {
    echo "SQL error: ". $mysql->error;
    exit();
}

// Initialize projects array
$projects = [];
while ($currentrow = $results->fetch_assoc()) {
    $projects[] = [
        'id' => mb_convert_encoding($currentrow['project_id'], "UTF-8", mb_detect_encoding($currentrow['project_id'])),
        'name' => mb_convert_encoding($currentrow['project_name'], "UTF-8", mb_detect_encoding($currentrow['project_name'])),
        'description' => mb_convert_encoding($currentrow['project_description'], "UTF-8", mb_detect_encoding($currentrow['project_description'])),
        'documentation' => mb_convert_encoding($currentrow['project_documentation_url'], "UTF-8", mb_detect_encoding($currentrow['project_documentation_url'])),
        'date' => mb_convert_encoding($currentrow['date'], "UTF-8", mb_detect_encoding($currentrow['date'])),
        'cost' => (int)$currentrow['cost'],
        'imgSrc' => 'placeholder.jpg'
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
<?php
    $showPartsSearchBar = false;
    $showProjectsSearchBar = true;
    include 'navbar.php';
?>
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
    const productContainer = document.getElementById("product-list");
    
    // Pagination settings
    const productsPerPage = 10;
    let currentPage = 1;
    
    // Initialize with PHP data
    const allProjects = <?php echo json_encode($projects, JSON_THROW_ON_ERROR); ?>;
    let filteredProjects = [...allProjects];
    
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
                displayProjects();
            });
            paginationContainer.appendChild(pageLink);
        }
        
        return paginationContainer;
    }
    
    function displayProjects() {
        productContainer.innerHTML = "";
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * productsPerPage;
        const endIndex = startIndex + productsPerPage;
        const paginatedProjects = filteredProjects.slice(startIndex, endIndex);
        const totalPages = Math.ceil(filteredProjects.length / productsPerPage);
        
        // Display projects
        paginatedProjects.forEach(project => {
            const productElement = document.createElement("div");
            productElement.className = "product";
            productElement.addEventListener("click", () => {
                window.location.href = "project_details.php?id=" + encodeURIComponent(project.id);
            });

            productElement.innerHTML = `
                <div class="product-details">
                    <div class="product-info">
                        <h3>${project.name}</h3>
                        <p>${project.description}</p>
                        <p>Cost: $${project.cost}</p>
                        <p>Date: ${project.date}</p>
                    </div>
                </div>
            `;
            productContainer.appendChild(productElement);
        });
        
        // Add pagination
        const paginationElement = createPagination(totalPages);
        productContainer.appendChild(paginationElement);
    }
    
    // Function to handle search
    function handleSearch(searchTerm) {
        searchTerm = searchTerm.toLowerCase();
        
        filteredProjects = allProjects.filter(project => 
            project.name.toLowerCase().includes(searchTerm) ||
            project.description.toLowerCase().includes(searchTerm)
        );
        
        // Reset to first page when searching
        currentPage = 1;
        displayProjects();
    }
    
    // Listen for search events from the navbar
    const searchInput = document.querySelector('input[name="search-projects"]');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            handleSearch(e.target.value);
        });
    }
    
    // Initial display
    displayProjects();
});
</script>
</body>
</html>
