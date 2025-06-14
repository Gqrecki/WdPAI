CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    login VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(16) NOT NULL DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS drinks (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    volume VARCHAR(32),
    alcohol_content VARCHAR(32),
    price_range VARCHAR(32),
    description TEXT
);

CREATE TABLE IF NOT EXISTS users_info (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    comments TEXT,
    favorite_drinks TEXT
);

CREATE TABLE IF NOT EXISTS grades (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    drink_id INTEGER REFERENCES drinks(id) ON DELETE CASCADE,
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    comment TEXT
);

CREATE TABLE IF NOT EXISTS favorite_drinks (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    drink_id INTEGER REFERENCES drinks(id) ON DELETE CASCADE,
    UNIQUE(user_id, drink_id)
);

-- Domyślny admin admin/admin123
INSERT INTO users (login, password, role) VALUES (
    'admin',
    '$2y$10$Qe6QwQwQwQwQwQwQwQwQwOQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw', -- wygeneruj hash w PHP
    'admin'
) ON CONFLICT DO NOTHING;

CREATE OR REPLACE FUNCTION avg_drink_rating(drinkid INT)
RETURNS NUMERIC AS $$
    SELECT AVG(rating)::numeric(10,2) FROM grades WHERE drink_id = drinkid;
$$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION create_users_info()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO users_info (user_id) VALUES (NEW.id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS after_user_insert ON users;
CREATE TRIGGER after_user_insert
AFTER INSERT ON users
FOR EACH ROW
EXECUTE FUNCTION create_users_info();

CREATE OR REPLACE FUNCTION cleanup_after_drink_delete()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM grades WHERE drink_id = OLD.id;
    UPDATE users_info
    SET favorite_drinks = regexp_replace(
        COALESCE(favorite_drinks, ''),
        '(^|,)\s*' || OLD.id || '\s*(,|$)',
        CASE
            WHEN substring(favorite_drinks from '(^|,)\s*' || OLD.id || '\s*(,|$)') = ',' THEN ','
            ELSE ''
        END,
        'g'
    )
    WHERE favorite_drinks IS NOT NULL AND favorite_drinks ~ ('(^|,)\s*' || OLD.id || '\s*(,|$)');
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS after_drink_delete ON drinks;
CREATE TRIGGER after_drink_delete
AFTER DELETE ON drinks
FOR EACH ROW
EXECUTE FUNCTION cleanup_after_drink_delete();
