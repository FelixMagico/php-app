
php-app

Applicazione PHP + MariaDB dockerizzata (progetto laboratorio Docker).

Avvio

1. Copiare .env.example in .env e inserire le password
2. Creare la rete: docker network create app-net
3. Build immagine: docker build --target prod -t php-app:1.0.0 .
4. Deploy dello stack docker-compose.yml tramite Portainer (caricando il .env)
5. App: http://localhost:8080

Sviluppo

VS Code - Dev Containers: Reopen in Container

Ispezione DB

docker run -d --name phpmyadmin --network app-net -e PMA_HOST=db -p 8081:80 phpmyadmin:5

- http://localhost:8081