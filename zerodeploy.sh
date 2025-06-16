#!/bin/bash

# Deployment configuration
APP_NAME="MaterialsCommons"
BASE_PATH="/var/www"
KEEP_RELEASES=5
REPOSITORY="your-git-repository-url"
BRANCH="main"
OPCACHE_CLEAR_URL="http://localhost/opcache-clear.php"

# Directory structure
CURRENT_RELEASE_DIR="${BASE_PATH}/current"
RELEASES_DIR="${BASE_PATH}/releases"
SHARED_DIR="${BASE_PATH}/shared"
TIMESTAMP=$(date +%Y%m%d%H%M%S)
NEW_RELEASE_DIR="${RELEASES_DIR}/${TIMESTAMP}"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Function to log messages
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
    exit 1
}

warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

# Function to check if command succeeded
check_result() {
    if [ $? -ne 0 ]; then
        error "$1"
    fi
}

# Verify running as appropriate user
if [ "$(whoami)" != "www-data" ]; then
    error "Script must be run as www-data user"
fi

# Create required directories
log "Creating directory structure..."
mkdir -p ${RELEASES_DIR} ${SHARED_DIR}/storage ${SHARED_DIR}/vendor ${SHARED_DIR}/.env
check_result "Failed to create directory structure"

# Clone new release
log "Cloning repository..."
git clone --depth 1 -b ${BRANCH} ${REPOSITORY} ${NEW_RELEASE_DIR}
check_result "Failed to clone repository"

cd ${NEW_RELEASE_DIR}
check_result "Failed to change to release directory"

# Install composer dependencies
log "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
check_result "Composer installation failed"

# Install npm dependencies and build assets
log "Installing NPM dependencies and building assets..."
npm ci
check_result "NPM installation failed"

npm run production
check_result "Asset compilation failed"

# Create symbolic links for shared resources
log "Creating symbolic links..."
ln -nfs ${SHARED_DIR}/.env ${NEW_RELEASE_DIR}/.env
ln -nfs ${SHARED_DIR}/storage ${NEW_RELEASE_DIR}/storage
ln -nfs ${SHARED_DIR}/vendor ${NEW_RELEASE_DIR}/vendor

# Clear caches
log "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run database migrations
log "Running database migrations..."
php artisan migrate --force
check_result "Database migration failed"

# Optimize Laravel
log "Optimizing Laravel..."
php artisan optimize
check_result "Laravel optimization failed"

# Make storage directory writable
chmod -R 775 ${SHARED_DIR}/storage

# Reload PHP-FPM in a non-disruptive way
log "Reloading PHP-FPM..."
sudo -u root systemctl reload php8.0-fpm
check_result "Failed to reload PHP-FPM"

# Switch current symlink to new release
log "Activating new release..."
ln -nfs ${NEW_RELEASE_DIR} ${BASE_PATH}/current_temp
mv -Tf ${BASE_PATH}/current_temp ${CURRENT_RELEASE_DIR}
check_result "Failed to activate new release"

# Clear OPcache
log "Clearing OPcache..."
curl -s -X GET "${OPCACHE_CLEAR_URL}" > /dev/null
check_result "Failed to clear OPcache"

# Queue workers restart (in background)
log "Restarting queue workers..."
sudo -u root systemctl restart laravel-worker
check_result "Failed to restart queue workers"

# Clean up old releases
log "Cleaning up old releases..."
cd ${RELEASES_DIR}
ls -t | tail -n +$((${KEEP_RELEASES} + 1)) | xargs -r rm -rf

# Verify deployment
log "Verifying deployment..."
curl -s -I "http://localhost/health-check" | grep "200 OK" > /dev/null
if [ $? -ne 0 ]; then
    warning "Health check failed, consider rolling back"
fi

log "Deployment completed successfully!"