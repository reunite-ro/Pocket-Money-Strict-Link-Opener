<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strict Link Opener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 2rem;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
        }
        .question-item {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .btn-remove {
            color: #dc3545;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4 text-center">Strict Link Opener</h1>
        <p class="text-center mb-4">Create links that require specific answers to questions before redirecting.</p>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Create a Protected Link</h5>
            </div>
            <div class="card-body">
                <form id="createLinkForm" action="create_link.php" method="post">
                    <div class="mb-3">
                        <label for="redirectUrl" class="form-label">Destination URL</label>
                        <input type="url" class="form-control" id="redirectUrl" name="redirectUrl" required placeholder="https://example.com">
                        <div class="form-text">The URL users will be redirected to after answering all questions correctly.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Security Questions</label>
                        <div id="questionsContainer">
                            <div class="question-item">
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" class="form-control" name="questions[]" placeholder="Question" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" name="answers[]" placeholder="Answer (case sensitive)" required>
                                    </div>
                                    <div class="col-auto">
                                        <span class="btn-remove" onclick="removeQuestion(this)"><i class="bi bi-trash"></i> Remove</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-secondary mt-2" id="addQuestionBtn">Add Another Question</button>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Generate Protected Link</button>
                </form>
            </div>
        </div>
        
        <?php if (isset($_SESSION['generatedLink'])): ?>
        <div class="alert alert-success">
            <h5>Your protected link has been created!</h5>
            <p>Share this link with others: <strong><?php echo $_SESSION['generatedLink']; ?></strong></p>
            <button class="btn btn-sm btn-outline-success" onclick="copyToClipboard('<?php echo $_SESSION['generatedLink']; ?>')">Copy Link</button>
        </div>
        <?php unset($_SESSION['generatedLink']); endif; ?>
    </div>

    <script>
        function addQuestion() {
            const container = document.getElementById('questionsContainer');
            const newQuestion = document.createElement('div');
            newQuestion.className = 'question-item';
            newQuestion.innerHTML = `
                <div class="row mb-2">
                    <div class="col">
                        <input type="text" class="form-control" name="questions[]" placeholder="Question" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="answers[]" placeholder="Answer (case sensitive)" required>
                    </div>
                    <div class="col-auto">
                        <span class="btn-remove" onclick="removeQuestion(this)"><i class="bi bi-trash"></i> Remove</span>
                    </div>
                </div>
            `;
            container.appendChild(newQuestion);
        }

        function removeQuestion(btn) {
            const questionItem = btn.closest('.question-item');
            if (document.querySelectorAll('.question-item').length > 1) {
                questionItem.remove();
            } else {
                alert('You need at least one question.');
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copied to clipboard!');
            });
        }

        document.getElementById('addQuestionBtn').addEventListener('click', addQuestion);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 