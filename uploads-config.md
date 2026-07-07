# Increasing video upload limits on Hostinger

By default, PHP only allows very small file uploads (often 2–8MB), which
isn't enough for video recordings. Hostinger uses PHP-FPM, so the
`.htaccess` values usually won't take effect — use hPanel instead:

## Steps
1. Log into **hPanel**
2. Go to **Websites** → select your site → **Advanced** → **PHP Configuration**
   (sometimes listed as "PHP Options" or "Configure PHP")
3. Set these values:
   - `upload_max_filesize` → `512M` (or higher, depending on your plan's storage)
   - `post_max_size` → `512M`
   - `max_execution_time` → `600`
   - `max_input_time` → `600`
   - `memory_limit` → `512M`
4. Save changes. Some plans apply this instantly; others take a minute or two.

## Storage limits
Business Web Hosting plans have a storage cap (check hPanel → your plan
details for the exact number). Video files are large, so keep an eye on
usage under **hPanel → Websites → your site → Statistics**. If you expect
to host many hours of video, consider periodically archiving or deleting
older recordings, or upgrading your plan.

## Large uploads timing out
If uploads still fail for very large files even after raising the limits
above, it's likely your connection or the server's execution time cutting
off a long single request. Options:
- Compress videos before uploading (e.g. with HandBrake) to reduce file size
- Upload during off-peak hours
- Split very long sessions into smaller recordings (e.g. one per hour)
