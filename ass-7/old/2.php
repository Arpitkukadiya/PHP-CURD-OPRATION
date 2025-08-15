<?php
$totalPay = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hours = (int)$_POST['hours'];
    $rate = (float)$_POST['rate'];
    $overtimeHours = isset($_POST['overtime']) ? (int)$_POST['overtime_hours'] : 0;

    $regularPay = $hours * $rate;
    $overtimePay = $overtimeHours * ($rate * 2);

    $totalPay = $regularPay + $overtimePay;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Weekly Pay Calculator</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right,rgb(62, 152, 90),rgb(5, 84, 17));
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
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input, select, button {
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
        <h2>Weekly Pay Calculator</h2>
        <form method="post">
            <label>Hours Worked:</label>
            <input type="number" name="hours" min="0" required>
            <label>Hourly Rate:</label>
            <select name="rate">
                <option value="100">₹100</option>
                <option value="200">₹200</option>
                <option value="300">₹300</option>
                <option value="400">₹400</option>
            </select>
            <label>Overtime Done:</label>
            <input type="checkbox" name="overtime" id="overtimeCheck" onclick="toggleOvertime()">
            <div id="overtimeSection" style="display: none;">
                <label>Overtime Hours:</label>
                <input type="number" name="overtime_hours" min="0">
            </div>
            <button type="submit">Calculate Pay</button>
        </form>
        <?php if ($totalPay > 0) : ?>
            <div class="output">
                Total Weekly Pay: ₹<?php echo number_format($totalPay, 2); ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleOvertime() {
            const overtimeSection = document.getElementById('overtimeSection');
            overtimeSection.style.display = overtimeSection.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>