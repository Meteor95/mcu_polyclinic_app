#/bin/bash

# Change the ownership some of the files
######################################
CURRENT_USER=$(eval "whoami")
CURRENT_GROUP=$(eval "id -gn")

# Ensure necessary directories exist
######################################
sudo mkdir -p /var/www/html/storage/logs
sudo mkdir -p /var/www/html/storage/app/public/file_surat_pengantar
sudo mkdir -p /var/www/html/storage/app/public/mcu/foto_peserta
sudo mkdir -p /var/www/html/storage/app/public/mcu/poliklinik/audiometri
sudo mkdir -p /var/www/html/storage/app/public/mcu/poliklinik/ekg
sudo mkdir -p /var/www/html/storage/app/public/mcu/poliklinik/ronsen
sudo mkdir -p /var/www/html/storage/app/public/mcu/poliklinik/spirometri
sudo mkdir -p /var/www/html/storage/app/public/mcu/poliklinik/threadmill
sudo mkdir -p /var/www/html/storage/app/public/user/ttd
# Berikan izin akses yang sesuai
sudo chown -R nobody:nogroup /var/www/html/storage
sudo chmod -R 775 /var/www/html/storage
# Source code location
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/app
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/bootstrap
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/config
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/public
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/resources
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/resources/lang
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/routes

# Database migrations
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/database/migrations
sudo chown -R $CURRENT_USER:$CURRENT_GROUP source/database/factories

# Ensure necessary permissions
######################################
sudo chmod -R 775 source/storage
sudo chmod -R 775 source/app
sudo chown -R nobody:nogroup source/storage
sudo chown -R nobody:nogroup source/app

# Pull from the repository
######################################
sudo chown -R $CURRENT_USER:$CURRENT_GROUP .git
eval $(ssh-agent)
ssh-add /home/veldora/.ssh/github_id_rsa_meteor95
git pull

######################################
# Build Image
######################################
# Show all command and variable value
set -x
# Load configuration from .env file
set -o allexport

# If .env not exist then use format.env
if [ -f deploy.env ]; then
	source deploy.env
	export $(grep -v '^#' deploy.env | xargs)
else
	echo "Please populate the deploy.env file from deploy.env.format"
	exit
fi
set +o allexport
# Hide all command and variable value again
set +x
# Build image from Docker file with var $IMAGE_REPO_NAME and tag $IMAGE_TAG
# You can see it from .env configuration
sudo docker stack remove artha_medica
sudo docker build --platform=linux/amd64 --pull --rm -f "$DOCKER_FILE" -t $IMAGE_REPO_NAME:$IMAGE_TAG "."
# Deploy to swarm
sudo docker stack deploy --compose-file docker-compose.yaml $DOCKER_SWARM_STACK_NAME --with-registry-auth --detach=false
if [ $? -eq 0 ]; then
	sudo docker image prune -a -f
	echo "Deployment completed successfully. Have a nice day!"
	sudo docker stack ps $DOCKER_SWARM_STACK_NAME
else
	echo "Deployment failed. Please check the logs."
fi