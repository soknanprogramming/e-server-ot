<?php
require_once '../../config/check_login.php';
require_once '../../repos/CategoriesProblem.php';
require_once '../../repos/CategoriesOrders.php';


// Get category ID from query string
$categoryId = isset($_GET['id_categories']) ? $_GET['id_categories'] : 0;
$problems = [];
if ($categoryId > 0) {
    $problemRepo = new CategoriesProblem();
    $problems = $problemRepo->getProblemsByCategoryOrderId($categoryId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../utils/main.css">
  <style>
    /* Container for the main content area */
    .main-content {
        /* Styles for the main area, assuming it takes up the remaining space */
        padding-left: 20px; /* Example, adjust as needed based on your sidebar */
        padding-top: 20px;
    }

    /* Outer container for centering the form */
    .container {
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem; /* p-4 equivalent */
        padding-right: 1rem; /* p-4 equivalent */
    }

    /* Form card styling */
    .form-card {
        max-width: 42rem; /* max-w-xl equivalent */
        margin-left: auto; /* mx-auto equivalent */
        margin-right: auto; /* mx-auto equivalent */
        margin-top: 2.5rem; /* mt-10 equivalent */
        padding: 1.5rem; /* p-6 equivalent */
        background-color: white; /* bg-white equivalent */
        border-radius: 0.375rem; /* rounded equivalent */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* shadow equivalent */
    }

    /* Spacing for form fields (space-y-6) */
    .form-space-y > div:not(:last-child) {
        margin-bottom: 1.5rem; /* space-y-6 equivalent */
    }

    /* Label styling */
    .form-label {
        display: block; /* block equivalent */
        font-size: 0.875rem; /* text-sm equivalent */
        font-weight: 500; /* font-medium equivalent */
        color: #374151; /* text-gray-700 equivalent */
        margin-bottom: 0.25rem; /* mb-1/mb-2 for location */
    }

    /* Required field indicator color */
    .required-star {
        color: #ef4444; /* text-red-500 equivalent */
    }

    /* Input, Select, and Textarea base styling */
    .form-input, .form-select, .form-textarea {
        display: block; /* block equivalent */
        width: 100%; /* w-full equivalent */
        margin-top: 0.25rem; /* mt-1 equivalent */
        border-width: 1px;
        border-color: #d1d5db; /* border-gray-300 equivalent */
        border-radius: 0.375rem; /* rounded-md equivalent */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm equivalent */
        padding: 0.5rem 0.75rem; /* padding for better look */
        /* Focus styles */
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #3b82f6; /* focus:border-blue-500 equivalent */
        box-shadow: 0 0 0 1px #3b82f6, 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* focus:ring-blue-500 equivalent */
    }

    /* Textarea specific */
    .form-textarea {
        resize: vertical;
    }

    /* Small label for location fields */
    .location-label {
        display: block;
        font-size: 0.75rem; /* text-xs equivalent */
        font-weight: 500;
        color: #4b5563; /* text-gray-600 equivalent */
    }

    /* Image Upload section */
    .file-input {
        display: block;
        width: 100%;
        font-size: 0.875rem;
        color: #374151;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        cursor: pointer;
        padding: 0.5rem 0.75rem;
        transition: all 0.15s ease-in-out;
    }

    .file-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 1px #3b82f6;
    }

    .file-note {
        font-size: 0.75rem; /* text-xs equivalent */
        color: #6b7280; /* text-gray-500 equivalent */
        margin-top: 0.25rem; /* mt-1 equivalent */
    }

    /* Image Preview Container (grid grid-cols-2 md:grid-cols-3 gap-3 mt-3) */
    #preview {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem; /* gap-3 equivalent */
        margin-top: 0.75rem; /* mt-3 equivalent */
    }
    @media (min-width: 768px) { /* md:grid-cols-3 equivalent */
        #preview {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    /* Individual Image Preview Item */
    .preview-item {
        position: relative; /* relative equivalent */
        width: 100%; /* w-full equivalent */
        height: 7rem; /* h-28 equivalent */
    }

    .preview-image {
        width: 100%; /* w-full equivalent */
        height: 100%; /* h-full equivalent */
        object-fit: cover; /* object-cover equivalent */
        border-radius: 0.375rem; /* rounded-md equivalent */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow equivalent */
    }

    /* Remove Image Button */
    .remove-btn {
        position: absolute; /* absolute equivalent */
        top: 0.25rem; /* top-1 equivalent */
        right: 0.25rem; /* right-1 equivalent */
        background-color: #dc2626; /* bg-red-600 equivalent */
        color: white; /* text-white equivalent */
        font-size: 0.75rem; /* text-xs equivalent */
        border-radius: 9999px; /* rounded-full equivalent */
        padding-left: 0.25rem; /* px-1 equivalent */
        padding-right: 0.25rem; /* px-1 equivalent */
        padding-top: 0.125rem; /* py-0.5 equivalent */
        padding-bottom: 0.125rem; /* py-0.5 equivalent */
        border: none;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }

    .remove-btn:hover {
        background-color: #b91c1c; /* hover:bg-red-700 equivalent */
    }

    /* Location Map */
    #map {
        width: 100%; /* w-full equivalent */
        height: 16rem; /* h-64 equivalent */
        border-radius: 0.25rem; /* rounded equivalent */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow equivalent */
        margin-bottom: 1rem; /* mb-4 equivalent */
    }

    /* Grid for location fields (grid grid-cols-2 gap-4) */
    .location-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem; /* gap-4 equivalent */
    }

    /* Submit Button */
    .submit-btn {
        width: 100%; /* w-full equivalent */
        padding-top: 0.5rem; /* py-2 equivalent */
        padding-bottom: 0.5rem; /* py-2 equivalent */
        padding-left: 1rem; /* px-4 equivalent */
        padding-right: 1rem; /* px-4 equivalent */
        background-color: #2563eb; /* bg-blue-600 equivalent */
        color: white; /* text-white equivalent */
        font-weight: 600; /* font-semibold equivalent */
        border-radius: 0.25rem; /* rounded equivalent */
        border: none;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out; /* transition equivalent */
    }

    .submit-btn:hover {
        background-color: #1d4ed8; /* hover:bg-blue-700 equivalent */
    }
  </style>
