-- Back up the DB and apply before deploying bcrypt.
ALTER TABLE account MODIFY COLUMN tk_password VARCHAR(255) NOT NULL;
