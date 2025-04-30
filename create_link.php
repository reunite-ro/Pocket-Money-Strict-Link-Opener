<?php
session_start();

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['redirectUrl']) || empty($_POST['questions']) || empty($_POST['answers'])) {
    header('Location: index.php');
    exit;
}

// Get form data
$redirectUrl = filter_var($_POST['redirectUrl'], FILTER_SANITIZE_URL);
$questions = $_POST['questions'];
$answers = $_POST['answers'];

// Validate redirect URL
if (!filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
    $_SESSION['error'] = 'Invalid redirect URL';
    header('Location: index.php');
    exit;
}

// Ensure we have at least one question
if (count($questions) < 1 || count($answers) < 1 || count($questions) !== count($answers)) {
    $_SESSION['error'] = 'Please provide at least one question and answer';
    header('Location: index.php');
    exit;
}

// Create a unique ID for this protected link
$linkId = bin2hex(random_bytes(8)); // 16 character unique ID

// Create data directory if it doesn't exist
$dataDir = 'data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Prepare data to store
$linkData = [
    'redirectUrl' => $redirectUrl,
    'questions' => [],
    'createdAt' => time()
];

// Store questions and answers
for ($i = 0; $i < count($questions); $i++) {
    if (!empty($questions[$i]) && !empty($answers[$i])) {
        $linkData['questions'][] = [
            'question' => htmlspecialchars($questions[$i], ENT_QUOTES, 'UTF-8'),
            'answer' => $answers[$i] // We store the exact answer for case-sensitive matching
        ];
    }
}

// Save data to JSON file
$filePath = $dataDir . '/' . $linkId . '.json';
file_put_contents($filePath, json_encode($linkData, JSON_PRETTY_PRINT));

// Generate link to share
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];

// Get the directory path of the application
$currentDir = dirname($_SERVER['PHP_SELF']);
// Normalize path to ensure it has a trailing slash
$basePath = $currentDir === '/' ? '/' : $currentDir . '/';

// Create the full URL with proper path to verify.php
$generatedLink = $protocol . $host . $basePath . 'verify.php?id=' . $linkId;

// Store in session to display on index page
$_SESSION['generatedLink'] = $generatedLink;

// Redirect back to index
header('Location: index.php');
exit; 