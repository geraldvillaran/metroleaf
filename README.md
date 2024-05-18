Build
```bash
cd /path/to/leaflet-basic
docker build -t metroleaf .
```
Run
```bash
docker run -d -p 8080:80 metroleaf
```

This command runs the Docker container in detached mode (-d) and maps port 8080 on your local machine to port 80 on the container, allowing you to access your PHP application by visiting `http://localhost:8080/index.html` in your browser.