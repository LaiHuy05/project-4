# Next security stage (on top of the controller-splitting PR)

Back up MySQL and apply `migrations/2026-10-03_expand_password_column.sql`
BEFORE deploying bcrypt passwords. Supply DB_HOST, DB_NAME, DB_USER and
DB_PASSWORD as server environment variables. Ensure MySQL uses InnoDB.

- SQL statements now use placeholders for dynamic CRUD arguments.
- One request shares its PDO connection; `pdo_transaction()` is available.
- Admin routes validate the logged-in user's current database role.
- Login uses one account lookup; new passwords use bcrypt. Plaintext records
  upgrade on their next successful login. **Remove this fallback** after migration.
- Auth forms use CSRF tokens and never echo back a submitted password.

Not yet covered by this security stage: admin GET mutations without CSRF,
cart GET mutations, owner checks for all profiles, inventory enforcement,
atomic inventory enforcement, image-upload sanitization. Do not deploy publicly
based on syntax and smoke tests alone.
\nCheckout now recalculates price from product rows within a single transaction,\nlocks selected cart rows and validates ownership against the session.\nThe remaining work includes inventory decrement and thorough payment smoke tests.\n
Cart add/update/delete routes now require POST+CSRF and check the session owns
both the cart and selected cart-detail IDs. The product and cart templates
submit protected forms instead of GET mutation links.

Profile routes require login and cannot select another account from iduser;
order confirmations check ownership and required status before applying a
fixed transition via POST+CSRF. Comment forms are POST+CSRF as well.
