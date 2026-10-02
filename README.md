# PeopleOps

A Laravel HR workspace with separate administrator and employee experiences. PeopleOps combines employee records, daily attendance, leave approvals, tasks, and payroll in one responsive dashboard, built on the [Mazer](https://github.com/zuramai/mazer) admin UI. Its six-month dashboard charts use **live database records**, not hardcoded metrics.

## Features

| Area              | HR administrator                                                                         | Employee                                           |
| ----------------- | ---------------------------------------------------------------------------------------- | -------------------------------------------------- |
| Dashboard         | Organization-wide headcount, attendance and salary trends, tasks, pending leave          | Own attendance, payslips, tasks and leave requests |
| People            | Create, search, edit and archive employees assign roles/departments create portal logins | View/update own account                            |
| Roles/departments | Manage roles and departments with deletion safeguards                                    | No access                                          |
| Attendance        | Add, correct and archive manual entries view team history                                | Clock in and out once per day see only own history |
| Leave             | Submit, review, approve, reject, edit and archive requests                               | Submit requests and track own approvals            |
| Tasks             | Create, assign, edit and archive work see all work                                       | See assigned work and update its status            |
| Payroll           | Create and edit monthly payslips automatic net pay, monthly duplication guard            | See and print only own payslips                    |

### Stack

PHP 8.2+, Laravel 11, Blade, SQLite / mySQL, Bootstrap 5 / Mazer, Bootstrap Icons and Chart.js. Authentication is powered by Laravel Breeze backend validation and role-based route middleware enforce access rules. Assets are bundled locally for a demo that does not depend on a JavaScript CDN or an npm build.
