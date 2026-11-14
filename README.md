# BlogXpert

![BlogXpert](https://img.shields.io/badge/Status-Active-brightgreen.svg)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)

BlogXpert is a robust, role-based blogging platform built entirely from scratch with HTML, CSS, vanilla JavaScript, PHP, and MySQL. It introduces a structured environment for content management where users can explore stories, writers can craft them, and administrators ensure high-quality publications.

## 🚀 Key Features

### Role-Based Access Control
- **Readers (Users):** Can browse approved posts, filter by categories, engage via interactive comments, and like their favorite articles.
- **Writers:** Can craft, format, edit, and submit blog posts for approval. Users can apply to become writers using a dedicated form.
- **Admins:** Oversee the content ecosystem. They can approve pending posts, delete inappropriate ones, and manage platform categories.
- **Super Admins:** Possess maximum privileges, including assigning and revoking writer and admin roles across the platform.

### Core Functionalities
- **Secure Authentication:** Complete session management and access validation for every role.
- **Approval Workflow:** Submissions by writers sit in a `pending` state until verified by an Admin, ensuring quality control.
- **Interactive Engagement:** Built-in dynamic like and comment system for enhanced reader participation (tracked per-user so posts cannot be liked multiple times).
- **Responsive UI:** Fully responsive frontend, providing an optimized viewing experience on desktops, tablets, and smartphones.
- **Dedicated Dashboards:** Tailored user interfaces and control panels for writers, admins, and the main admin.

## 🛠 Tech Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP (Procedural/Vanilla)
- **Database:** MySQL
- **Tooling:** Composer (for potential future dependencies)

## 📂 Project Structure

```text
BlogXpert/
├── admin/          # Admin dashboard and management handlers
├── auth/           # Login, registration, and session management
├── assests/        # Static assets (CSS, JS, images)
├── config/         # Database and environment configurations
├── database/       # SQL scripts (e.g., Blog.sql for DB schema)
├── function/       # Shared utility and helper functions
├── includes/       # Reusable UI components (header, footer)
├── pages/          # Public-facing views (posts, categories, comments)
├── vendor/         # Composer dependencies
├── writer/         # Writer dashboard and post creation tools
└── index.php       # Application entry point
```

## ⚙️ Installation & Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/muhammadshaharyaraulakh/BlogXpert.git
   cd BlogXpert
   ```

2. **Configure Database**
   - Import the `database/Blog.sql` file into your local MySQL server.
   - This script creates the `BlogXpert` database along with `user`, `categories`, `posts`, `comments`, and `post_likes` tables.

3. **Environment Setup**
   - Navigate to `config/config.php` and update the database credentials (Host, Username, Password) to match your local environment.

4. **Run the Application**
   - Host the directory on a local server like **XAMPP**, **WAMP**, or **MAMP** (e.g., placing it inside the `htdocs` or `www` folder).
   - Alternatively, use the built-in PHP server:
     ```bash
     php -S localhost:8000
     ```
   - Open your browser and navigate to `http://localhost:8000`.

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!
Feel free to check [issues page](https://github.com/muhammadshaharyaraulakh/BlogXpert/issues) if you want to contribute.

## 📝 License

This project is licensed under the terms of the MIT license. Please check the `LICENSE` file for more details.
