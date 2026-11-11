<?php
header('Content-Type: text/html; charset=UTF-8');

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$country = $_GET['country'] ?? '';
$country = is_string($country) ? trim($country) : '';

$conn = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password
);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$lookup = $_GET['lookup'] ?? '';
$isCities = ($lookup === 'cities');

if ($isCities) {
    $stmt = $conn->prepare(
        "SELECT cities.name, cities.district, cities.population
         FROM cities
         JOIN countries ON cities.country_code = countries.code
         WHERE countries.name LIKE :country
         ORDER BY cities.name"
    );
} else {
    $stmt = $conn->prepare(
        "SELECT * FROM countries
         WHERE name LIKE :country
         ORDER BY name"
    );
}

$stmt->execute([
    'country' => '%' . $country . '%'
]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

function escapeHtml($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
?>

<?php if (empty($results)): ?>
    <p>No <?= $isCities ? 'cities' : 'countries' ?> found.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <?php if ($isCities): ?>
                    <th scope="col">Name</th>
                    <th scope="col">District</th>
                    <th scope="col">Population</th>
                <?php else: ?>
                    <th scope="col">Country Name</th>
                    <th scope="col">Continent</th>
                    <th scope="col">Independence Year</th>
                    <th scope="col">Head of State</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= escapeHtml($row['name']) ?></td>

                    <?php if ($isCities): ?>
                        <td><?= escapeHtml($row['district']) ?></td>
                        <td>
                            <?= escapeHtml(number_format($row['population'])) ?>
                        </td>
                    <?php else: ?>
                        <td><?= escapeHtml($row['continent']) ?></td>
                        <td>
                            <?= escapeHtml($row['independence_year'] ?? 'N/A') ?>
                        </td>
                        <td>
                            <?= escapeHtml($row['head_of_state'] ?? 'N/A') ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>