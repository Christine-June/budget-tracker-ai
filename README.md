# 💰 Prompt-Powered Kickstart: Building a Beginner's Toolkit for PHP

## 📍 1. Title & Objective

**Project Title:** "Getting Started with PHP - Building a Budget Tracker Web Application"

**Technology Chosen:** PHP (Hypertext Preprocessor)

**Why did I choose it?**
- PHP is widely used but different from Python, Java, and JavaScript (project requirements)
- It's beginner-friendly with immediate visual feedback
- Powers 40% of the web (WordPress, Facebook originally)
- Perfect for learning server-side programming
- No complex setup required - has built-in development server

**What's the end goal?**
Create a fully functional budget tracker web application that:
- Tracks income and expenses
- Sets and monitors monthly budget limits
- Shows real-time balance calculations
- Provides visual progress indicators
- Stores data persistently using JSON files

---

## 📝 2. Quick Summary of the Technology

**What is PHP?**
PHP (Hypertext Preprocessor) is a popular server-side scripting language designed specifically for web development. Unlike JavaScript which runs in the browser, PHP executes on the server and generates HTML that is sent to the client.

**Where is it used?**
- **WordPress** - Powers 43% of all websites globally
- **Facebook** - Originally built entirely in PHP
- **Wikipedia** - Uses PHP with MediaWiki
- **Slack** - Backend systems use PHP
- **Mailchimp** - Email marketing platform
- **Etsy** - E-commerce marketplace

**One real-world example:**
When you log into WordPress to write a blog post, PHP processes your credentials on the server, checks the database, manages your session, retrieves your posts, and generates the admin dashboard HTML - all before sending anything to your browser.

---

## 🖥️ 3. System Requirements

**Operating System:**
- ✅ Windows 10/11
- ✅ macOS 10.15+
- ✅ Linux (Ubuntu 20.04+, Debian, Fedora)

**Required Tools:**
- **PHP 7.4 or higher** (8.0+ recommended)
- **Text Editor:** VS Code, Sublime Text, or any code editor
- **Web Browser:** Chrome, Firefox, Safari, or Edge
- **Terminal/Command Prompt:** Built into all operating systems

**Optional (Not Required for this project):**
- Git (for version control)
- GitHub account (for code hosting)

**No Database Required!** This project uses JSON file storage for simplicity.

---

## ⚙️ 4. Installation & Setup Instructions

### Step 1: Check if PHP is Already Installed

Open your terminal and run:
```bash
php -v
```

**Expected Output:**
```
PHP 8.2.0 (cli) (built: Dec  7 2024 10:15:23) (NTS)
```

If you see a version number, **skip to Step 3**. If not, continue to Step 2.

---

### Step 2: Install PHP (If Needed)

#### **Windows:**

1. Download PHP from: https://windows.php.net/download/
2. Choose "Thread Safe" version (ZIP file)
3. Extract to `C:\php`
4. Add to System PATH:
   - Right-click "This PC" → Properties
   - Advanced System Settings → Environment Variables
   - Edit "Path" → Add `C:\php`
5. Restart terminal and verify: `php -v`

#### **macOS:**

PHP comes pre-installed on macOS, but you can install the latest version:
```bash
brew install php
```

Verify installation:
```bash
php -v
```

