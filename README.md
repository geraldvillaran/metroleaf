Build
```bash
cd /path/to/leaflet-basic
docker build -t leaflet-basic .
```
Run
```bash
docker run -d -p 8080:80 leaflet-basic
```

This command runs the Docker container in detached mode (-d) and maps port 8080 on your local machine to port 80 on the container, allowing you to access your PHP application by visiting `http://localhost:8080/laguna.php` in your browser.