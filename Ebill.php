<?php
$error = '';
$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consumer = trim($_POST['consumer_name'] ?? '');
    $previous = (float)($_POST['previous_reading'] ?? 0);
    $current = (float)($_POST['current_reading'] ?? 0);
    $customerType = $_POST['customer_type'] ?? '2';

    if ($current < $previous) {
        $error = 'Invalid Reading: Current reading cannot be lower than previous.';
    } else {
        $usage = $current - $previous;
        $rate = $usage <= 200 ? 10.00 : 15.00;
        $usageCharge = $usage * $rate;
        $surcharge = ($customerType === '1') ? 500 : 0;
        $total = $usageCharge + $surcharge;
        $result = sprintf(
            'Consumer: %s | Usage: %.2f kWh | Rate: ₱%.2f/kWh | Usage charge: ₱%.2f |  (CommercialCharge:) ₱%.2f | Total: ₱%.2f',
            $consumer ?: 'N/A', $usage, $rate, $usageCharge, $surcharge, $total
        );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ebill Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel = "stylesheet" href ="style.css">
    
</head>
<body>
     <div class ="container py-5" style = "margin-top: 80px;">
        <div class ="row justify-content-center">
            <div class ="col-lg-6 col-md-8 col-lg-6">
                <div class ="card shadow-sm">
                    <div class="card-body">

                        <h1 class="h4 fw-bold mt-2" style = "text-align: center;">Eco-Friendly Electric Bill App</h1>
                        <form method="post">
                            <label for="CunsumerName" class="form-label">Consumer Name</label>
                            <input id="heroName" name="consumer_name" type="text" class="form-control" placeholder="Your Name:" value="<?php echo htmlspecialchars($_POST['consumer_name'] ?? ''); ?>">

                            <label for="PreviousReading" class="form-label">Previous Reading (Kwh):</label>
                            <input id="heroName" name="previous_reading" type="text" class="form-control" placeholder="(Previous Kwh)" value="<?php echo htmlspecialchars($_POST['previous_reading'] ?? ''); ?>">

                            <label for="CurrentReading" class="form-label">Current Reading</label>
                            <input id="heroName" name="current_reading" type="text" class="form-control" placeholder="(Current Kwh)" value="<?php echo htmlspecialchars($_POST['current_reading'] ?? ''); ?>">

                            <label for="CustomerType" class="form-label">Customer Type</label>
                            <select class="form-select" id="floatingSelect" name="customer_type" aria-label="Floating label select example">
                            <option value="1" <?php echo (isset($_POST['customer_type']) && $_POST['customer_type'] === '1') ? 'selected' : ''; ?>>Commercial</option>
                            <option value="2" <?php echo (!isset($_POST['customer_type']) || $_POST['customer_type'] === '2') ? 'selected' : ''; ?>>Residential</option>
                            </select>
                            <button type="submit" id="submitBtn" class="btn btn-success w-100 mt-2">Calculate Bill</button>
                        </form>

                        <?php if ($error): ?>
                            <div class="alert alert-danger mt-2" role="alert"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <?php if ($result): ?>
                            <div class="alert alert-success mt-2" role="alert"><?php echo htmlspecialchars($result); ?></div>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>