# Deployment Instructions for Task Manager

## Prerequisites
- Docker and Docker Compose installed on your host server
- PHP 8.2 or higher with SQLite extension

## Deployment Steps

1. **Prepare the environment**

   Copy the production environment file:
   ```bash
   cp .env.production .env
   ```

   Generate a new application key:
   ```bash
   php artisan key:generate --force
   ```

2. **Run using Docker Compose**

   Build and start the container:
   ```bash
   docker-compose up -d
   ```

3. **Setup the database**

   The database will be automatically initialized when the container starts, but if you need to run migrations manually:
   ```bash
   docker exec task-manager php artisan migrate
   ```

4. **Verify the Deployment**

   Your application should now be accessible at http://localhost:8000

5. **Cloudflare Tunnel Configuration**

   1. Install Cloudflare CLI (cloudflared) on your local machine
   2. Authenticate with Cloudflare: `cloudflared tunnel login`
   3. Create a tunnel: `cloudflared tunnel create task-manager`
   4. Configure the tunnel to point to your Docker container:
      ```bash
      cloudflared tunnel route dns task-manager your-domain.com
      ```
   5. Create a config file (config.yml) for the tunnel:
      ```yaml
      tunnel: <TUNNEL_ID>
      credentials-file: /path/to/credentials.json
      ingress:
        - hostname: your-domain.com
          service: http://localhost:80
        - service: http_status:404
      ```
   6. Run the tunnel: `cloudflared tunnel run task-manager`

6. **Verify the Deployment**

   Your application should now be accessible at your configured domain.
