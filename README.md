<h1 style="border-bottom: 0px">🚀 Daus Library</h1>

## ✏ Description
![Dashboard Daus Library](https://drive.google.com/uc?export=view&id=1Hy21wj0Do_tweVcCvUjrCAdP3ntNJqXC)

Daus Library is a web application specifically designed for library management personnel. It provides a comprehensive platform for librarians to efficiently manage library resources, track borrowing activities, and maintain user records.

## 🔑 Key Features

-   **Super Admin Access:** Super admin role for creating and managing librarian accounts.

-   **Book Management:** Librarians can add, edit, and remove books from the library catalog.

-   **User Management:** Ability to register and manage librarian accounts, including updating user information and permissions.

-   **Borrowing Records:** Track borrowing history and manage book availability.

-   **Search Functionality:** Advanced search capabilities for librarians to quickly find books based on various criteria.

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
    git clone https://github.com/ZckyF/daus-library.git
    ```

2. Navigate to the 'server' folder:

    ```bash
    cd daus-library
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

## 🖥 How to Use the Project:

This guide will help you understand how to use the project effectively, including the roles and permissions available, and how to manage different aspects of the library system.

### 1. Getting Started
After cloning the repository and installing the necessary dependencies, start the application using the following command:

```bash
php artisan serve
```


### 2. Roles and Permissions
The system has four roles, each with specific permissions:

- **Super Admin**:
  - Has full control over the entire application.
  - Can create, update, and delete users, including admins, librarians, and staff.

- **Admin**:
  - Also has full control but **cannot create users with the admin role**.
  - Manages employees, members, and user accounts.

- **Librarian**:
  - Manages books, book categories, and bookshelves.
  - Can create, update, and delete records in these sections.

- **Staff**:
  - Focuses on managing carts, borrowed books, and fines.
  - Can create, update, and delete records in these areas.

### 3. Logging In
Use the following credentials based on your role:

- **Super Admin**
  - **Username**: `superadmin`
  - **Password**: `password123`

- **Admin**
  - **Username**: `admin`
  - **Password**: `password123`

- **Librarian**
  - **Username**: `librarian`
  - **Password**: `password123`

- **Staff**
  - **Username**: `staff`
  - **Password**: `password123`

### 4. Managing Library Resources

- **Books**: Librarians can add, edit, and delete books. Navigate to the "Books" section, select an action, and fill out the required details.

- **Book Categories**: Librarians can manage categories by adding, editing, or deleting them under the "Book Categories" section.

- **Bookshelves**: Librarians can organize books by assigning them to shelves in the "Bookshelves" section.

### 5. Handling Borrowing and Returns

- **Carts**: Staff can create and manage carts for borrowing books. Go to the "Carts" section, select the books, and process the borrowing.

- **Borrowed Books**: Track and update the status of borrowed books in the "Borrowed Books" section.

- **Fines**: Staff can manage fines by viewing, creating, updating, or deleting them in the "Fines" section.

### 6. Dashboard Overview
The dashboard provides insights into library activities:

- **Charts**: View statistics on borrowed, returned, lost, damaged, and due books.

- **Top 10 Lists**: The dashboard also shows the top 10 borrowed books and the top 10 most borrowed books.

### 7. Settings and Profile Management
Users can manage their profiles and change their passwords through the "Settings" section. It's important to keep your credentials secure.

### 8. Troubleshooting
If you encounter any issues, refer to the troubleshooting guide in the Wiki or contact the project maintainers.

### Visual Aids
Here are some screenshots to help you understand the layout and design:

![Dashboard Screenshot](https://drive.google.com/uc?export=view&id=13scHAX89_oVpl4uVKKXHA1insWfkxPBB)

*The main dashboard provides an overview of library activities.*

![Books and Categories Management Screenshot](https://drive.google.com/uc?export=view&id=1G_myyhk2gr5FcJdFw5Az_Qh6O73fXNd2)
![Bookshelves Management Screenshot](https://drive.google.com/uc?export=view&id=1q7Wl-XExjx9IxwxuK2kKoQsZEbgR5p5C)

*Manage books, categories, and shelves easily through the interface.*




## 💡 Suggestions and Questions

If you have suggestions or questions about this project, feel free to make them in the Issues section. We are always open to feedback and suggestions from users of this project.
