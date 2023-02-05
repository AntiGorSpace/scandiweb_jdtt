# preinstall
### development
* npm
* sass
* typescript
### server starting
*    docker-compose
***
# development
### Node extensions
    npm install
# watchers
### sass:
    sass --watch ./devfiles/sass/main.sass ./html/static/style.css
### TypeScript:
    tsc -p .\devfiles\type_script\tsconfig.json -w
***
# start server
    docker-compose up