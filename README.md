1. Installation

- Install docker and docker-compose:

        sudo apt update && sudo apt install docker docker-compose

- To make docker run without sudo execute:

    1. Create the docker group if it doesn't already exist:
    
            sudo groupadd docker
        
    2. Add your user to the docker group:
    
            sudo usermod -aG docker $USER
        
    3. Logout and login again    

- Setup environment parameters:

        cp ./docker-compose.override.yml.dist ./docker-compose.override.yml
    
    Give custom values to the variables in the file.
    
- Create the images and start the containers by:
    
        docker-compose up -d
        
- Get dependencies:

        docker-compose run composer install
        
- Create database schema:        
        
        docker-compose run cli bin/console doctrine:schema:create

2. Run tests

        docker-compose run cli vendor/bin/phpunit --coverage-html var/coverage tests/Werkspot/JiraDashboard

3. Run in the browser

    Just browse http://localhost