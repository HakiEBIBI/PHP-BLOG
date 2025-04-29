# Blog post with PHP and server

## What does the website

This is a blog platform that enables registered users to create, manage, and share their blog posts. Non-registered users can browse and read all published content.

Key Features:
- User-friendly blog creation interface
- Public access to blog content without registration
- User profile management system
- Role-based content management (users can edit their own posts, admins have full access)

## How to install it

1. Clone the repository:
   ```bash
   git clone https://github.com/HakiEBIBI/PHP-BLOG
2. make sure you have php installed in you computer here's to [install PHP in the official website](https://www.php.net/downloads.php)
3. for using the website in local go to the file where the website is and use the command `php -S localhost:8080 -t ./public`
4. after that you can try the website 

## list of features

- User authentication system with secure password hashing
- Blog posts creation with title, content, and image upload
- Image validation (JPG, JPEG, PNG formats only)
- Secure file upload system with unique filenames
- Database storage using SQLite
- User profile management (name, email, password)
- Role-based access control (regular users vs admin)
- Session-based security for protected routes
- Mobile-responsive design