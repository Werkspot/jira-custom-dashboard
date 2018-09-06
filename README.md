## Installation

##### 1. Install docker and docker-compose:

  - Mac: Download the installer from [Docker](https://store.docker.com/editions/community/docker-ce-desktop-mac) 
  - Linux:

        sudo apt update && sudo apt install docker docker-compose

    - To make docker run without sudo execute:

        - Create the docker group if it doesn't already exist:
    
            `sudo groupadd docker`
        
        - Add your user to the docker group:
    
            `sudo usermod -aG docker $USER`
        
        - Logout and login again    

##### 2. Setup environment parameters:
  - Copy the default docker-compose configuration and give custom values to the variables in the file.
  
        cp ./docker-compose.override.yml.dist ./docker-compose.override.yml   
    
  - Update the `.env` file and replace the placeholders of the database settings with the right values:
    
        DATABASE_URL=mysql://db_user:db_password@mysql:3306/db_name
    
    Note that the host is `mysql`, which matches the container network alias.
    
##### 3. Create the images and start the containers by:
    
        docker-compose up -d
        
##### 4. Create the database schema:        
        
        docker-compose run php bin/console doctrine:schema:create
        

## Run the tests

- Create a local phpunit configuration and update the env variables:

        cp phpunit.xml.dist phpunit.xml
        
- Run all the tests (with coverage):

        docker-compose run php vendor/bin/phpunit --coverage-html var/coverage tests/Werkspot/JiraDashboard

## Run the webapp

Load [http://localhost](http://localhost) on your browser
