# Deploying to Render

The contact form uses PHP and SQLite, so deploy this project as a **Docker Web Service**, not as a static site.

1. Push the project to GitHub and create a Render Blueprint using [`render.yaml`](./render.yaml). The Blueprint creates a persistent disk at `/var/data`.
2. Deploy the service. The application creates the SQLite database and `submissions` table automatically at `/var/data/dolphin_netball.sqlite`.
3. For local development, PHP needs the `pdo_sqlite` extension. The database defaults to `data/dolphin_netball.sqlite`; set `SQLITE_PATH` to choose another path.

The persistent disk retains submissions across deployments. Render persistent disks are available on paid web services and attach to a single service instance, so keep the service at one instance when using this SQLite database. If the database cannot be opened or written, the form displays an error instead of reporting a false success.
