# NorthWest Construction Control

PHP + MySQL clone of a construction lending field-services platform: public marketing site, lead capture, ZIP coverage check, and portals for **admin**, **lender**, and **inspector**.

## Features

- Public website (services, solutions, company, blog, contact)
- Contact form and inspector/career applications saved to MySQL
- User login with roles: admin, staff, lender, inspector
- Admin: leads, companies, users, projects, inspections, QC reports, invoices, coverage ZIPs, blog, messages
- Lender: projects, inspection requests, released reports, invoices
- Inspector: assigned jobs, report + photo submit, profile
- Workflow: request → assign → inspect → QC → release → invoice

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + PHP 8+ + MySQL/MariaDB)
- Browser

## Install

1. Copy this project into XAMPP:

   `htdocs/trynorthwest`

2. Start **Apache** and **MySQL** in the XAMPP control panel.

3. Open:

   `http://localhost/trynorthwest/setup.php`

   Click **Create database & seed**. This creates the `nwcc` database and tables.

4. Open the site:

   `http://localhost/trynorthwest/`

5. Log in:

   `http://localhost/trynorthwest/login.php`

Starter accounts are created by setup (admin, staff, lender, inspector). Change those passwords after first login.

## Database

Default connection is in `config/database.php`:

| Setting | Value |
|---|---|
| Host | `127.0.0.1` |
| Database | `nwcc` |
| User | `root` |
| Password | *(empty on default XAMPP)* |

Schema: `sql/schema.sql`

## Project structure

```
trynorthwest/
├── index.html              Public homepage
├── *.php                   Public pages
├── login.php / logout.php
├── setup.php               One-time database installer
├── superform.php           Contact form handler
├── admin/                  Admin / staff portal
├── lender/                 Lender portal
├── inspector/              Inspector portal
├── api/                    Public form endpoints
├── config/database.php
├── includes/               Shared PHP (DB, auth, layout)
├── portal/                 Portal CSS and chrome
├── sql/schema.sql
├── stylesheets/ javascripts/ images/
└── uploads/                Inspection photos
```

## Typical flow

1. Lender creates a project and requests an inspection.
2. Admin assigns an inspector.
3. Inspector submits a report and photos.
4. Staff reviews QC, then releases the report.
5. Lender sees the report; an invoice is created.

## License

Project code is provided for local development and learning. Branding, copy, and media related to NorthWest Construction Control remain the property of their owners.
