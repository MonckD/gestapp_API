<?php
// Configuration de la base de données
require_once('./db.php');

// Récupérer l'ID de l'utilisateur depuis l'URL
if (!isset($_GET['userId']) || empty($_GET['userId'])) {
    echo json_encode(['message' => 'User ID is required']);
    exit;
}

$userId = intval($_GET['userId']);

// Préparer et exécuter la requête de sélection avec WHERE
$sql = 'SELECT * FROM probleme WHERE userId = :userId ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute([':userId' => $userId]);

// Récupérer les résultats
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si des résultats ont été trouvés
if (empty($results)) {
    echo json_encode(['message' => 'No problems found']);
    exit;
}

// Retourner les résultats en JSON
header('Content-Type: application/json');
echo json_encode($results);
?>