# 💰 PHP Budget Tracker - Beginner's Toolkit

## 1. Title & Objective

**Project:** Getting Started with PHP - A Budget Tracker Web Application

**Technology Chosen:** PHP (Hypertext Preprocessor)

**Why PHP?**
- Easy to learn for beginners
- Runs on almost any server
- Perfect for dynamic web applications
- No complex setup required

**End Goal:** Build a fully functional budget tracker that tracks income and expenses with a clean web interface.

---

## 2. Quick Summary of the Technology

**What is PHP?**
PHP is a server-side scripting language designed for web development. It processes code on the server and sends HTML to the browser.

**Where is it used?**
- WordPress (powers 40% of all websites)
- Facebook (originally built with PHP)
- Wikipedia
- Slack

**Real-world example:** When you log into Facebook, PHP processes your login credentials on the server and decides what content to show you.

---

## 3. System Requirements

- **OS:** Windows, Mac, or Linux
- **PHP Version:** 7.4 or higher (Check with `php -v`)
- **Tools:**
  - Text editor (VS Code, Sublime, Notepad++)
  - Web browser (Chrome, Firefox, Safari)
  - Terminal/Command Prompt

**No database required!** This project uses JSON file storage.

---

## 4. Installation & Setup Instructions

### Step 1: Install PHP

**Windows:**
1. Download from https://windows.php.net/download/
2. Extract to `C:\php`
3. Add `C:\php` to system PATH

**Mac:**
```bash
brew install php
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install php
```

### Step 2: Verify Installation
```bash
php -v
```
You should see something like: `PHP 8.2.0 (cli)`

### Step 3: Create Project
```bash
mkdir php-budget-tracker
cd php-budget-tracker
```

### Step 4: Create Files
- Create `index.php` (main application file)
- PHP will automatically create `budget_data.json` when you run it

### Step 5: Run the Application
```bash
php -S localhost:8000
```

### Step 6: Open in Browser
Navigate to: `http://localhost:8000`

---

## 5. Minimal Working Example

### What This Example Does:
- ✅ Accepts user input for income/expenses
- ✅ Calculates running balance
- ✅ Stores data persistently in JSON format
- ✅ Displays transaction history
- ✅ Color-codes income (green) and expenses (red)

### Code Structure:
```php
<?php
// 1. Start PHP session for user data
session_start();

// 2. Create data file if it doesn't exist
if (!file_exists('budget_data.json')) {
    file_put_contents('budget_data.json', json_encode(['transactions' => []]));
}

// 3. Load existing data from JSON file
$data = json_decode(file_get_contents('budget_data.json'), true);

// 4. Handle new transaction submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction = [
        'id' => uniqid(),
        'description' => $_POST['description'],
        'amount' => floatval($_POST['amount']),
        'type' => $_POST['type'],
        'date' => date('Y-m-d H:i:s')
    ];
    
    // Save to file
    $data['transactions'][] = $transaction;
    file_put_contents('budget_data.json', json_encode($data));
}

// 5. Calculate total balance
$balance = 0;
foreach ($data['transactions'] as $t) {
    $balance += ($t['type'] === 'income') ? $t['amount'] : -$t['amount'];
}
?>
```

### Expected Output:
When you add a transaction like:
- **Description:** "Monthly Salary"
- **Amount:** 5000
- **Type:** Income

The balance updates to **+$5,000.00** and displays in the transaction list with a green indicator.

---

## 6. AI Prompt Journal

### Prompt 1:
**Prompt:** "Give me a step-by-step guide to create a budget tracker in PHP without using a database"

**Link to curriculum:** https://ai.moringaschool.com

**AI Response Summary:**
The AI suggested using JSON file storage instead of a database, which simplified the project significantly. It provided the file handling functions: `file_get_contents()` and `file_put_contents()`.

**Evaluation:** ⭐⭐⭐⭐⭐ Extremely helpful - saved hours of database setup time.

---

### Prompt 2:
**Prompt:** "How do I handle form submissions in PHP and prevent page refresh issues?"

**Link to curriculum:** https://ai.moringaschool.com

**AI Response Summary:**
The AI explained the POST method and the importance of using `header('Location: index.php')` with `exit` to prevent form resubmission on page refresh.

