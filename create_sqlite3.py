import sqlite3
import os

def create_database():
    # Ensure the directory exists
    db_dir = os.path.join(os.path.dirname(__file__), 'pharmacy.db')
    os.makedirs(os.path.dirname(db_dir), exist_ok=True)
    
    conn = sqlite3.connect(db_dir)
    cursor = conn.cursor()
    
    # Create tables
    cursor.execute('''
    CREATE TABLE IF NOT EXISTS medicines (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
        description TEXT,
        price REAL NOT NULL,
        quantity INTEGER NOT NULL
    )
    ''')
    
    cursor.execute('''
    CREATE TABLE IF NOT EXISTS admin (
        id INTEGER PRIMARY KEY,
        username TEXT NOT NULL,
        password TEXT NOT NULL
    )
    ''')
    
    # Insert some sample medicines
    cursor.execute('''
    INSERT INTO medicines (name, description, price, quantity) VALUES
    ('Aspirin', 'Pain reliever', 5.99, 100),
    ('Paracetamol', 'Fever reducer', 3.49, 200),
    ('Ibuprofen', 'Anti-inflammatory', 4.79, 150)
    ''')
    
    conn.commit()
    conn.close()

if __name__ == '__main__':
    create_database()
