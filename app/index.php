<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed");
}

$search = $_GET['q'] ?? '';

$sql = "SELECT * FROM offices";
if ($search != '') {
    $sql .= " WHERE organization LIKE '%$search%' OR street_address LIKE '%$search%'";
}

$result = $conn->query($sql);
$offices = [];
while($row = $result->fetch_assoc()){
    $offices[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Caltrans Office Locator</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f3f6fb;
        }

        /* Header */
        .header {
            background: linear-gradient(90deg, #002b5c, #00509d);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .header span {
            font-size: 14px;
            opacity: 0.9;
        }

        .container {
            display: flex;
            height: calc(100vh - 80px);
        }

        /* Sidebar */
        .sidebar {
            width: 38%;
            background: white;
            padding: 20px;
            overflow-y: auto;
            border-right: 1px solid #e5e7eb;
        }

        .search-box input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            outline: none;
            margin-bottom: 20px;
        }

        .search-box input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .office-card {
            background: linear-gradient(135deg, #f9fafb, #ffffff);
            padding: 16px 18px;
            border-radius: 14px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: all 0.25s ease;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .office-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            border-color: #c7d2fe;
            background: #eef2ff;
        }

        .org {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 6px;
            color: #1f2933;
        }

        .addr {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .map-btn {
            display: inline-block;
            padding: 6px 12px;
            font-size: 12px;
            background: #2563eb;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .map-btn:hover {
            background: #1d4ed8;
        }

        /* Map */
        .map {
            width: 62%;
            position: relative;
        }

        #map {
            height: 100%;
        }

        .footer-note {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 15px;
        }

        /* Top info bar */
        .info-bar {
            background: #e0ecff;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            color: #1e3a8a;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div>
        <h1>California Department of Transportation</h1>
        <span>Office Locator Portal</span>
    </div>
    <div>
        <span>Powered by Amazon EKS & RDS</span>
    </div>
</div>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="info-bar">
            📍 Search Caltrans offices across California. Click any office to view on map.
        </div>

        <form method="get" class="search-box">
            <input type="text" name="q" placeholder="Search by city, district, or office name..." value="<?= htmlspecialchars($search) ?>">
        </form>

        <?php if (count($offices) == 0): ?>
            <p>No offices found.</p>
        <?php endif; ?>

        <?php foreach($offices as $o): ?>
            <div class="office-card"
                 onclick="focusMap(
                     <?= $o['latitude'] ?>,
                     <?= $o['longitude'] ?>,
                     '<?= addslashes($o['organization']) ?>',
                     '<?= addslashes($o['street_address']) ?>'
                 )">

                <div class="org"><?= $o['organization'] ?></div>
                <div class="addr"><?= $o['street_address'] ?></div>

                <a class="map-btn" target="_blank"
                   href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($o['street_address']) ?>">
                    View on Google Maps
                </a>
            </div>
        <?php endforeach; ?>

        <div class="footer-note">
            Data Source: Caltrans Offices • Enterprise Demo Application
        </div>

    </div>

    <!-- MAP -->
    <div class="map">
        <div id="map"></div>
    </div>

</div>

<script>
    var map = L.map('map').setView([37.0902, -95.7129], 4);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:'© OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    function focusMap(lat, lng, org, addr){
        if(marker){
            map.removeLayer(marker);
        }
        map.setView([lat, lng], 13);
        marker = L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>" + org + "</b><br>" + addr)
            .openPopup();
    }
</script>

</body>
</html>
