# Presenting PeopleOps in a portfolio

## Suggested project card

**PeopleOps | HR Management Dashboard** — A responsive Laravel 11 HR portal that separates HR administration from employee self-service. Built six-month KPI charts, employee/department/role management, attendance clock-in/out, request-and-approval flows for leave, assigned tasks, and printable payroll. Added controller-level ownership checks, protected routes, reusable Blade forms, deterministic demo data, and a Docker setup for reviewers.

**Tech:** PHP · Laravel 11 · Blade · SQLite · Bootstrap 5 · Chart.js · Docker · Pest

**My contribution:** Extend and redesign the provided HR-app foundation, secure previously unguarded workflows, connect dashboard statistics to real records, and package an end-to-end demo with seed data and automated tests. Do not describe the Mazer template or Laravel scaffold as originally authored components.

## Screenshots to capture after running locally

- Desktop HR overview (four metric cards and two trend charts).
- Employee directory with searching/filtering and a create/edit form.
- Leave request list, approval, and resulting attendance view.
- A *fictional* employee's print-preview payslip.
- Employee-only view showing assigned tasks and attendance.
- Responsive mobile navigation, if desired.

## Suggested live-demo sequence

Sign in as HR, create a staff member and their portal login, assign a task and salary record, then approve a leave request. Sign out, sign in as Alex, show restricted personal records and complete a check-in and task-status update. Emphasize that payroll and permissions are always validated on the server.

## Interview talking points

- Modeling one-to-one portal accounts and one-to-many payroll, attendance, tasks and leave records.
- Splitting administrative controllers/routes from employee self-service.
- Transactional leave approvals that add and remove *only* their generated attendance rows.
- The difference between a client salary preview and server-validated payroll calculation.
- Reproducible local demos (Docker, SQLite, seeded accounts) and continuous integration.
- Sensible next steps: payroll tax modules, public deployment hardening, database uniqueness under concurrency, email notifications and audit logs.
