<?php
session_start();

// Initialize budget data
if (!file_exists('budget_data.json')) {
    file_put_contents('budget_data.json', json_encode([
        'transactions' => [],
        'monthly_budget' => 0
    ]));
}

// Load data
$data = json_decode(file_get_contents('budget_data.json'), true);

// Handle budget setting
if (isset($_POST['set_budget'])) {
    $data['monthly_budget'] = floatval($_POST['monthly_budget']);
    file_put_contents('budget_data.json', json_encode($data));
    header('Location: index.php');
    exit;
}

// Handle transaction submission
if (isset($_POST['add_transaction'])) {
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

// Handle delete transaction
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $data['transactions'] = array_filter($data['transactions'], function($t) use ($deleteId) {
        return $t['id'] !== $deleteId;
    });
    $data['transactions'] = array_values($data['transactions']); // Re-index array
    file_put_contents('budget_data.json', json_encode($data));
    header('Location: index.php');
    exit;
}

// Calculate balance and expenses
$balance = 0;
$totalExpenses = 0;
$totalIncome = 0;

foreach ($data['transactions'] as $t) {
    if ($t['type'] === 'income') {
        $balance += $t['amount'];
        $totalIncome += $t['amount'];
    } else {
        $balance -= $t['amount'];
        $totalExpenses += $t['amount'];
    }
}

