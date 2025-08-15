<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = $_POST['text'];
    $color = $_POST['color'];
    $font = $_POST['font'];
    $size = $_POST['size'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Text Formatter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f7f8;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .output {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Text Formatter</h2>
        <form method="post">
            <textarea name="text" rows="4" cols="50" placeholder="Enter your text here..."></textarea><br><br>
            <label>Choose Text Color:</label>
            <input type="color" name="color"><br><br>
            <label>Choose Font:</label>
            <select name="font">
                <option value="Arial">Arial</option>
                <option value="Verdana">Verdana</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Georgia">Georgia</option>
            </select><br><br>
            <label>Choose Font Size:</label>
            <input type="number" name="size" min="10" max="100" value="16"><br><br>
            <button type="submit">Format Text</button>
        </form>
        <?php if (!empty($text)) : ?>
            <div class="output" style="color: <?php echo htmlspecialchars($color); ?>; font-family: <?php echo htmlspecialchars($font); ?>; font-size: <?php echo htmlspecialchars($size); ?>px;">
                <?php echo nl2br(htmlspecialchars($text)); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
