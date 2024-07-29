import sqlite3
import os
import hashlib

def add_admin(username, password):
    db_path = os.path.join(os.path.dirname(__file__), 'db', 'pharmacy.db')
    
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    # Hash the password for security
    hashed_password = hashlib.sha256(password.encode()).hexdigest()
    
    # Insert admin user
    cursor.execute('''
    INSERT INTO admin (username, password) VALUES (?, ?)
    ''', (username, hashed_password))
    
    conn.commit()
    conn.close()

if __name__ == '__main__':
    username = 'admin'  # Replace with desired admin username
    password = 'password'  # Replace with desired admin password
    add_admin(username, password)
