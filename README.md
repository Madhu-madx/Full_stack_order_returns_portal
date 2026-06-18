Order Returns System
A web-based order returns management system built with PHP, MySQL, and vanilla CSS. Allows customers to submit return requests, check return status, and lets admins review and process requests through a dashboard.

Project Structure
returns/
├── index.php           # Home page — landing page with navigation to submit or track returns
├── order.php           # Return request form — customer fills in Order ID, reason, and item condition
├── submit_return.php   # Backend logic — validates the request and inserts it into the database
├── status.php          # Status check page — customer looks up their return by Order ID
├── admin.php           # Admin dashboard — view all requests, approve or reject them
├── nav.php             # Shared navigation bar included across all pages
├── footer.php          # Shared footer included across all pages
├── style.css           # Stylesheet for the entire application
├── db.php              # Database connection file
└── setup.sql           # SQL script to create the database, tables, and sample data
How It Works
Customer Flow
Customer visits the home page and clicks Submit a Return
They enter their Order ID, describe the reason, and select the item condition
The system validates:
Whether the Order ID exists in the database
Whether the purchase was made within the last 30 days
Whether the item condition is eligible (damaged items are rejected)
If valid, the return is saved as pending and the customer gets a Return ID
Customer can visit Check Status and enter their Order ID to see the current status
Admin Flow
Admin visits admin.php
Sees a summary of pending, approved, and rejected requests
Can click Approve or Reject on any pending request
Status updates immediately in the database
Pages and What They Do
index.php
The landing page. Has a brief description of the portal and two buttons — one to submit a return and one to check status. Also has a simple numbered explanation of how the process works.

order.php
The return request form. Takes three inputs from the customer: Order ID, reason for return (text area), and item condition (dropdown with unopened / opened / damaged). On successful submission, shows a green success alert with the generated Return ID. On failure, shows a red error message explaining why the request was rejected.

submit_return.php
No visible page — purely backend. Receives the POST data from order.php, runs three validations against the database, and either inserts the record or redirects back to order.php with an error code. Validation logic:

Checks if Order ID exists in the orders table
Calculates the difference between today and the purchase date — rejects if more than 30 days
Rejects if item condition is marked as damaged
status.php
A search page. Customer enters their Order ID and the page queries the return_requests table and displays all associated return requests in a table with Return ID, item name, submission date, condition, and a colour-coded status badge (yellow for pending, green for approved, red for rejected).

admin.php
The admin dashboard. Loads all return requests ordered by status (pending first). Shows three summary cards at the top with counts of pending, approved, and rejected requests. Each pending row has Approve and Reject buttons that trigger a GET request back to the same page, which updates the record and redirects.

nav.php
Shared header included at the top of every page. Contains the portal title and navigation links to Home, Submit Return, Check Status, and Admin.

footer.php
One-line shared footer with copyright text, included at the bottom of every page.

style.css
Single stylesheet for the whole project. Covers layout, navigation bar, content cards, form inputs, tables, status badges, and responsive behaviour for smaller screens.

db.php
Database connection using MySQLi. Connects to localhost with the default XAMPP credentials and selects the returns_db database. Included at the top of any page that needs database access.

setup.sql
Run this once in phpMyAdmin to set everything up. Creates the returns_db database, the orders table (sample orders), and the return_requests table (where submitted returns are stored). Also inserts 5 sample orders for testing.

Database Structure
orders table
Stores the original purchase records that return requests are validated against.

Column	Type	Description
id	INT	Auto-increment primary key
order_id	VARCHAR(20)	Unique order identifier (e.g. ORD001)
customer_name	VARCHAR(100)	Customer full name
item_name	VARCHAR(100)	Name of the purchased item
purchase_date	DATE	Date of original purchase
amount	DECIMAL(10,2)	Order value
return_requests table
Stores all submitted return requests.

Column	Type	Description
id	INT	Auto-increment primary key
order_id	VARCHAR(20)	References the original order
customer_name	VARCHAR(100)	Pulled from orders table at submission
item_name	VARCHAR(100)	Pulled from orders table at submission
purchase_date	DATE	Pulled from orders table at submission
reason	TEXT	Customer-provided return reason
item_condition	ENUM	unopened / opened / damaged
status	ENUM	pending / approved / rejected
submitted_at	TIMESTAMP	Auto-set on insert
reviewed_at	TIMESTAMP	Set when admin takes action
Sample Order IDs for Testing
Order ID	Item	Days Since Purchase	Expected Behaviour
ORD001	Wireless Headphones	5 days	Return accepted
ORD002	Running Shoes	12 days	Return accepted
ORD003	Yoga Mat	2 days	Return accepted
ORD004	Coffee Maker	45 days	Rejected — window expired
ORD005	Bluetooth Speaker	20 days	Return accepted
Setup and Run Instructions
Requirements
XAMPP (Apache + MySQL)
A browser
Steps
Clone or download this repository
Copy the project folder into C:\xampp\htdocs\returns\
Start Apache and MySQL from the XAMPP Control Panel
Open http://localhost/phpmyadmin in your browser
Click the SQL tab, paste the contents of setup.sql, and click Go
Open http://localhost/returns/index.php
Technology Stack
Frontend: HTML5, CSS3 (vanilla, no frameworks)
Backend: PHP 8 (procedural)
Database: MySQL via MySQLi
Server: Apache (XAMPP)
Version Control: Git
Validation Logic
Three checks run on every return submission before it is accepted:

Order exists — the Order ID is looked up in the orders table. If not found, the request is rejected with a "not found" error.
Return window — the number of days between today and the purchase date is calculated. If it exceeds 30 days, the request is rejected with an "expired" error.
Item condition — if the customer selects "damaged", the request is rejected as damaged items are ineligible for returns.
Only requests that pass all three checks are inserted into the database with a status of pending.

AI Tools Used
I used Claude (Anthropic) during development primarily as a coding assistant. It helped me scaffold the initial database schema, suggested the validation logic structure for the return window check, and helped me debug a MySQLi prepared statement issue I ran into with the INSERT query. I wrote and reviewed all the code myself and made decisions about the page structure, flow, and UI design. Claude was useful in the same way Stack Overflow or documentation would be — as a reference when I was stuck, not as a replacement for understanding the code.



























