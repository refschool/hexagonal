CREATE TABLE IF NOT EXISTS products (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL CHECK (length(trim(name)) > 0),
    price_cents INTEGER NOT NULL CHECK (price_cents > 0)
);

INSERT OR IGNORE INTO products (id, name, price_cents) VALUES
    ('1', 'Clavier mécanique', 7900),
    ('2', 'Souris sans fil', 3900),
    ('3', 'Écran 27 pouces', 24900),
    ('4', 'Webcam HD', 5900);
