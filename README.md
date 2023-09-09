# HOW PROJECT IS SET UP FROM SCRATCH

NOTE: set up using PGSQL. Select when setting up sail, and may need to change the .env to use pgsql instead of mysql

## Base project install 

```sh
# Update and install php + exts and composer. If any php extension is required just add php-$EXTENSIONNAME
sudo apt update
sudo apt upgrade

sudo apt install -y lsb-release gnupg2 ca-certificates apt-transport-https software-properties-common
sudo add-apt-repository ppa:ondrej/php # Gets you latest php (8.2 at time of writing)
php --version

sudo apt install php php-xml php-curl php-pear php-dev composer
sudo pecl install xdebug

# Setup new laravel project
cd ~/.src/
# With Composer: composer create-project laravel/laravel multitenancy
# OR with laravel installer
composer global require laravel/installer
laravel new multitenancy

# Run project localy
cd multitenancy/
composer update
php artisan key:generate
php artisan serve
```

## Set up docker and laravel sail

Remove existing installs if they exist, then install through official apt repo
If you have any issues see: https://docs.docker.com/engine/install

### Docker
```sh
# Remove existing docker installs, then set up fresh latest official docker install
for pkg in docker.io docker-doc docker-compose podman-docker containerd runc; do sudo apt-get remove $pkg; done

sudo apt update
sudo apt install ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings

# Add official source
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo   "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
"$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" |   sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Update and install
sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# OPTIONAL: Run official hello world container to see if docker works
sudo docker run hello-world

# Set up docker group and add your user so you don't need sudo to run docker
sudo groupadd docker
sudo usermod -aG docker $USER
newgrp docker
sudo chmod 666 /var/run/docker.sock

# OPTIONAL: Run official hello world container WITHOUT SUDO to see if docker works without sudo
docker run hello-world
```

### Sail

Read: https://laravel.com/docs/master/sail

```sh
# Make sure you are in your app dir
# cd ~/.src/multitenancy
composer require laravel/sail --dev
php artisan sail:install # Select pgsql,redis,meilisearch,mailpit,selenium,minio and deselect mysql
# If you need to add mysql back later or something, use `php artisan sail:add`

# Set alias so you can use `sail up` instead of `./vendor/bin/sail up`
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'

# Check if anything is using port 80
sudo lsof -i:80
# If apache or nginx is using port 80, disable it
sudo systemctl stop apache2
sudo systemctl stop nginx

# Start your app in sail
sail up

```
