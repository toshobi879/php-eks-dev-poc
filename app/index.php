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
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(120deg, #eef2f7, #f8fafc);
            color: #1f2933;
        }

        /* HEADER */
        .header {
            background: linear-gradient(90deg, #0f172a, #1e3a8a, #2563eb);
            color: white;
            padding: 22px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header span {
            font-size: 13px;
            opacity: 0.9;
        }

        .container {
            display: flex;
            height: calc(100vh - 90px);
        }

        /* SIDEBAR */
        .sidebar {
            width: 40%;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            padding: 24px;
            overflow-y: auto;
            border-right: 1px solid #e5e7eb;
        }

        .info-bar {
            background: linear-gradient(135deg, #dbeafe, #eef2ff);
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 14px;
            color: #1e3a8a;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .search-box input {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            outline: none;
            margin-bottom: 24px;
            transition: all 0.25s ease;
        }

        .search-box input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.15);
        }

        .office-card {
            background: linear-gradient(135deg, #ffffff, #f9fafb);
            padding: 18px 20px;
            border-radius: 18px;
            margin-bottom: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .office-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(37,99,235,0.08), rgba(99,102,241,0.08));
            opacity: 0;
            transition: 0.3s ease;
        }

        .office-card:hover::before {
            opacity: 1;
        }

        .office-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 14px 35px rgba(0,0,0,0.12);
            border-color: #c7d2fe;
        }

        .org {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .addr {
            font-size: 14px;
            color: #475569;
            margin-bottom: 10px;
        }

        .map-btn {
            display: inline-block;
            padding: 8px 14px;
            font-size: 12px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .map-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37,99,235,0.4);
        }

        .footer-note {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 25px;
        }

        /* MAP */
        .map {
            width: 60%;
            position: relative;
        }

        #map {
            height: 100%;
            border-radius: 0;
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 10px;
        }

        /* Subtle animation */
        .office-card {
            animation: fadeUp 0.4s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
