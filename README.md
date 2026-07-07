# Tech Career Academy — Deployment Guide (Hostinger Business Hosting)

## What's included
- **Public site:** landing page, course browsing, login/register
- **Student dashboard:** enrolled courses, live sessions (Teams), recorded sessions
- **Admin panel:** manage courses, schedule live sessions, upload recordings, view students
- **Database:** MySQL schema in `database/schema.sql`

## Step-by-step setup

### 1. Create the database
1. Log into **hPanel** → **Databases** → **MySQL Databases**
2. Create a new database and a database user, note down:
   - Database name (e.g. `u123456789_tca_db`)
   - Database username
   - Database password
   - Host (usually `localhost`)
3. Go to **phpMyAdmin** (linked from the same page), select your new database,
   click **Import**, and upload `database/schema.sql`

### 2. Upload the files
1. In hPanel, go to **Files** → **File Manager**
2. Navigate to `public_html` (or your domain's folder if using a subdomain/addon domain)
3. Upload and extract all files from this project into that folder
4. Confirm `index.php` ends up directly inside `public_html` (not in a subfolder)

### 3. Configure the database connection
1. Edit `config/db.php` (via File Manager's code editor, or download/edit/re-upload)
2. Replace the placeholder values with your real DB name, username, and password from Step 1

### 4. Create your admin account
1. Visit `https://yourdomain.com/setup.php` in your browser
2. Fill in your name, email, and a strong password — this becomes your admin login
3. **Delete `setup.php` from the server immediately after** (via File Manager) —
   it disables itself after one admin is created, but it's best removed entirely

### 5. Raise upload limits for video recordings
See `uploads-config.md` for how to increase PHP's upload size limit in hPanel
(needed before uploading any videos larger than a few MB).

### 6. Add your logo
Replace the "TC" text logo in `includes/header.php`, `includes/footer.php`,
`admin/includes/admin-header.php`, and the auth pages with your actual logo image.
Upload your logo file to `assets/images/` and swap:
```html
<span class="logo-mark">TC</span>
```
for:
```html
<img src="/assets/images/your-logo.png" alt="Tech Career Academy" style="height:34px;">
```

### 7. Update the WhatsApp number
In `includes/footer.php`, find:
```
https://wa.me/10000000000?text=...
```
Replace `10000000000` with your real WhatsApp number in international format
(no `+` or spaces, e.g. `9611234567`).

### 8. Start adding content
Log in at `/login.php` with your admin account, then from the admin panel:
1. **Courses** → add courses under each category (IT Support, Networking, Basic Computer Skills)
2. **Live Sessions** → schedule sessions with Microsoft Teams links
3. **Recordings** → upload recorded session videos

Students can now register at `/register.php`, browse and enroll in courses,
and access their dashboard for live sessions and recordings.

## Security notes
- `config/.htaccess` and `uploads/.htaccess` block direct access to database
  credentials and raw video files — don't remove these.
- Videos are served through `stream.php`, which checks that a student is
  logged in and enrolled before streaming — direct video links won't work
  for non-enrolled users.
- Change your admin password periodically via a future "account settings"
  feature, or directly in the database if needed.

## Folder structure
```
/
├── index.php, login.php, register.php, dashboard.php, courses.php, course.php
├── live-sessions.php, recordings.php, watch.php, stream.php, logout.php, setup.php
├── config/           → db.php, auth.php (protected from direct access)
├── includes/         → header.php, footer.php
├── assets/           → css/, js/, images/
├── admin/            → admin panel pages
├── uploads/recordings/ → video files (protected, served via stream.php)
└── database/schema.sql → import this into phpMyAdmin
```
