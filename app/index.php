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
    <title>Office Locator</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body { margin:0; font-family: Arial, sans-serif; }
        .container { display:flex; height:100vh; }
        .sidebar {
            width:35%;
            padding:15px;
            overflow-y:auto;
            border-right:1px solid #ddd;
        }
        .map { width:65%; }
        #map { height:100%; }

        .search-box input {
            width:100%;
            padding:10px;
            font-size:16px;
            margin-bottom:10px;
        }
        .office-card {
            padding:10px;
            border-bottom:1px solid #eee;
            cursor:pointer;
        }
        .office-card:hover { background:#f5f5f5; }
        .org { font-weight:bold; }
        .addr { color:#555; font-size:14px; }
    </style>
</head>
<body>

<div class="container">

    <div class="sidebar">
        <h2>Office Locator</h2>

        <form method="get" class="search-box">
            <input type="text" name="q" placeholder="Search city or office..." value="<?= htmlspecialchars($search) ?>">
        </form>

        <?php foreach($offices as $o): ?>
            <div class="office-card" onclick="focusMap(<?= $o['latitude'] ?>, <?= $o['longitude'] ?>, '<?= addslashes($o['organization']) ?>', '<?= addslashes($o['street_address']) ?>')">
                <div class="org"><?= $o['organization'] ?></div>
                <div class="addr"><?= $o['street_address'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="map">
        <div id="map"></div>
    </div>

</div>

<script>
    var map = L.map('map').setView([20,0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:'© OpenStreetMap'
    }).addTo(map);

    var marker;

    function focusMap(lat, lng, org, addr){
        if(marker){
            map.removeLayer(marker);
        }
        map.setView([lat, lng], 13);
        marker = L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>"+org+"</b><br>"+addr).openPopup();
    }
</script>

</body>
</html>
