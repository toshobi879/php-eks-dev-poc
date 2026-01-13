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
<html>
<head>
    <title>California Department of Transportation – Office Locator</title>
    <meta charset="utf-8">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            color: #1f2933;
        }

        /* Header */
        .header {
            background: #003a8f;
            color: #fff;
            padding: 18px 30px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
            font-weight: 600;
        }

        .header span {
            font-size: 14px;
            margin-left: 10px;
            opacity: 0.85;
        }

        .container {
            display: flex;
            height: calc(100vh - 64px);
        }

        /* Sidebar */
        .sidebar {
            width: 38%;
            background: #ffffff;
            padding: 20px;
            overflow-y: auto;
            border-right: 1px solid #e5e7eb;
        }

        .search-box input {
            width: 100%;
            padding: 12px 14px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            outline: none;
            margin-bottom: 15px;
        }

        .search-box input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
        }

        .office-card {
            background: #f9fafb;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
        }

        .office-card:hover {
            background: #eef2ff;
            border-color: #c7d2fe;
            transform: translateY(-1px);
        }

        .org {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .addr {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.4;
        }

        .map {
            width: 62%;
        }

        #map {
            height: 100%;
        }

        /* Footer note */
        .footer-note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <h1>California Department of Transportation</h1>
    <span>Office Locator – Caltrans</span>
</div>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">

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
            </div>
        <?php endforeach; ?>

        <div class="footer-note">
            Data Source: Caltrans Offices • Powered by Amazon EKS & RDS
        </div>
    </div>

    <!-- Map -->
    <div class="map">
        <div id="map"></div>
    </div>

</div>

<script>
    var map = L.map('map').setView([37.0902, -95.7129], 4); // USA centered

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
