# Deploying to Render

This is the simplest setup: deploy the site as a **Static Site**. It does not need a database or environment variables.

1. Push the project to GitHub.
2. In Render, choose **New > Blueprint** and connect the repository.
3. Render reads [`render.yaml`](./render.yaml), publishes the website, and gives you an `onrender.com` URL.

The build publishes only the HTML, CSS, JavaScript, and images. PHP and database files are not deployed. The contact page explains that form submissions are not available yet; the form will need a backend or a form service to receive messages.
