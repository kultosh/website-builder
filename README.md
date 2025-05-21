## About Calendar

This project helps users build a basic website with dynamic pages, dynamic content, and dynamic menus, built using Vue and Laravel. [Live Demo of the Website Builder](https://santosh-website-builder.netlify.app/)

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
5. [What Has Been Done](#what-has-been-done)
6. [What Can Be Done](#what-can-be-done)
7. [Upcoming Features](#upcoming-features)
8. [API Endpoints](#api-endpoints)
9. [Technologies Used](#technologies-used)
10. [Credentials](#credentials)
11. [Contributing](#contributing)

## Prerequisites

Before you begin, ensure you have the following installed on your local machine:

- [Node.js](https://nodejs.org/) (v14.x or higher)
- [Vue CLI](https://cli.vuejs.org/)
- [PHP](https://www.php.net/) (v8.2 or higher)
- [Composer](https://getcomposer.org/)

## Installation

### Clone the repository:

```bash
git clone https://github.com/kultosh/website-builder.git
cd website-builder
```

### Install the dependencies :

#### For the Laravel Backend:

```bash
composer install
```
#### For the Vue Frontend:
```bash
cd frontend
npm install
```

## Configuration

### For the Laravel Backend:
1. Link your database in .env file
2. Generate the application key:
```bash
php artisan key:generate
```
3. Seed the database seeder to get admin user and settings data:
```bash
php artisan db:seed
```
4. Link the storage folder with public to access file:
```bash
php artisan storage:link
```

### For the Vue Frontend
#### .env Setup
Add the following variables to your frontend/.env file:
```bash
VUE_APP_NODE_ENV="development"
VUE_APP_ROOT_API="http://localhost:8000/api"
VUE_APP_ASSET_BASE_URL="http://localhost:8000"
VUE_APP_TITLE="Web Builder"
```

## Usage

### Running the Application

#### For the backend:
```bash
php artisan serve
```

#### For the frontend:
```bash
cd frontend
npm run serve
```
## What Has Been Done
1. Authentication using Sanctum: Users can log in and log out via the API using Sanctum authentication.
2. Dynamic Pages: Users can add, edit, update, and delete dynamic pages with three different layouts.
3. Sliders: Users can create, edit, update, and delete image sliders that can be displayed on the homepage.
4. Settings: Users can update website settings, including theme selection and other configurations

## What Can Be Done
1. Add Multiple Dynamic Content Pages: Users can create multiple pages with 3 different page section layouts.
2. Display Pages in Menus: Users can link the dynamic pages to a navigation menu, allowing easy access to each page.
3. Add Sliders to the Home Page: Users can add image sliders to the home page for dynamic visual content.
4. Display Multiple Pages on the Home Page: Users can display multiple pages or sections within the home page, creating a custom layout and read more feature.
5. Change Template Themes from Settings: Users can change the theme of the website directly from the settings page

## Upcoming Features
1. Menu Management with user friendly drag & drop menus order
2. Footer Management with user friendly drag & drop footer order
3. Live Preview Section while creating/editing pages
4. Contact Page Handling with contact form functionality

## API Endpoints
| HTTP Method                                            | Endpoint        | Description                                                                      |
| ------------------------------------------------------ | --------------- | -------------------------------------------------------------------------------- |
| `POST`                                                 | `/login`        | Logs the user in using the `AuthController::login` method                        |
| **Authenticated Routes (requires Sanctum middleware)** |                 | **Routes below require user to be authenticated via Sanctum**                    |
| `POST`                                                 | `/logout`       | Logs the user out using the `AuthController::logout` method                      |
| `GET`                                                  | `/dashboard`    | Fetches the dashboard data via `DashboardController::index`                      |
| **Pages Routes**                                       |                 | **CRUD operations for managing pages**                                           |
| `GET`                                                  | `/pages`        | Fetches all pages using `PageController::index`                                  |
| `POST`                                                 | `/pages`        | Creates a new page using `PageController::store`                                 |
| `GET`                                                  | `/pages/{id}`   | Edits a specific page identified by `{id}` using `PageController::edit`          |
| `PUT`                                                  | `/pages/{id}`   | Updates a specific page identified by `{id}` using `PageController::update`      |
| `DELETE`                                               | `/pages/{id}`   | Deletes a specific page identified by `{id}` using `PageController::destroy`     |
| `GET`                                                  | `/parent/pages` | Fetches all parent pages using `PageController::parentPages`                     |
| **Sliders Routes**                                     |                 | **CRUD operations for managing sliders**                                         |
| `GET`                                                  | `/sliders`      | Fetches all sliders using `SliderController::index`                              |
| `POST`                                                 | `/sliders`      | Creates a new slider using `SliderController::store`                             |
| `GET`                                                  | `/sliders/{id}` | Edits a specific slider identified by `{id}` using `SliderController::edit`      |
| `PUT`                                                  | `/sliders/{id}` | Updates a specific slider identified by `{id}` using `SliderController::update`  |
| `DELETE`                                               | `/sliders/{id}` | Deletes a specific slider identified by `{id}` using `SliderController::destroy` |
| `POST`                                                 | `/settings`     | Updates settings using `SettingController::updateSettings`                       |

## Technologies Used
- Frontend: Vue.js 2, Axios, Boostrap 5.3, CKEDITOR 5,
- Backend: Laravel, Sanctum, Image Intervention

## Credentials
- email: admin@admin.com
- password: password

## Contributing
Feel free to fork this repository and make your changes. If you would like to contribute, submit a pull request.