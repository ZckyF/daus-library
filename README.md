<h1 style="border-bottom: 0px">🚀 Daus Library</h1>

## ✏ Description

Daus Library is a web application specifically designed for library management personnel. It provides a comprehensive platform for librarians to efficiently manage library resources, track borrowing activities, and maintain user records.

## 🔑 Key Features

-   **Super Admin Access:** Super admin role for creating and managing librarian accounts.

-   **Book Management:** Librarians can add, edit, and remove books from the library catalog.

-   **User Management:** Ability to register and manage librarian accounts, including updating user information and permissions.

-   **Borrowing Records:** Track borrowing history and manage book availability.

-   **Search Functionality:** Advanced search capabilities for librarians to quickly find books based on various criteria.

-   **Reports and Analytics:** Generate reports on library activity, book availability, and user engagement.

## ⚙ Technologies Used:

-   **Backend**: Laravel PHP framework
-   **Frontend**: HTML, CSS, JavaScript
-   **Database**: MySQL
-   **Authentication**: Laravel Passport for API authentication
-   **Frontend Framework**: Bootstrap for responsive design
-   **Additional Packages**:
    -   Livewire for dynamic, reactive interfaces
    -   Spatie Laravel Permission for role and permission management

## 📑 Prerequisites

Before you begin, ensure you have met the following requirements:

-   [Node.js](https://nodejs.org/) installed
-   [npm](https://www.npmjs.com/) installed
-   [Composer](https://getcomposer.org/) installed
-   [PHP](https://www.php.net/) installed
-   [MySQL](https://www.mysql.com/) or [PostgreSQL](https://www.postgresql.org/) installed (or any SQL database)

## 🛠️ Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/daus-library.git
    ```

2. Navigate to the 'server' folder:

    ```bash
    cd server
    ```

3. Install PHP dependencies using Composer:

    ```bash
    composer install
    ```

4. Install JavaScript dependencies using npm:

    ```bash
    npm install
    ```

5. Copy the '.env.example' file to '.env':

    ```bash
    cp .env.example .env
    ```

6. Generate the application key:

    ```bash
    php artisan key:generate
    ```

7. Create an empty database (e.g., with the name 'daus_library').

8. Configure your database connection by editing the '.env' file:

    ```plaintext
    DB_CONNECTION=mysql //or anything SQL
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=<Name database>
    DB_USERNAME=<Username database>
    DB_PASSWORD=<Password database>
    ```

9. Publish spatie laravel-permission:

    ```bash
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
    ```

10. Install Livewire:

    ```bash
    composer require livewire/livewire
    ```

11. Publish Livewire config:

    ```bash
    php artisan livewire:publish --config
    ```

12. Create symbolic link for storage:

    ```bash
    php artisan storage:link
    ```

13. Migrate the database:

    ```bash
    php artisan migrate:fresh --seed
    ```

14. Run Vite for development mode:

    ```bash
    npm run dev
    ```

15. Run the Laravel development server:

    ```bash
    php artisan serve
    ```

This project will run at http://127.0.0.1:8000 (if not changed the default port). You can access it through your web browser.

## 🖥 Usage:

-   Super admin creates librarian accounts with appropriate permissions.

-   Librarians log in to the application to manage library resources, user accounts, and borrowing activities.

-   Add, edit, or remove books from the catalog as needed.

-   Register new librarian accounts and update permissions as necessary.
    Track borrowing history and manage book availability.

-   Utilize search functionality to quickly find books based on various criteria.

## 🤝 Contributing to Daus Library

Thank you for your interest in contributing! We appreciate every contribution you make to improve this project.

To contribute to this project, follow these steps:

1. Fork this repository and clone the fork to your local machine.
2. Create a new branch with the feature or fix you will be working on.
3. Make the necessary changes within your branch.
4. Make sure to test your changes.
5. Commit your changes with a clear and descriptive message.
6. Push your branch to your forked repository on GitHub.
7. Create a pull request (PR) to the main repository.

## 📝 Code Style Guidelines

To maintain consistency in the project's code, please adhere to the code style guidelines established in this project. Make sure to follow the established coding style and maintain consistency in the code structure.

## 🐞 Bug Reports

If you find a bug in this project, please create a bug report in the Issues section. Make sure to provide clear information about the bug, including steps to reproduce the bug if possible.

## 💡 Suggestions and Questions

If you have suggestions or questions about this project, feel free to make them in the Issues section. We are always open to feedback and suggestions from users of this project.


## 📄 License

Daus Library is open-sourced software licensed under the [MIT license]('https://github.com/ZckyF/daus-library/blob/main/LICENSE').