**Evaluation:** ⭐⭐⭐⭐ Very useful - fixed the duplicate submission bug.

---

### Prompt 3:
**Prompt:** "Best practices for styling a PHP application without using external CSS frameworks"

**Link to curriculum:** https://ai.moringaschool.com

**AI Response Summary:**
The AI provided inline CSS with modern gradients and responsive design principles. Suggested using flexbox for layout.

**Evaluation:** ⭐⭐⭐⭐ Helpful - created a professional look quickly.

---

### Prompt 4:
**Prompt:** "How to calculate balance from income and expenses array in PHP?"

**Link to curriculum:** https://ai.moringaschool.com

**AI Response Summary:**
The AI showed how to use a foreach loop with ternary operators to add/subtract based on transaction type.

**Evaluation:** ⭐⭐⭐⭐⭐ Perfect solution - clean and efficient code.

---

## 7. Common Issues & Fixes

### Issue 1: "PHP command not found"
**Error:**
```
bash: php: command not found
```

**Solution:**
PHP is not installed or not in your PATH. Install PHP using the instructions in Section 4, then restart your terminal.

---

### Issue 2: Port 8000 already in use
**Error:**
```
Failed to listen on localhost:8000
```

**Solution:**
Use a different port:
```bash
php -S localhost:8080
```
Then visit `http://localhost:8080`

---

### Issue 3: Permission denied when creating budget_data.json
**Error:**
```
Warning: file_put_contents(budget_data.json): failed to open stream
```

**Solution:**
Make sure you have write permissions in the project folder:
```bash
chmod 755 .
```

---

### Issue 4: Blank page or no output
**Solution:**
Check for PHP syntax errors:
```bash
php -l index.php
```
This will show any syntax errors in your code.

---

### Issue 5: Transactions not saving
**Solution:**
1. Check if `budget_data.json` was created in the same folder
2. Verify the JSON file has write permissions
3. Check browser console for any JavaScript errors (F12)

**Reference:** [Stack Overflow - PHP File Handling](https://stackoverflow.com/questions/tagged/php+file)

---

## 8. References

### Official Documentation
- [PHP Official Manual](https://www.php.net/manual/en/)
- [PHP: Getting Started Tutorial](https://www.php.net/manual/en/tutorial.php)
- [PHP File Handling](https://www.php.net/manual/en/book.filesystem.php)

### Video Tutorials
- [PHP in 100 Seconds](https://www.youtube.com/watch?v=a7_WFUlFS94)
- [PHP for Beginners - Traversy Media](https://www.youtube.com/watch?v=BUCiSSyIGGU)

### Helpful Articles
- [PHP: The Right Way](https://phptherightway.com/)
- [W3Schools PHP Tutorial](https://www.w3schools.com/php/)
- [Learn PHP in Y Minutes](https://learnxinyminutes.com/docs/php/)

### Communities
- [r/PHP on Reddit](https://www.reddit.com/r/PHP/)
- [PHP tag on Stack Overflow](https://stackoverflow.com/questions/tagged/php)

---

## 🚀 Quick Start Commands
```bash
# Clone or create project
mkdir php-budget-tracker
cd php-budget-tracker

# Create index.php (copy code from above)

# Run the application
php -S localhost:8000

# Open browser to
http://localhost:8000
```

---

## 📸 Screenshots

### Home Page
![Budget Tracker showing balance of $0.00 with empty transaction list]

### After Adding Transactions
![Balance showing $3,450.00 with income and expense transactions listed]

---

## 🎯 Learning Outcomes

By completing this project, you learned:
- ✅ PHP syntax and basic programming
- ✅ Handling HTTP POST requests
- ✅ File I/O operations in PHP
- ✅ JSON data storage
- ✅ HTML form processing
- ✅ Session management basics
- ✅ Running a local PHP server

---

## 🔄 Future Improvements

Want to take this further? Try:
- Add delete transaction functionality
- Implement categories (Food, Transport, Entertainment)
- Add date filtering
- Create monthly/yearly reports
- Export data to CSV
- Add user authentication

---

## 📝 License

This project is open source and available for educational purposes.

---

**Built by CHRISTINE MWORIA using PHP and AI-powered learning**