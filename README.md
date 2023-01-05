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
