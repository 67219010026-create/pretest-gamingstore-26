# Gaming Gear Store

Welcome to the Gaming Gear Store project! This is a simple e-commerce application built with **PHP**, **MySQL**, and **Docker**.

## Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop) installed on your machine.
- [Git](https://git-scm.com/) (optional, for cloning the repository).

## Getting Started

Follow these steps to get the application up and running:

1.  **Open a terminal** and navigate to the project directory.

2.  **Start the containers**:
    Run the following command to build and start the Docker containers:
    ```bash
    docker-compose up -d --build
    ```

3.  **Wait for initialization**:
    The first time you run this, it might take a few moments for MySQL to initialize and import the database schema from `database.sql`.

4.  **Access the application**:
    Open your web browser and go to:
    [http://localhost:8080](http://localhost:8080)

## Project Structure

-   `index.php`: The main landing page displaying products.
-   `db.php`: Database connection configuration.
-   `database.sql`: SQL script to initialize the database schema and seed data.
-   `Dockerfile`: Configuration for the PHP/Apache image.
-   `docker-compose.yml`: Defines the services (web and db) for the application.

## Troubleshooting

-   **Database Connection Error**: If you see a connection error immediately after starting, wait a few seconds and refresh. The database might still be initializing.
-   **Port Conflicts**: If port `8080` is already in use, modify the `ports` section in `docker-compose.yml` (e.g., `"8081:80"`).

## Stopping the Application

To stop the containers, run:
```bash
docker-compose down
```
