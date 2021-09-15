# soccer
soccer application for API

git clone https://github.com/keyur-lakhalani/soccer.git soccer-prod

cd soccer-prod

composer install

cretea a database 

copy .env.example file to .env file

make necessary changes in .env file 
 - app_url
 - database config
 - TEAM_LOGO_PATH
 - TEAM_LOGO_URL
 - TEAM_PLAYER_IMAGE_PATH
 - TEAM_PLAYER_IMAGE_URL

php aritsan migrate

php artisan db:seed

//optional if you have store the team logo and player image in storage/app/public then you need to create symbolic link

php artisan storage:link 

//--port is optional if you want to run this application other than por 8080 then you can specify the port

php artisan serve --port=8085

//default admin user

admin@socerlocal.com/Admin12!@

API endpoints

1] POST - http://soccer.local/api/login?email=admin@socerlocal.com&password=Admin12!@

2] GET - http://soccer.local/api/team

3] GET - http://soccer.local/api/team-player/{team-id/team-name}

4] GET - http://soccer.local/api/team-player/info/{player-id/player-name}

5] POST - http://soccer.local/api/team

6] POST - http://soccer.local/api/team/{team_id}

7] DELETE - http://soccer.local/api/team/{team_id}

8] POST - http://soccer.local/api/team-player

9] POST - http://soccer.local/api/team-player/{player_id}

10] DELETE - http://soccer.local/api/team-player/{player_id}


TO DO 
- Unit testing
- Swagger
