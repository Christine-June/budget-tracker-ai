<?php
session_start();

// Initialize budget data
if (!file_exists('budget_data.json')) {
    file_put_contents('budget_data.json', json_encode(['transactions' => []]));
}

// Load data
$data = json_decode(file_get_contents('budget_data.json'), true);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction = [
        'id' => uniqid(),
        'description' => $_POST['description'],
        'amount' => floatval($_POST['amount']),
        'type' => $_POST['type'],
        'date' => date('Y-m-d H:i:s')
    ];
    
    $data['transactions'][] = $transaction;
    file_put_contents('budget_data.json', json_encode($data));
    header('Location: index.php');
    exit;
}

// Calculate balance
$balance = 0;
foreach ($data['transactions'] as $t) {
    $balance += ($t['type'] === 'income') ? $t['amount'] : -$t['amount'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Budget Tracker</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .balance { font-size: 36px; font-weight: bold; margin: 20px 0; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-align: center; }
        .balance.negative { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        form { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input, select, button { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        button { background: #667eea; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #5568d3; }
        .transactions { margin-top: 20px; }
        .transaction { display: flex; justify-content: space-between; padding: 15px; margin: 10px 0; border-radius: 5px; background: #f8f9fa; }
        .income { border-left: 4px solid #4caf50; }
        .expense { border-left: 4px solid #f44336; }
        .amount { font-weight: bold; }
        .income .amount { color: #4caf50; }
        .expense .amount { color: #f44336; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💰 PHP Budget Tracker</h1>
        
        <div class="balance <?php echo $balance < 0 ? 'negative' : ''; ?>">
            Balance: $<?php echo number_format($balance, 2); ?>
        </div>

        <form method="POST">
            <input type="text" name="description" placeholder="Description (e.g., Salary, Coffee)" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <select name="type" required>
                <option value="">Select Type</option>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
            <button type="submit">Add Transaction</button>
        </form>

        <div class="transactions">
            <h2>Recent Transactions</h2>
            <?php if (empty($data['transactions'])): ?>
                <p style="text-align: center; color: #999; margin-top: 20px;">No transactions yet. Add your first one above!</p>
            <?php else: ?>
                <?php foreach (array_reverse($data['transactions']) as $t): ?>
                    <div class="transaction <?php echo $t['type']; ?>">
                        <div>
                            <strong><?php echo htmlspecialchars($t['description']); ?></strong>
                            <div style="font-size: 12px; color: #999;"><?php echo $t['date']; ?></div>
                        </div>
                        <div class="amount">
                            <?php echo $t['type'] === 'income' ? '+' : '-'; ?>
                            $<?php echo number_format($t['amount'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>