#### **Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install php php-cli php-json
```

Verify installation:
```bash
php -v
```

#### **Linux (Fedora/RHEL):**
```bash
sudo dnf install php php-cli php-json
```

---

### Step 3: Create Project Directory
```bash
mkdir php-budget-tracker
cd php-budget-tracker
```

---

### Step 4: Create the Application File

Create a file named `index.php` in your project directory.

**Using terminal:**
```bash
touch index.php
```

**Using VS Code:**
- Open VS Code
- File → Open Folder → Select `php-budget-tracker`
- New File → Name it `index.php`

Copy the complete code from the GitHub repository into this file.

---

### Step 5: Run the Application

Start the built-in PHP development server:
```bash
php -S localhost:8000
```

**Expected Output:**
```
[Thu Nov 27 14:30:00 2025] PHP 8.2.0 Development Server (http://localhost:8000) started
```

---

### Step 6: Access in Browser

Open your web browser and navigate to:
```
http://localhost:8000
```

You should see the Budget Tracker interface! 🎉

---

### Step 7: Stop the Server (When Done)

Press `Ctrl + C` in the terminal to stop the server.

---

## 💻 5. Minimal Working Example

### What This Example Does:

The PHP Budget Tracker demonstrates core PHP concepts:
- **File I/O Operations:** Reading and writing JSON data
- **Form Processing:** Handling POST requests
- **Data Persistence:** Storing transactions in a JSON file
- **Dynamic HTML Generation:** Rendering data with PHP
- **Session Management:** Maintaining application state
- **Calculations:** Computing balances and percentages

### Key Features Implemented:

1. ✅ **Add Income/Expenses** - Form submission and data storage
2. ✅ **Set Monthly Budget** - Budget limit with progress tracking
3. ✅ **Visual Progress Bar** - Color-coded warnings (green → yellow → red)
4. ✅ **Calculate Balance** - Real-time income minus expenses
5. ✅ **Transaction History** - Display all transactions with timestamps
6. ✅ **Delete Transactions** - Remove entries with confirmation
7. ✅ **Persistent Storage** - Data survives server restarts

---

### Code Structure Breakdown:
```php
<?php
// 1. Start PHP session for maintaining user state
session_start();

// 2. Initialize JSON data file if it doesn't exist
if (!file_exists('budget_data.json')) {
    file_put_contents('budget_data.json', json_encode([
        'transactions' => [],
        'monthly_budget' => 0
    ]));
}

// 3. Load existing data from JSON file
$data = json_decode(file_get_contents('budget_data.json'), true);

// 4. Handle budget setting form submission
if (isset($_POST['set_budget'])) {
    $data['monthly_budget'] = floatval($_POST['monthly_budget']);
    file_put_contents('budget_data.json', json_encode($data));
    header('Location: index.php'); // Redirect to prevent resubmission
    exit;
}

// 5. Handle transaction form submission
if (isset($_POST['add_transaction'])) {
    $transaction = [
        'id' => uniqid(), // Generate unique ID
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

// 6. Calculate totals
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

// 7. Calculate budget progress
$monthlyBudget = $data['monthly_budget'];
$budgetRemaining = $monthlyBudget - $totalExpenses;
$budgetPercentage = $monthlyBudget > 0 ? ($totalExpenses / $monthlyBudget) * 100 : 0;
?>
```

---

### Expected Output:

#### **Initial State (No Budget Set):**
- Balance: $0.00
- Total Income: $0.00
- Total Expenses: $0.00
- Message: "Set Your Monthly Budget"

#### **After Setting Budget ($3000):**
- Budget progress bar appears
- Shows: "Spent: $0.00 of $3,000.00"
- Remaining: $3,000.00

#### **After Adding Income ($5000 - Salary):**
- Balance: +$5,000.00 (green)
- Total Income: $5,000.00
- Transaction appears in history with green border

#### **After Adding Expense ($150 - Groceries):**
- Balance: $4,850.00
- Total Expenses: $150.00
- Budget progress: 5% (green bar)
- Remaining: $2,850.00

#### **After More Expenses (Total $2700):**
- Progress bar: 90% (changes to red/warning)
- Remaining: $300.00

---

## 🤖 6. AI Prompt Journal

### Prompt 1: Technology Selection
**Prompt Used:**
```
"Recommend a beginner-friendly web technology (not Python, Java, or JavaScript) 
for building a budget tracker that demonstrates core programming concepts and 
has practical real-world applications."
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI recommended PHP because:
- Widely used (WordPress, Facebook heritage)
- Built-in development server (no Apache/Nginx needed)
- Straightforward syntax for beginners
- Can use JSON instead of databases for simple projects

**My evaluation:**
⭐⭐⭐⭐⭐ Excellent suggestion. PHP's simplicity and instant visual feedback made learning fast. The built-in server eliminated complex setup.

---

### Prompt 2: Project Setup
**Prompt Used:**
```
"How do I install PHP on my system and run a local development server? 
I need step-by-step instructions for beginners."
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI explained:
- Built-in PHP server: `php -S localhost:8000`
- No need for XAMPP/WAMP/MAMP
- How to check if PHP is installed: `php -v`

**My evaluation:**
⭐⭐⭐⭐⭐ Game-changer! I didn't know PHP had a built-in server. This saved hours of configuration.

---

### Prompt 3: Data Storage Strategy
**Prompt Used:**
```
"What's the simplest way to store budget data in PHP without using MySQL 
or any database? I want data to persist between sessions."
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI suggested using JSON files with:
- `file_get_contents()` to read data
- `json_decode()` to parse JSON
- `json_encode()` to convert arrays to JSON
- `file_put_contents()` to save data

**My evaluation:**
⭐⭐⭐⭐⭐ Perfect for this project. JSON is human-readable and doesn't require database configuration. The functions were intuitive.

---

### Prompt 4: Form Handling
**Prompt Used:**
```
"How do I handle form submissions in PHP and prevent duplicate submissions 
when users refresh the page?"
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI explained:
- Check `$_SERVER['REQUEST_METHOD'] === 'POST'`
- Access form data via `$_POST` superglobal
- Use `header('Location: index.php')` followed by `exit` to redirect (PRG pattern)

**My evaluation:**
⭐⭐⭐⭐⭐ This solved the duplicate submission bug immediately. The PRG (Post-Redirect-Get) pattern is a best practice I'll use forever.

---

### Prompt 5: Budget Progress Calculation
**Prompt Used:**
```
"How do I calculate budget progress percentage in PHP and change colors 
based on spending thresholds (safe, warning, danger)?"
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI provided:
- Formula: `($totalExpenses / $monthlyBudget) * 100`
- Conditional logic for color classes
- CSS gradient backgrounds for visual appeal

**My evaluation:**
⭐⭐⭐⭐ Very helpful. The ternary operators made the code concise. The visual feedback improves user experience significantly.

---

### Prompt 6: Delete Functionality
**Prompt Used:**
```
"How do I implement a delete button for each transaction in PHP that removes 
items from the JSON file safely?"
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI suggested:
- Use `array_filter()` to remove transactions by ID
- Re-index array with `array_values()`
- Add JavaScript confirmation: `onclick="return confirm()"`

**My evaluation:**
⭐⭐⭐⭐ Solid solution. The confirmation dialog prevents accidental deletions. Array manipulation was straightforward.

---

### Prompt 7: UI/UX Improvements
**Prompt Used:**
```
"Suggest modern CSS styling techniques for a budget tracker without using 
frameworks like Bootstrap or Tailwind. I want it to look professional."
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI recommended:
- CSS Grid for responsive layouts
- Linear gradients for visual depth
- Hover effects with transitions
- Color-coded elements (green for income, red for expenses)

**My evaluation:**
⭐⭐⭐⭐⭐ Exceeded expectations. The gradients and animations make it look like a premium app. No framework needed.

---

### Prompt 8: Error Handling
**Prompt Used:**
```
"What are common errors when working with PHP file operations and form handling? 
How do I prevent them?"
```

**Link to curriculum:** https://ai.moringaschool.com

**AI's Response Summary:**
The AI warned about:
- File permission issues (use `chmod 755`)
- Missing `exit` after `header()` redirects
- Not checking if files exist before reading
- XSS vulnerabilities (use `htmlspecialchars()`)

**My evaluation:**
⭐⭐⭐⭐ Practical advice. I implemented `htmlspecialchars()` for all user input display to prevent XSS attacks.

---

## ❌ 7. Common Issues & Fixes

### Issue 1: "PHP command not found"

**Error Message:**
```bash
bash: php: command not found
```

**Cause:**
PHP is not installed or not in your system's PATH environment variable.

**Solution:**
1. Install PHP using instructions in Section 4
2. Restart your terminal after installation
3. Verify with `php -v`

**Alternative:**
If installed but not in PATH (Windows):
- Add PHP directory to System Environment Variables
- Path: `C:\php` or wherever you installed it

**Reference:** [PHP Installation Guide](https://www.php.net/manual/en/install.php)

---

### Issue 2: Port 8000 Already in Use

**Error Message:**
```bash
[Thu Nov 27 14:30:00 2025] Failed to listen on localhost:8000 (reason: Address already in use)
```

**Cause:**
Another application is using port 8000 (maybe another PHP server or development tool).

**Solution:**
Use a different port:
```bash
php -S localhost:8080
```

Then access: `http://localhost:8080`

**Finding What's Using the Port (Optional):**
- **Windows:** `netstat -ano | findstr :8000`
- **Mac/Linux:** `lsof -i :8000`

---

### Issue 3: Permission Denied Creating budget_data.json

**Error Message:**
```bash
Warning: file_put_contents(budget_data.json): failed to open stream: Permission denied
```

**Cause:**
Your user doesn't have write permissions in the project directory.

**Solution (Mac/Linux):**
```bash
chmod 755 .
```

**Solution (Windows):**
- Right-click project folder → Properties → Security
- Edit permissions for your user
- Enable "Write" permission

---

### Issue 4: Blank Page / No Output

**Symptoms:**
- Browser shows completely white page
- No error messages visible

**Cause:**
PHP syntax error preventing the script from executing.

**Solution:**
Check for syntax errors:
```bash
php -l index.php
```

Expected output if no errors:
```
No syntax errors detected in index.php
```

**Common Syntax Errors:**
- Missing semicolons `;`
- Unclosed strings or brackets
- Forgotten `<?php` opening tag

**Enable Error Display (for development only):**
Add to top of `index.php`:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

### Issue 5: Transactions Not Saving

**Symptoms:**
- Form submits but transaction doesn't appear
- Page refreshes but no data

**Debugging Steps:**

1. **Check if JSON file was created:**
```bash
ls -la budget_data.json
```

2. **Check file contents:**
```bash
cat budget_data.json
```

Should show:
```json
{"transactions":[],"monthly_budget":0}
```

3. **Check browser console (F12):**
Look for JavaScript errors

4. **Verify form has correct attributes:**
```php
<form method="POST">
    ...
    <button type="submit" name="add_transaction">
```

**Common Causes:**
- Form missing `method="POST"`
- Button missing `name` attribute
- `$_POST` array not being checked correctly

---

### Issue 6: Budget Progress Not Updating

**Symptoms:**
- Expenses added but progress bar doesn't move
- Percentage shows 0%

**Solution:**
Ensure monthly budget is set first:
1. Click "Set Budget Now" button
2. Enter an amount (e.g., 3000)
3. Submit the form
4. Then add expenses

**Check Calculation:**
Add debugging output temporarily:
```php
echo "Budget: $monthlyBudget, Expenses: $totalExpenses, Percentage: $budgetPercentage";
```

---

### Issue 7: Delete Button Not Working

**Symptoms:**
- Click delete but transaction remains
- No confirmation dialog appears

**Solutions:**

1. **JavaScript confirm not working:**
Check browser console for errors

2. **ID not matching:**
Verify transaction ID in URL matches database

3. **Test with simple confirmation:**
```php
<a href="?delete=<?php echo $t['id']; ?>" 
   onclick="return confirm('Delete this?');">
```

---

### Issue 8: JSON File Gets Corrupted

**Symptoms:**
```
Warning: json_decode() expects parameter 1 to be string
```

**Cause:**
JSON file has invalid syntax (usually from manual editing).

**Solution:**
Delete and recreate the file:
```bash
rm budget_data.json
```

Refresh the page - PHP will recreate it automatically.

**Prevention:**
Never manually edit `budget_data.json` while the app is running.

---

### Helpful Resources:

- [PHP Manual](https://www.php.net/manual/en/)
- [Stack Overflow - PHP](https://stackoverflow.com/questions/tagged/php)
- [PHP File Handling Tutorial](https://www.w3schools.com/php/php_file.asp)
- [PHP Form Validation](https://www.php.net/manual/en/tutorial.forms.php)

---

## 📚 8. References

### Official Documentation
- **PHP Manual:** https://www.php.net/manual/en/
- **PHP Getting Started:** https://www.php.net/manual/en/getting-started.php
- **File System Functions:** https://www.php.net/manual/en/book.filesystem.php
- **JSON Functions:** https://www.php.net/manual/en/book.json.php
- **Form Handling:** https://www.php.net/manual/en/tutorial.forms.php

### Video Tutorials
- **PHP in 100 Seconds** - Fireship: https://www.youtube.com/watch?v=a7_WFUlFS94
- **PHP for Beginners** - Traversy Media: https://www.youtube.com/watch?v=BUCiSSyIGGU
- **Learn PHP in 15 Minutes** - Web Dev Simplified: https://www.youtube.com/watch?v=ZdP0KM49IVk

### Interactive Learning
- **PHP: The Right Way:** https://phptherightway.com/
- **W3Schools PHP Tutorial:** https://www.w3schools.com/php/
- **Learn PHP in Y Minutes:** https://learnxinyminutes.com/docs/php/
- **PHP Exercises:** https://www.w3resource.com/php-exercises/

### Helpful Blog Posts
- **Understanding PHP Superglobals:** https://www.php.net/manual/en/language.variables.superglobals.php
- **JSON in PHP:** https://www.geeksforgeeks.org/how-to-create-and-parse-json-data-with-php/
- **PHP Security Best Practices:** https://www.php.net/manual/en/security.php

### Community Support
- **r/PHP (Reddit):** https://www.reddit.com/r/PHP/
- **Stack Overflow PHP Tag:** https://stackoverflow.com/questions/tagged/php
- **PHP on Discord:** https://discord.gg/php
- **Dev.to PHP Community:** https://dev.to/t/php

### Tools & Extensions
- **PHP Storm (IDE):** https://www.jetbrains.com/phpstorm/
- **VS Code PHP Extensions:** Search "PHP Intelephense" in VS Code
- **Composer (Dependency Manager):** https://getcomposer.org/

---

## 🚀 Project Files Structure
```
php-budget-tracker/
├── index.php              # Main application file
├── budget_data.json       # Auto-generated data storage
├── README.md              # This file (toolkit documentation)
├── DOCUMENTATION.md       # AI learning reflections
└── .gitignore            # Git ignore rules
```

---

## 📊 Project Statistics

- **Total Development Time:** ~2 hours
- **Lines of Code:** ~450
- **AI Prompts Used:** 8 comprehensive prompts
- **Errors Debugged:** 6 major issues
- **Features Implemented:** 7 core features
- **Technologies Learned:** PHP, JSON, Form Handling, File I/O

---

## ✅ Learning Outcomes

By completing this project, I successfully learned:

### Technical Skills:
1. ✅ PHP syntax and programming fundamentals
2. ✅ HTTP request handling (GET/POST methods)
3. ✅ File I/O operations in PHP
4. ✅ JSON data serialization and deserialization
5. ✅ HTML form processing and validation
6. ✅ Session management basics
7. ✅ Running local PHP development servers
8. ✅ Debugging PHP applications

### Problem-Solving Skills:
- Breaking down complex features into smaller tasks
- Using AI prompts effectively for learning
- Debugging errors systematically
- Implementing user feedback (progress bars, color coding)

### Best Practices:
- Post-Redirect-Get (PRG) pattern for forms
- Input sanitization with `htmlspecialchars()`
- Error checking before file operations
- Meaningful variable naming
- Code comments for clarity

---

## 🔮 Future Improvements

Want to take this project further? Here are ideas:

### Short-term Enhancements:
- [ ] Add transaction categories (Food, Transport, Entertainment)
- [ ] Implement date range filtering
- [ ] Create monthly/yearly summary reports
- [ ] Export data to CSV format
- [ ] Add search functionality

### Medium-term Features:
- [ ] Multiple budget categories
- [ ] Recurring transactions (monthly bills)
- [ ] Budget alerts via email
- [ ] Data visualization with charts
- [ ] Multi-currency support

### Advanced Improvements:
- [ ] User authentication system
- [ ] MySQL database integration
- [ ] RESTful API for mobile apps
- [ ] Admin dashboard
- [ ] Collaborative budgets (family sharing)

---

## 🎯 Quick Start Guide
```bash
# 1. Clone or download the project
git clone https://github.com/Christine-June/budget-tracker-ai.git
cd budget-tracker-ai

# 2. Start PHP server
php -S localhost:8000

# 3. Open browser
# Navigate to: http://localhost:8000

# 4. Start budgeting!
# - Set your monthly budget
# - Add income and expenses
# - Track your progress
```

---

## 📸 Screenshots

### Initial Dashboard
![Clean interface with $0.00 balance and prompt to set budget]

### Budget Progress
![Green progress bar showing 45% of $3000 budget used]

### Transaction History
![List of income (green) and expense (red) transactions with dates]

### Over Budget Warning
![Red progress bar at 105% with negative remaining balance]

---

## 📝 License

This project is created for educational purposes as part of the Moringa School AI Capstone Project.

Feel free to:
- ✅ Use it for learning
- ✅ Modify and improve it
- ✅ Share it with others
- ✅ Include it in your portfolio

---

## 🙏 Acknowledgments

- **Moringa School** for the capstone project framework
- **Claude AI** for guidance and problem-solving support
- **PHP Community** for excellent documentation
- **Open Source Community** for inspiration

---

## 📧 Contact

**Project Repository:** https://github.com/Christine-June/budget-tracker-ai

For questions or feedback about this project, open an issue on GitHub.

---

**Built by CHRISTINE MWORIA using PHP and AI-powered learning**

*Last Updated: November 27, 2025*