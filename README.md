# Login Script with PHP and SQLite

This repository contains a simple and secure login script implemented in PHP with an SQLite database for user authentication. The script offers features such as:

- User registration: Users can create new accounts by providing a username, email, and password.
- User login: Registered users can log in using their credentials to access their dashboard.
- User dashboard: After logging in, users are redirected to their personalized dashboard where they can view their profile information and submit messages.
- Message submission: Users can submit messages through a form on their dashboard.
- Public messages: All users can view the public messages submitted by other users.
- User messages: Users can view and manage their own messages, including editing and deleting them.

## Requirements

- PHP 7.0 or higher
- SQLite3 extension enabled
- Web server (e.g., Apache, Nginx)
- Python 3.x (for running the database creation script)

## Setup

1. Clone the repository to your local machine.
2. Run the `cr_db.py` script to create the SQLite database file.
3. Configure your web server to serve the PHP files.
4. Access the login page (`index.php`) in your browser and start using the script.

## Usage

1. Navigate to the login page (`index.php`).
2. Enter your username/email and password.
3. Click on the "Login" button.
4. Upon successful authentication, you will be redirected to the dashboard page (`dashboard.php`).

## Security

- Passwords are securely hashed using PHP's built-in `password_hash()` function before storing them in the database.
- User input is sanitized and validated to prevent SQL injection and cross-site scripting (XSS) attacks.
- Sessions are used to manage user authentication and maintain logged-in state.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributions

Contributions are welcome! Feel free to open issues and pull requests to suggest improvements or report bugs.
