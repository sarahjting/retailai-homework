# RetailAI Homework

## Implementation notes
- I made the choice to combine the admin and merchant authentication endpoints. 
  - This decision was made with consideration to the current app's logic (which doesn't really justify making two separate controllers), but also towards supporting multiple user groups. If in the future we want `/admin/signup`, `/merchant/signup`, `/customer/signup`, `/legal/signup`, `/accounting/signup`, making a separate controller for each may be overkill if the logic is not different. 
  - In this scenario I want to avoid the duplicated code and just provide a generic base with different authorization/implementation. I wouldn't be against splitting the code into completely separated controllers, though.

## Setup
1. Sail up
    ```
    docker compose up
    ```
2. Link storage folders
   ```
    ./vendor/bin/sail artisan storage:link
   ```
3. Migrations
   ```
   ./vendor/bin/sail artisan migrate
   ```

## Screenshots

| ![Screen Shot 2023-01-06 at 14 34 32](https://user-images.githubusercontent.com/58196030/210937123-8fb0c69a-13cb-4371-a14d-38cede4d7d1d.png) | ![Screen Shot 2023-01-06 at 14 34 38](https://user-images.githubusercontent.com/58196030/210937293-91fbc8ae-36f2-4e70-ac75-21f5664aa07f.png) | ![Screen Shot 2023-01-06 at 14 19 43](https://user-images.githubusercontent.com/58196030/210937320-2e7e4093-f567-43dc-9abd-21f7562bac06.png) |
|---|---|---|
| ![Screen Shot 2023-01-06 at 14 21 20](https://user-images.githubusercontent.com/58196030/210937427-0107d34a-e99f-45a3-a9b4-c5c244df596e.png) | ![Screen Shot 2023-01-06 at 14 21 50](https://user-images.githubusercontent.com/58196030/210937458-437d72db-7ed9-4389-ae6a-5fbcfdc3b89b.png) | ![Screen Shot 2023-01-06 at 14 22 13](https://user-images.githubusercontent.com/58196030/210937481-48604a99-de97-4f82-bde0-cbca3cecd6bd.png) |
| ![Screen Shot 2023-01-06 at 14 19 56](https://user-images.githubusercontent.com/58196030/210937502-1b7aa8d2-6a34-4241-bfb6-1c89af004a57.png) | ![Screen Shot 2023-01-06 at 16 51 04](https://user-images.githubusercontent.com/58196030/210955310-0cfbd511-c148-4f56-b12f-f1119eb6c0db.png) | 

