# Deploying to Render

This project uses PHP for its contact form, so deploy it as a Docker **Web Service**.

## 1. Prepare the database

Render does not provide a MySQL database for this service. Use a MySQL provider that accepts connections from Render, and make sure its network or IP allowlist permits connections from the deployed service.

Create the `submissions` table by running [`sql/schema.sql`](./sql/schema.sql) against the database you will use. If your provider already created a database for you, run the table creation statements there and set `DB_NAME` to that database's actual name.

## 2. Deploy the service

1. Push this project to a GitHub repository.
2. In Render, choose **New > Blueprint** and connect that repository.
3. Render reads [`render.yaml`](./render.yaml), builds the Docker image, and prompts you for the database environment variables.
4. Enter `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` from your MySQL provider. Keep the password in Render's environment settings, not in the repository. `DB_PORT` defaults to `3306`; change it if your provider uses another port.
5. Apply the Blueprint and wait for the deployment to finish. Render will provide a public `onrender.com` URL.

The site pages can be served without database settings, but the contact form requires a reachable MySQL database and the `submissions` table. Check the Render service logs for database configuration or connection errors if form submissions fail.
