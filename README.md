# MadTestLab

This project aims at designing and securing a database for a medical laboratory while ensuring compliance with medical data standards.

## Start Docker

To start the Docker containers for this project, run the following command in project folder:

```bash
docker compose up
```

### Optional Parameters

You can use the following optional parameters:

- **Force Rebuild**: Add `--build` to rebuild the Docker images.
  
  ```bash
  docker compose up --build
  ```

- **Run in Background**: Add `-d` to run the containers in detached mode (in the background).
  
  ```bash
  docker compose up -d
  ```

### Server Ports

- The application is accessible on port **8080**.
- phpMyAdmin can be accessed on port **8081**.
- MySQL is accessible on port **3306**.

### Accessing the Application

- **Web Application**: [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin**: [http://localhost:8081](http://localhost:8081)

### Stopping the Services

To stop and remove the containers, run:

```bash
docker compose down
```

### License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
