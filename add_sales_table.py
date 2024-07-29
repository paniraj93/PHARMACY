import sqlite3
import os

def add_sales_table():
    db_path = os.path.join(os.path.dirname(__file__), 'db', 'pharmacy.db')
    
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    # Create sales table
    cursor.execute('''
    CREATE TABLE IF NOT EXISTS sales (
        id INTEGER PRIMARY KEY,
        medicine_id INTEGER NOT NULL,
        quantity_sold INTEGER NOT NULL,
        sale_date DATE NOT NULL,
        FOREIGN KEY(medicine_id) REFERENCES medicines(id)
    )
    ''')
    
    conn.commit()
    conn.close()

if __name__ == '__main__':
    add_sales_table()
