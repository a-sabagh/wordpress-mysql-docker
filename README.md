# WordPress + MySQL with Docker Compose

Spin up a local WordPress stack (WordPress, MySQL, phpMyAdmin) with Docker.  
Services, ports, env vars, volumes, and healthchecks are all pre-wired in `compose.yml`.

## What’s inside

- **MySQL** (`mysql:latest`) with a healthcheck and a named volume for data persistence.
- **phpMyAdmin** (`phpmyadmin:latest`) bound to port **8081**.
- **WordPress** (`wordpress:latest`) bound to port **8080**, with your local `./src/` mounted into the container.

## Prerequisites

- Docker & Docker Compose v2+
- A `.env` file in the repo root (see below)

## Quick start

```bash
# 1) clone & enter the repo
git clone https://github.com/a-sabagh/wordpress-mysql-docker.git
cd wordpress-mysql-docker

# 2) create your .env file
printf "ROOT_PASSWORD=changeme\nDB_NAME=wordpress\n" > .env

# 3) bring everything up
docker compose up -d

# 4) visit the apps
# WordPress:   http://localhost:8080
# phpMyAdmin:  http://localhost:8081
```

> Default DB connection used by WordPress:
> - host: `mysql`
> - user: `root`
> - password: `ROOT_PASSWORD` from `.env`
> - database: `DB_NAME` from `.env`

## Configuration

Create a `.env` file next to `compose.yml`:

```ini
# .env
ROOT_PASSWORD=changeme        # MySQL root password (required)
DB_NAME=wordpress             # The database WordPress will use (required)
```

You can also change host ports or volume paths by editing `compose.yml`. Key bits:  
- WordPress maps `./src/:/var/www/html` so you can develop themes/plugins in `./src`.  
- MySQL data is persisted in the named volume `gnutecshop_dbv`.

## Common commands

```bash
# See logs for all services
docker compose logs -f

# Rebuild/recreate after changes
docker compose up -d --force-recreate

# Stop and remove containers (keep DB volume)
docker compose down

# Stop and remove containers + DB volume (starts fresh DB next time)
docker compose down -v

# Shell into a container (example: WordPress)
docker compose exec wordpress bash
```

## Back up & restore the database

```bash
# Backup (writes dump.sql into your current directory)
docker compose exec mysql   sh -c 'exec mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' > dump.sql

# Restore (from dump.sql in current directory)
docker compose exec -T mysql   sh -c 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' < dump.sql
```

## Service details

- **MySQL**
  - Image: `mysql:latest`
  - Port: `3306:3306`
  - Healthcheck: `mysql --version`
  - Volume: `gnutecshop_dbv:/var/lib/mysql`
  - Container name: `gnutec_wpdb`

- **phpMyAdmin**
  - Image: `phpmyadmin:latest`
  - Port: `8081:80`
  - Env: `PMA_HOST=mysql`, `PMA_PORT=3306`
  - Container name: `phpmyadmin`

- **WordPress**
  - Image: `wordpress:latest`
  - Port: `8080:80`
  - Env: `WORDPRESS_DB_HOST=mysql`, `WORDPRESS_DB_USER=root`, `WORDPRESS_DB_PASSWORD=${ROOT_PASSWORD}`, `WORDPRESS_DB_NAME=${DB_NAME}`, `WORDPRESS_TABLE_PREFIX=wp_`
  - Volume: `./src/:/var/www/html`
  - Container name: `gnutec_wordpress`

_All services are on the `gnutecshop` network and use `restart: on-failure:3` with health-gated dependencies._

## Troubleshooting

- **“Error establishing a database connection” in WordPress**
  - Ensure `.env` exists and the `ROOT_PASSWORD`/`DB_NAME` match the MySQL env vars.
  - Wait for the MySQL healthcheck to pass; WordPress and phpMyAdmin depend on it.
- **File changes not appearing**
  - Your code should live in `./src/` (mounted to `/var/www/html`). Check permissions and container logs.
- **Start from scratch**
  - `docker compose down -v` to remove containers and the DB volume, then `docker compose up -d`.

## Repository

- GitHub: [a-sabagh/wordpress-mysql-docker](https://github.com/a-sabagh/wordpress-mysql-docker)

---

**License**: (add one if you’d like—there isn’t one in the repo yet.)
