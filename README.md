# TU Academy WP Plugin

**TU Academy** is a WordPress plugin that provides an address book management system with advanced features such as caching and REST API support. It is ideal for developers and users looking for a reliable, high-performance solution for managing contacts directly within WordPress.

---

## Badges

![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)  
![ReadMe](https://img.shields.io/badge/README-Ready-blue.svg)

---

## Features

- **Address Book Management**:
  - Add, edit, delete, and list contacts directly in the WordPress admin panel.
- **Custom REST API**:
  - Provides full CRUD functionality via the custom endpoint: `academy/v1/contacts`.
- **Caching**:
  - Built-in caching for improved performance and reduced server load.
- **Developer-Friendly**:
  - Extensible and clean architecture for easy customization.
- **Lightweight & Efficient**:
  - Optimized for speed and scalability.

---

## Installation

1. **Download the Plugin**:
   - Clone the repository or download the plugin zip file.

   ```bash
   git clone https://github.com/developerbayazid/tu-academy.git


2.Install via WordPress Admin:

 Log in to your WordPress dashboard.
 Navigate to Plugins > Add New.
 Click Upload Plugin and upload the zip file.
 Activate the plugin.

3.Install via FTP:

 Extract the zip file and upload the folder to the /wp-content/plugins/ directory.
 Activate the plugin from the WordPress dashboard.

# Usage
  Admin Panel
  Navigate to TU Academy in the WordPress admin menu.
  Manage your contacts with an intuitive interface.
  REST API
  Use the custom endpoint academy/v1/contacts for programmatic access.
  Example API calls:
  Get All Contacts

  GET /wp-json/academy/v1/contacts

    POST /wp-json/academy/v1/contacts
    Content-Type: application/json

    {
        "name": "John Doe",
        "email": "john.doe@example.com",
        "phone": "123-456-7890"
    }

    PUT /wp-json/academy/v1/contacts/{id}
    Content-Type: application/json

    {
        "name": "Jane Doe",
        "email": "jane.doe@example.com"
    }

    DELETE /wp-json/academy/v1/contacts/{id}


