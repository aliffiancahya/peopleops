# Scope and known limitations

PeopleOps is built for **local portfolio demonstrations** and technical interviews, not for production HR or payroll processing.

- **Demo accounts:** two seeded logins share a documented password. The demo seeder refuses to run outside `local` and `testing`. Remove these accounts, rotate credentials, configure email and set `APP_DEBUG=false` before any private deployment.
- **Legal/payroll:** IDR salary amounts are illustrative. Tax, BPJS contributions, overtime, bank transfers, audit compliance, labor-law leave accrual and statutory rules are deliberately out of scope.
- **Concurrency:** monthly payslip and attendance uniqueness use application validation/transactions rather than an unconditional database constraint. For a multi-user production system, add an appropriate uniqueness strategy accounting for soft deletes and test concurrent requests.
- **Audit/history:** archival uses soft deletes but there is no immutable HR audit log, HR action history or employee self-service record export.
- **Email:** Laravel's log mailer is the development default; password reset links go to `storage/logs/laravel.log` unless SMTP is configured.
- **Permissions:** HR is a simple `role.title === 'HR'` privilege, and employee data views are owner-scoped. Organizations with multiple approval tiers should adopt policies and more granular permissions.
- **Attendance:** local server time is used for clock-in/out; there is no GPS verification, holiday calendar, shift scheduling or overtime computation.
- **Local verification:** PHP syntax/static file checks can run without dependencies. Actual Laravel feature tests require Composer packages and a PHP runtime with SQLite PDO, mbstring and DOM/XML extensions. The included GitHub Actions workflow runs those tests in a compatible environment.
