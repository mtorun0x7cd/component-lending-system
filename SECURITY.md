# Security Policy

## Status

This repository is an archived academic project (TH Köln, Systementwurfs-Praktikum
(SYP), Winter 2019/20), retained for reference and portfolio purposes. It is **not under
active development** and receives no functional or security updates.

## Not for production use

The application predates any security review and was written as a student
exercise. It must not be deployed on an untrusted network or operated against
real data. The committed database credentials in `src/config.php` are
placeholders (`<db-host>`, `<db-user>`, `<db-password>`), not live secrets, and
no other secrets are present in the repository.

## Known Limitations

The source as written carries vulnerability classes typical of a 2019 PHP web
project. They are recorded here for transparency, not as a maintenance backlog:

- **SQL injection.** Database access uses the procedural `mysqli` API with
  queries assembled by string interpolation of request data; there are no
  prepared statements or escaping anywhere. The login query
  (`einloggen.php`) is reachable pre-authentication and runs against the
  credential table.
- **Broken access control.** Privilege checks are duplicated per page rather
  than centralized. On several administrative pages the state-changing query
  executes before the privilege gate is evaluated, and the gate is skipped
  entirely for a request that carries no session; there is no CSRF protection
  on state-changing forms.
- **Cross-site scripting.** Database, session, and error values are emitted into
  HTML without output encoding (`htmlspecialchars` is applied only to form
  action attributes), allowing stored and reflected XSS.
- **Credential handling.** Passwords are hashed with bcrypt
  (`password_hash` / `password_verify`), but on successful registration the
  cleartext password is echoed back into the response.
- **Information disclosure.** A failed database connection prints the driver
  error to the client, and verbose SQL-failure messages are surfaced
  throughout.
- **Unimplemented flows.** The password-reset pages are stubs; the default
  `admin` account ships with the password `admin` and must be changed on first
  use.

For a modern equivalent, use parameterized queries (prepared statements or PDO),
centralized authentication and authorization middleware, CSRF tokens,
context-aware output encoding, and externalized secrets.

## Reporting

To report a substantive issue worth recording, contact info@mtorun0x7cd.com.
Given the archived status of the project, a fix or response is not guaranteed.
