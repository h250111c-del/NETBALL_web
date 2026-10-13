# Deploying to Render

The contact form uses the PHP handler and MySQL database, so deploy this project as a **Docker Web Service**, not as a static site.

1. Create a MySQL database and run [`sql/schema.sql`](./sql/schema.sql).
2. Push the project to GitHub and create a Render Blueprint using [`render.yaml`](./render.yaml).
3. In the Render service's environment settings, set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` to the database connection values. Set `DB_PORT` if the database uses a port other than `3306`.
4. Deploy the service. The contact form posts to `process_form.php`; successful submissions are stored in the `submissions` table.

Keep database credentials in Render's environment settings. Do not commit them to the repository. If the database is unreachable or the environment variables are missing, the form displays an error instead of reporting a false success.
