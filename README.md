# Event Organizer

Event Organizer is a mono repo application made for BBOX, by Gál Tamás!

## Installation

Make sure you have .env files near every .env.example file!

Run docker compose up -d

Visit localhost:3000, you should see the running application here.

Run Laravel migrations and seeds

docker compose exec backend php artisan migrate

docker compose exec backend php artisan db:seed

You will have 3 users by default admin@example.com / secret123 user@example.com / secret123 organizer@example.com / secret123
