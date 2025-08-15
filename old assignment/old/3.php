<?php
$departments = [
    'BPCCS' => ['Dr.Rupesh', 'Dr. R. Mehta', 'Prof.himani'],
    'LDRP' => ['Dr. Bhrantav Vora', 'Prof. KINJAL PATEL', 'Prof. Riya Patel'],
    'Mechanical' => ['Dr. M. Desai', 'Prof. H. Nair', 'Dr. J. Singh']
];

$selectedDepartment = $_POST['department'] ?? '';
$facultyList = $selectedDepartment && isset($departments[$selectedDepartment]) ? $departments[$selectedDepartment] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Institute Faculty Info</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right,rgb(175, 84, 160),rgb(218, 140, 94));
            margin: 0;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        button {
            background: #4CAF50;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #45a049;
        }
        .output {
            background: #e0f7fa;
            padding: 10px;
            border-radius: 8px;
            margin-top: 20px;
            color: #00796b;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Faculty Information</h2>
        <form method="post">
            <select name="department">
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept => $faculty): ?>
                    <option value="<?php echo $dept; ?>" <?php echo $selectedDepartment == $dept ? 'selected' : ''; ?>>
                        <?php echo $dept; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Show Faculty</button>
        </form>
        <?php if (!empty($facultyList)) : ?>
            <div class="output">
                <h3>Faculty in <?php echo $selectedDepartment; ?>:</h3>
                <ul>
                    <?php foreach ($facultyList as $faculty): ?>
                        <li><?php echo $faculty; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($selectedDepartment) : ?>
            <div class="output">No faculty found for this department.</div>
        <?php endif; ?>
    </div>
</body>
</html>
