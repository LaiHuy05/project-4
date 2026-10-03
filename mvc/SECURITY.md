# Migration and security notes (PR #2, depends on PR #1)

Before deployment, **back up your database** and apply
`migrations/2026-10-03_expand_password_column.sql` to expand
`account.tk_password` to VARCHAR(255). Deploying bcrypt writes before this
migration may truncate hashes and lock users out. Configure DB_HOST, DB_NAME,
DB_USER, and DB_PASSWORD through server environment settings.

## Implemented in this branch

- Prepared statements for mutable account, product/variant, category, cart,
  comment, and order queries; single request-scoped PDO connection.
- Bcrypt for all new passwords, verified login against one account record,
  temporary plaintext-to-bcrypt upgrade on successful legacy login.
- Database-verified admin authorization; CSRF protection on login/registration,
  checkout, cart mutations, comments, and order acknowledgements.
- Cart changes and checkout operate on the authenticated user's own records.
- Checkout recalculates totals from product rows, validates quantities,
  and writes order, line items and cart cleanup in one transaction.
- Profiles display the current user's orders; order acknowledgement checks
  owner and fulfillment status, not a user-supplied status string.

## Remaining production-readiness work

- Legacy admin add/edit/delete form actions still have GET mutations and no
  general CSRF middleware. Protect every admin form and convert GET deletion
  links to POST before public deployment.
- Decrement/check inventory atomically and add idempotent payment confirmation
  and real payment-gateway verification where appropriate.
- Review all remaining PHP template output escaping and validate image uploads
  (size, MIME, random filename, storage restrictions).
- Add database-level uniqueness for account username/email after cleaning
  duplicates, then remove plaintext-password fallback once all accounts
  have migrated or reset passwords.
- Run full MySQL integration and browser tests, including rollback tests,
  permissions, cart mutation, login with legacy passwords, and checkout.

Lint and standalone smoke tests are insufficient to establish production safety.

Dashboard revenue calculations now use one grouped SQL query cached for
the duration of the HTTP request, rather than twelve separate month queries.
The old `thang_1()` through `thang_12()` function names and array outputs
remain compatible with existing templates. The order report uses the current
calendar year rather than a hardcoded 2024 filter.
