<?php
session_start();

// Check if we have an ID parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid link');
}

$linkId = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['id']); // Sanitize the ID
$dataFile = 'data/' . $linkId . '.json';

// Check if the link exists
if (!file_exists($dataFile)) {
    die('This link does not exist or has expired');
}

// Load the link data
$linkData = json_decode(file_get_contents($dataFile), true);
if (!$linkData || !isset($linkData['questions']) || !isset($linkData['redirectUrl'])) {
    die('Invalid link data');
}

$redirectUrl = $linkData['redirectUrl'];
$questions = $linkData['questions'];

// Check if form was submitted to validate answers
$error = null;
$currentQuestion = 0;
$maxQuestions = count($questions);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $currentQuestion = isset($_POST['questionIndex']) ? intval($_POST['questionIndex']) : 0;
    $userAnswer = trim($_POST['answer']);
    
    // Verify answer for current question
    if ($userAnswer === $questions[$currentQuestion]['answer']) {
        // Correct answer, move to next question
        $currentQuestion++;
        
        // All questions answered correctly
        if ($currentQuestion >= $maxQuestions) {
            // Redirect to the target URL
            header('Location: ' . $redirectUrl);
            exit;
        }
    } else {
        $error = 'Incorrect answer. Please try again.';
    }
}

// Display the verification form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Security Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 2rem;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
        }
        .verification-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .progress {
            height: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="h3">Security Verification</h1>
            <p>Please answer the following questions to access the protected link.</p>
        </div>
        
        <div class="card verification-card">
            <div class="card-body">
                <!-- Progress bar -->
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: <?php echo ($currentQuestion / $maxQuestions) * 100; ?>%" 
                         aria-valuenow="<?php echo ($currentQuestion / $maxQuestions) * 100; ?>" 
                         aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <h5 class="card-title">Question <?php echo ($currentQuestion + 1); ?> of <?php echo $maxQuestions; ?></h5>
                
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <p class="mb-4"><?php echo $questions[$currentQuestion]['question']; ?></p>
                
                <form method="post">
                    <input type="hidden" name="questionIndex" value="<?php echo $currentQuestion; ?>">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="answer" placeholder="Your answer" required autocomplete="off">
                        <div class="form-text">Answers are case sensitive.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Answer</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 