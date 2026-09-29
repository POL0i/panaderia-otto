import sqlite3
import crypt
import datetime

db_path = './docker/mailserver/users.db'
conn = sqlite3.connect(db_path)
cursor = conn.cursor()

domain = 'panaderia-otto.shop'
now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

# Create domain
cursor.execute("SELECT name FROM domains WHERE name=?", (domain,))
if not cursor.fetchone():
    cursor.execute("""
        INSERT INTO domains (name, created, updated, disabled) 
        VALUES (?, ?, ?, 0)
    """, (domain, now, now))
    print(f"Domain {domain} created.")

users = [
    ('produccion', 'Qvu3t0zkpmDfYN+T'),
    ('empleado', 'hY5e3qYBrtFlE/h7'),
    ('dennis', 'A6y3PNfyWT9dkhp/'),
    ('compra', 'EO35Ummfe7/fQYrv'),
    ('admin', 'contraseña')
]

for username, password in users:
    address = f"{username}@{domain}"
    pwd_hash = '{CRYPT}' + crypt.crypt(password, crypt.mksalt(crypt.METHOD_SHA512))
    
    cursor.execute("SELECT address FROM users WHERE address=?", (address,))
    if not cursor.fetchone():
        cursor.execute("""
            INSERT INTO users (
                address, username, password, home, uid, gid, name, 
                disabled, domainAdmin, superAdmin, strictFromDisabled, 
                created, discard, internalOnly, copyToSent, domainName
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        """, (
            address, username, pwd_hash, f"/data/mail/{domain}/{username}", 
            5000, 5000, username, 0, 1 if username == 'admin' else 0, 
            1 if username == 'admin' else 0, 0, now, 0, 0, 1, domain
        ))
        print(f"User {address} created.")

conn.commit()
conn.close()
