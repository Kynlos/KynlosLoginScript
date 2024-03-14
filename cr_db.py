import sqlite3

# Connect to the database (create a new file if it doesn't exist)
conn = sqlite3.connect('users.db')
c = conn.cursor()

# Create the users table
c.execute('''CREATE TABLE IF NOT EXISTS users
             (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, email TEXT NOT NULL UNIQUE, password TEXT NOT NULL, is_admin INTEGER DEFAULT 0)''')

# Create the password_resets table
c.execute('''CREATE TABLE IF NOT EXISTS password_resets
             (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, token TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(user_id) REFERENCES users(id))''')

# Commit the changes and close the connection
conn.commit()
conn.close()
print("Database created successfully.")
