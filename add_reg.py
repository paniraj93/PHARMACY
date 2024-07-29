import sqlite3

def update_database_schema():
    conn = sqlite3.connect('C:/Users/ASUS/Desktop/pharma_management/db/pharmacy.db')
    c = conn.cursor()
    c.execute("ALTER TABLE medicines ADD COLUMN is_regular INTEGER DEFAULT 1")
    conn.commit()
    conn.close()

update_database_schema()
