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

$stmt = $conn->prepare(
    "SELECT * FROM countries WHERE name LIKE :country"
);

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
    <p>No countries found.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th scope="col">Country Name</th>
                <th scope="col">Continent</th>
                <th scope="col">Independence Year</th>
                <th scope="col">Head of State</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= escapeHtml($row['name']) ?></td>
                    <td><?= escapeHtml($row['continent']) ?></td>
                    <td>
                        <?= escapeHtml($row['independence_year'] ?? 'N/A') ?>
                    </td>
                    <td>
                        <?= escapeHtml($row['head_of_state'] ?? 'N/A') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>