$monthlyBudget = $data['monthly_budget'];
$budgetRemaining = $monthlyBudget - $totalExpenses;
$budgetPercentage = $monthlyBudget > 0 ? ($totalExpenses / $monthlyBudget) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Budget Tracker Pro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px; 
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.2); 
        }
        h1 { 
            color: #333; 
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card.balance {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card.balance.negative {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card.income {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .stat-card.expense {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
            color: white;
        }
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: bold;
        }

        /* Budget Section */
        .budget-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .budget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .budget-header h3 {
            color: #333;
            font-size: 18px;
        }
        .edit-budget-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }
        .edit-budget-btn:hover {
            background: #5568d3;
        }
        .progress-bar {
            background: #e0e0e0;
            height: 30px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            margin-bottom: 10px;
        }
        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        .progress-fill.safe {
            background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
        }
        .progress-fill.warning {
            background: linear-gradient(90deg, #f2994a 0%, #f2c94c 100%);
        }
        .progress-fill.danger {
            background: linear-gradient(90deg, #ee0979 0%, #ff6a00 100%);
        }
        .budget-info {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #666;
        }

        /* Budget Form Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
        }
        
        form { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 20px; 
        }
        form h3 {
            margin-bottom: 15px;
            color: #333;
        }
        input, select, button { 
            width: 100%; 
            padding: 12px; 
            margin: 8px 0; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 14px; 
        }
        button { 
            background: #667eea; 
            color: white; 
            border: none; 
            cursor: pointer; 
            font-weight: bold; 
            transition: background 0.3s;
        }
        button:hover { 
            background: #5568d3; 
        }
        .btn-secondary {
            background: #6c757d;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .transactions { 
            margin-top: 20px; 
        }
        .transactions h2 {
            margin-bottom: 15px;
            color: #333;
        }
        .transaction { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            padding: 15px; 
            margin: 10px 0; 
            border-radius: 8px; 
            background: #f8f9fa;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .transaction:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .income { 
            border-left: 4px solid #4caf50; 
        }
        .expense { 
            border-left: 4px solid #f44336; 
        }
        .transaction-info {
            flex: 1;
        }
        .transaction-description {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .transaction-date {
            font-size: 12px;
            color: #999;
        }
        .transaction-amount {
            font-weight: bold;
            font-size: 18px;
            margin-right: 15px;
        }
        .income .transaction-amount { 
            color: #4caf50; 
        }
        .expense .transaction-amount { 
            color: #f44336; 
        }
        .delete-btn {
            background: #ff4444;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s;
        }
        .delete-btn:hover {
            background: #cc0000;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> PHP Budget Tracker </h1>
        <p class="subtitle">Track your income, expenses, and stay within budget</p>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card balance <?php echo $balance < 0 ? 'negative' : ''; ?>">
                <div class="stat-label">Current Balance</div>
                <div class="stat-value">$<?php echo number_format($balance, 2); ?></div>
            </div>
            <div class="stat-card income">
                <div class="stat-label">Total Income</div>
                <div class="stat-value">$<?php echo number_format($totalIncome, 2); ?></div>
            </div>
            <div class="stat-card expense">
                <div class="stat-label">Total Expenses</div>
                <div class="stat-value">$<?php echo number_format($totalExpenses, 2); ?></div>
            </div>
        </div>

        <!-- Budget Section -->
        <?php if ($monthlyBudget > 0): ?>
        <div class="budget-section">
            <div class="budget-header">
                <h3>📊 Monthly Budget Progress</h3>
                <button class="edit-budget-btn" onclick="showBudgetModal()">Edit Budget</button>
            </div>
            <div class="progress-bar">
                <div class="progress-fill <?php 
                    if ($budgetPercentage < 70) echo 'safe';
                    elseif ($budgetPercentage < 90) echo 'warning';
                    else echo 'danger';
                ?>" style="width: <?php echo min($budgetPercentage, 100); ?>%">
                    <?php echo round($budgetPercentage); ?>%
                </div>
            </div>
            <div class="budget-info">
                <span>Spent: $<?php echo number_format($totalExpenses, 2); ?> of $<?php echo number_format($monthlyBudget, 2); ?></span>
                <span <?php echo $budgetRemaining < 0 ? 'style="color: #f44336; font-weight: bold;"' : ''; ?>>
                    Remaining: $<?php echo number_format($budgetRemaining, 2); ?>
                </span>
            </div>
        </div>
        <?php else: ?>
        <div class="budget-section">
            <div class="budget-header">
                <h3>📊 Set Your Monthly Budget</h3>
            </div>
            <p style="color: #666; margin-bottom: 15px;">Set a monthly budget to track your spending limits</p>
            <button class="edit-budget-btn" onclick="showBudgetModal()">Set Budget Now</button>
        </div>
        <?php endif; ?>

        <!-- Budget Modal -->
        <div id="budgetModal" class="modal">
            <div class="modal-content">
                <h3>💵 Set Monthly Budget</h3>
                <form method="POST">
                    <input type="number" step="0.01" name="monthly_budget" placeholder="Enter budget amount (e.g., 3000)" value="<?php echo $monthlyBudget > 0 ? $monthlyBudget : ''; ?>" required>
                    <button type="submit" name="set_budget">Save Budget</button>
                    <button type="button" class="btn-secondary" onclick="hideBudgetModal()">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Transaction Form -->
        <form method="POST">
            <h3>➕ Add Transaction</h3>
            <input type="text" name="description" placeholder="Description (e.g., Salary, Coffee, Rent)" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <select name="type" required>
                <option value="">Select Type</option>
                <option value="income">💰 Income</option>
                <option value="expense">💸 Expense</option>
            </select>
            <button type="submit" name="add_transaction">Add Transaction</button>
        </form>

        <!-- Transactions List -->
        <div class="transactions">
            <h2>📝 Transaction History</h2>
            <?php if (empty($data['transactions'])): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <p>No transactions yet. Add your first one above!</p>
                </div>
            <?php else: ?>
                <?php foreach (array_reverse($data['transactions']) as $t): ?>
                    <div class="transaction <?php echo $t['type']; ?>">
                        <div class="transaction-info">
                            <div class="transaction-description"><?php echo htmlspecialchars($t['description']); ?></div>
                            <div class="transaction-date"><?php echo date('M d, Y g:i A', strtotime($t['date'])); ?></div>
                        </div>
                        <div class="transaction-amount">
                            <?php echo $t['type'] === 'income' ? '+' : '-'; ?>
                            $<?php echo number_format($t['amount'], 2); ?>
                        </div>
                        <a href="?delete=<?php echo $t['id']; ?>" onclick="return confirm('Delete this transaction?')">
                            <button type="button" class="delete-btn">🗑️ Delete</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showBudgetModal() {
            document.getElementById('budgetModal').classList.add('active');
        }
        
        function hideBudgetModal() {
            document.getElementById('budgetModal').classList.remove('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('budgetModal');
            if (event.target === modal) {
                hideBudgetModal();
            }
        }
    </script>
</body>
</html>