</head>
<body>


<aside class="sidebar">
  <ul>
    <li>
      <a class="home" href="/clients/index.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
          <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
          </svg>
        </div>
        Home
      </a>
    </li>

    <li>
      <a class="services active" href="/clients/services.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
          </svg>
        </div>
        Services
      </a>
    </li>
    <li>
      <a href="/clients/ordered.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-check" viewBox="0 0 16 16">
            <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
          </svg>
        </div>
        Ordered
      </a>
    </li>
    <li>
      <a href="/clients/profile.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
          </svg>
        </div>
        Profile
      </a>
    </li>
    <li>
      <a class="about" href="/clients/about.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
          </svg>
        </div>
        About
      </a>
    </li>
    <li>
        <a href="/clients/contact.php">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                </svg>
            </div>
            Contact
        </a>
    </li>
    <li class="settings">
      <a href="/clients/settings.php">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
          </svg>
        </div>
        Settings
      </a>
    </li>
  </ul>
</aside>

<div id="sidebar-container"></div>


  <div class="main-content">
    <?php
    // Fake categories data since the table is not created yet
    $categories = (new CategoriesOrders)->getCategoriesOrders();
    ?>

    <div class="container">
      <div class="form-card"> <form action="submit_order.php" id="uploadForm" method="POST" enctype="multipart/form-data" class="form-space-y"> <div>
                <label for="username" class="form-label">Username</label> <input type="text" id="username" name="username" required class="form-input"> </div>
            <div>
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" required class="form-input">
            </div>
            <div>
                <label for="problem" class="form-label">Problem<span class="required-star">*</span></label> <select id="problem" name="problem" required class="form-select">
                    <option value="">Select a Problem</option>
                    <?php foreach ($problems as $p): ?>
                        <option value="<?= htmlspecialchars($p['id']) ?>"><?= htmlspecialchars($p['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="problem_text" class="form-label">Problem</label>
                <textarea id="problem_text" name="problem_text" rows="3" required class="form-textarea"></textarea>
            </div>
            <div>
                <label for="imageInput" class="form-label mb-2">
                    Upload Images (1–5, can add multiple times)
                </label>
                <input type="file" id="images" name="images[]" class="file-input" multiple accept="image/*">
                <div id="preview"></div>

<script>
document.getElementById('images').addEventListener('change', function (event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';

    Array.from(event.target.files).forEach((file) => {
        const reader = new FileReader();

        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('preview-image');
            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    });
});
</script>


            <div>
                <label class="form-label mb-2">Location</label>
                <div id="map"></div> </div>
            <div>
                <label class="form-label mb-2">Location</label>
                <div class="location-grid"> <div>
                        <label for="province" class="location-label">Province/City</label> <select id="province" name="province" required class="form-select">
                            <option value="">Select Province</option>
                            <?php
                            require_once __DIR__ . '../../../repos/Location.php';
                            $location = new Location();
                            $provinces = $location->getProvinces();
                            foreach ($provinces as $p) {
                                echo "<option value='{$p['id']}'>{$p['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="district" class="location-label">District</label>
                        <select id="district" name="district" required class="form-select">
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div>
                        <label for="commune" class="location-label">Commune</label>
                        <select id="commune" name="commune" required class="form-select">
                            <option value="">Select Commune</option>
                        </select>
                    </div>
                    <div>
                        <label for="village" class="location-label">Village</label>
                        <select id="village" name="village" required class="form-select">
                            <option value="">Select Village</option>
                        </select>
                    </div>
                </div>
                <script>
                document.getElementById("province").addEventListener("change", function() {
                    fetch("../../../api/get_districts.php?province_id=" + this.value)
                        .then(res => res.json())
                        .then(data => {
                            // console.log(data);
                            let districtSelect = document.getElementById("district");
                            districtSelect.innerHTML = '<option value="">Select District</option>';
                            data.forEach(d => {
                                districtSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                            });
                            document.getElementById("commune").innerHTML = '<option value="">Select Commune</option>';
                            document.getElementById("village").innerHTML = '<option value="">Select Village</option>';
                        });
                });

                document.getElementById("district").addEventListener("change", function() {
                    fetch("../../../api/get_communes.php?district_id=" + this.value)
                        .then(res => res.json())
                        .then(data => {
                            let communeSelect = document.getElementById("commune");
                            communeSelect.innerHTML = '<option value="">Select Commune</option>';
                            data.forEach(c => {
                                communeSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                            });
                            document.getElementById("village").innerHTML = '<option value="">Select Village</option>';
                        });
                });

                document.getElementById("commune").addEventListener("change", function() {
                    fetch("../../../api/get_villages.php?commune_id=" + this.value)
                        .then(res => res.json())
                        .then(data => {
                            let villageSelect = document.getElementById("village");
                            villageSelect.innerHTML = '<option value="">Select Village</option>';
                            data.forEach(v => {
                                villageSelect.innerHTML += `<option value="${v.id}">${v.name}</option>`;
                            });
                        });
                });
                </script>

            </div>

                <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <button type="submit" class="submit-btn">Submit</button> </form>
      </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([12.5657, 104.9910], 7); // Default Cambodia
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker;

        // Try to get user location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                // Center map on user's location
                map.setView([lat, lng], 20);

                // Place marker
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup("You are here").openPopup();

                // Save to hidden fields
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }, function(error) {
                console.warn("Geolocation error:", error.message);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }

        // Allow user to click and move marker
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);

            // Update hidden fields with new position
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>

    </div>

  </div>

  <script src="utils/main.js"></script>
</body>
</html>