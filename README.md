```
  ______     _           
 |  ____|   | |          
 | |__   ___| |__   ___  
 |  __| / __| '_ \ / _ \ 
 | |___| (__| | | | (_) |
 |______\___|_| |_|\___/                         
```

### Useful Commands

- Connect to the container `docker exec -it echo-laravel.test-1 /bin/bash`
- Get the container IP `docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' echo-laravel.test-1`

### Database

- Run migrations `php artisan migrate`
- Create new migration `php artisan make:migration create_users_table`
