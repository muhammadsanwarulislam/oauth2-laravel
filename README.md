# Project Documentation: Nuxt.js and Laravel Application with OAuth 2.0

This repository contains a full-stack web application built with a Nuxt.js frontend and a Laravel backend, integrated with OAuth 2.0 for secure authentication and authorization. The application includes an admin dashboard and a client-facing frontend, powered by a robust Laravel RESTful API. The backend uses a relational database to manage users, OAuth clients, and tokens, ensuring scalability and security.

## Table of Contents
1. [Project Overview](#project-overview)
2. [Installation Instructions](#installation-instructions)
   - [Admin Dashboard and Frontend (Nuxt.js)](#admin-dashboard-and-frontend-nuxtjs)
   - [Backend API (Laravel)](#backend-api-laravel)
3. [Database Schema Explanation](#database-schema-explanation)
   - [Users](#users)
   - [OAuth Clients](#oauth-clients)
   - [OAuth Access Tokens](#oauth-access-tokens)
   - [OAuth Client Credentials](#oauth-client-credentials)
   - [OAuth Authorization Codes](#oauth-authorization-codes)
   - [OAuth Refresh Tokens](#oauth-refresh-tokens)
4. [Database Table Design](#database-table-design)
   - [Table: users](#table-users)
   - [Table: oauth_clients](#table-oauth_clients)
   - [Table: oauth_access_tokens](#table-oauth_access_tokens)
   - [Table: oauth_client_credentials](#table-oauth_client_credentials)
   - [Table: oauth_auth_codes](#table-oauth_auth_codes)
   - [Table: oauth_refresh_tokens](#table-oauth_refresh_tokens)

## Project Overview

This project is a secure and scalable web application with two Nuxt.js frontends:

   **Admin Dashboard:** A management interface for administrators, accessible at http://localhost:3000.
   **Client Frontend:** A user-facing interface for end users, accessible at http://localhost:3001.

The Laravel backend provides a RESTful API for user authentication (via email or phone), data management, and OAuth 2.0 token-based access. It leverages the Repository Pattern for database operations, caching for performance in production, and standardized JSON responses for seamless integration with the frontend.

## Installation Instructions

This section outlines the steps to set up the Nuxt.js frontend (admin dashboard and client) and the Laravel backend API.

### Admin Dashboard and Frontend (Nuxt.js)

Follow these steps to install and run the Nuxt.js projects for the admin dashboard and frontend:

1. Clone or download the repository.
2. Run the command `npm install`.
3. Create a `.env` file by copying `.env.example` using `cp .env.example .env`.
4. Update the API endpoint base URL in the `.env` file.
5. Run `npm run dev` from the project root and visit [http://localhost:3000/](http://localhost:3000/) for the admin dashboard.
6. Run `npm run dev` from the project root and visit [http://localhost:3001/](http://localhost:3001/) for the frontend.

### Backend API (Laravel)

Follow these steps to install and run the Laravel backend API:

1. Clone or download the repository.
2. Go to the project directory and run `composer install`.
3. Create a `.env` file by copying `.env.example` using `cp .env.example .env`.
4. Update the database name and credentials in the `.env` file.
5. Run `php artisan migrate:fresh --seed` to set up the database.
6. Run `php artisan serve` to start the API server and verify functionality.

## Database Schema Explanation

The Laravel backend uses a relational database to support user management and OAuth-based authentication. This section describes the key tables and their purposes.

### Users

Stores user information for authentication.

- `id`: Auto-incrementing primary key.
- `name`: User's full name.
- `email`: Unique email address for login and communication.
- `phone`: Unique phone number for contact or verification.
- `email_verified_at`: Timestamp for email verification (nullable).
- `password`: Hashed password for authentication (nullable for OAuth users).
- `remember_token`: Token for "remember me" functionality.
- `created_at`, `updated_at`: Timestamps for record creation and updates.

### OAuth Clients

Stores OAuth client applications for API access.

- `id`: Auto-incrementing primary key.
- `user_id`: Identifier for the user owning the client (nullable, indexed).
- `name`: Client application name.
- `secret`: Client secret for authentication (100 characters, nullable).
- `redirect`: Redirect URI for OAuth flow.
- `personal_access_client`: Boolean flag for personal access clients (default: false).
- `created_at`, `updated_at`: Timestamps for record creation and updates.

### OAuth Access Tokens

Manages OAuth access tokens for authenticated API requests.

- `id`: Primary key, unique token identifier (100 characters).
- `user_id`: Foreign key referencing `users.id` (nullable, indexed).
- `client_id`: Foreign key referencing `oauth_clients.id`.
- `name`: Token name (nullable).
- `scopes`: JSON array of token scopes (nullable).
- `revoked`: Boolean indicating if token is revoked (default: false).
- `created_at`, `updated_at`: Timestamps for record creation and updates.
- `expires_at`: Token expiration timestamp (nullable).
- `refresh_token`: Associated refresh token (nullable).
- **Indexes**: Composite index on `client_id` and `user_id` for efficient queries.

### OAuth Client Credentials

Stores credentials for OAuth clients, likely for the client credentials grant.

- `id`: Auto-incrementing primary key.
- `client_id`: Foreign key referencing `oauth_clients.id` (unique).
- `created_at`, `updated_at`: Timestamps for record creation and updates.

### OAuth Authorization Codes

Manages OAuth authorization codes for the authorization code grant.

- `id`: Primary key, unique code identifier (100 characters).
- `user_id`: Foreign key referencing `users.id` (indexed).
- `client_id`: Foreign key referencing `oauth_clients.id`.
- `revoked`: Boolean indicating if code is revoked.
- `expires_at`: Code expiration timestamp (nullable).
- `created_at`, `updated_at`: Timestamps for record creation and updates.
- **Indexes**: Composite index on `client_id` and `user_id` for efficient queries.

### OAuth Refresh Tokens

Stores OAuth refresh tokens for renewing access tokens.

- `id`: Primary key, unique token identifier (100 characters).
- `access_token_id`: Foreign key referencing `oauth_access_tokens.id` (indexed).
- `user_id`: Foreign key referencing `users.id` (nullable, indexed).
- `client_id`: Foreign key referencing `oauth_clients.id`.
- `revoked`: Boolean indicating if token is revoked.
- `expires_at`: Token expiration timestamp (nullable).
- `created_at`, `updated_at`: Timestamps for record creation and updates.
- **Indexes**: Composite index on `client_id` and `user_id` for efficient queries.

This schema supports a robust authentication system suitable for a secure and scalable application.

## Database Table Design

This section provides a detailed design of the database tables, including columns, data types, constraints, and relationships, as implemented via Laravel migrations.

### Table: users

| Column              | Data Type        | Constraints                     | Description                          |
|---------------------|------------------|---------------------------------|--------------------------------------|
| id                  | bigint           | Primary Key, Auto-increment     | Unique identifier for each user      |
| name                | varchar(255)     | Not Null                        | User's full name                     |
| email               | varchar(255)     | Unique, Not Null                | User's email address                 |
| phone               | varchar(255)     | Unique, Not Null                | User's phone number                  |
| email_verified_at   | timestamp        | Nullable                        | Timestamp of email verification      |
| password            | varchar(255)     | Nullable                        | Hashed password                      |
| remember_token      | varchar(100)     | Nullable                        | Token for "remember me" feature      |
| created_at          | timestamp        | Nullable                        | Record creation timestamp            |
| updated_at          | timestamp        | Nullable                        | Record update timestamp              |

**Relationships**:
- Referenced by `oauth_access_tokens.user_id`, `oauth_auth_codes.user_id`, `oauth_refresh_tokens.user_id`.

### Table: oauth_clients

| Column              | Data Type        | Constraints                     | Description                          |
|---------------------|------------------|---------------------------------|--------------------------------------|
| id                  | bigint           | Primary Key, Auto-increment     | Unique identifier for each client    |
| user_id             | varchar(255)     | Nullable, Indexed               | User owning the client               |
| name                | varchar(255)     | Not Null                        | Client application name              |
| secret              | varchar(100)     | Nullable                        | Client secret for authentication     |
| redirect            | varchar(255)     | Not Null                        | Redirect URI for OAuth flow          |
| personal_access_client | boolean       | Default: false                  | Flag for personal access clients     |
| created_at          | timestamp        | Nullable                        | Record creation timestamp            |
| updated_at          | timestamp        | Nullable                        | Record update timestamp              |

**Relationships**:
- Referenced by `oauth_access_tokens.client_id`, `oauth_client_credentials.client_id`, `oauth_auth_codes.client_id`, `oauth_refresh_tokens.client_id`.

### Table: oauth_access_tokens

| Column              | Data Type        | Constraints                     | Description                          |
|---------------------|------------------|---------------------------------|--------------------------------------|
| id                  | varchar(100)     | Primary Key                     | Unique token identifier              |
| user_id             | bigint           | Nullable, Foreign Key, Indexed  | References `users.id`                |
| client_id           | bigint           | Not Null, Foreign Key           | References `oauth_clients.id`        |
| name                | varchar(255)     | Nullable                        | Token name                           |
| scopes              | json             | Nullable                        | JSON array of token scopes           |
| revoked             | boolean          | Default: false                  | Indicates if token is revoked        |
| created_at          | timestamp        | Nullable                        | Record creation timestamp            |
| updated_at          | timestamp        | Nullable                        | Record update timestamp              |
| expires_at          | datetime         | Nullable                        | Token expiration timestamp           |
| refresh_token       | varchar(255)     | Nullable                        | Associated refresh token             |

**Indexes**:
- Composite index on `client_id`, `user_id`.

**Relationships**:
- `user_id` references `users.id`.
- `client_id` references `oauth_clients.id`.
- Referenced by `oauth_refresh_tokens.access_token_id`.

### Table: oauth_client_credentials

| Column              | Data Type        | Constraints                     | Description                          |
|---------------------|------------------|---------------------------------|--------------------------------------|
| id                  | bigint           | Primary Key, Auto-increment     | Unique identifier for credentials    |
| client_id           | bigint           | Not Null, Foreign Key, Unique   | References `oauth_clients.id`        |
| created_at          | timestamp        | Nullable                        | Record creation timestamp            |
| updated_at          | timestamp        | Nullable                        | Record update timestamp              |

**Relationships**:
- `client_id` references `oauth_clients.id`.

### Table: oauth_auth_codes

| Column              | Data Type        | Constraints                     | Description                          |
|---------------------|------------------|---------------------------------|--------------------------------------|
| id                  | varchar(100)     | Primary Key                     | Unique authorization code identifier |
| user_id             | bigint           | Not Null, Foreign Key, Indexed  | References `users.id`                |
| client_id           | bigint           | Not Null, Foreign Key           | References `oauth_clients.id`        |
| revoked             | boolean          | Not Null                        | Indicates if code is revoked         |
| expires_at          | datetime         | Nullable                        | Code expiration timestamp            |
| created_at          | timestamp        | Nullable                        | Record creation timestamp            |
| updated_at          | timestamp        | Nullable                        | Record update timestamp              |

**Indexes**:
- Composite index on `client_id`, `user_id`.

**Relationships**:
- `user_id` references `users.id`.
- `client_id` references `oauth_clients.id`.

### Table: oauth_refresh_tokens

| Column              | Data Type        | Constraints                     | Description                          |
|---------------------|------------------|---------------------------------|--------------------------------------|
| id                  | varchar(100)     | Primary Key                     | Unique refresh token identifier      |
| access_token_id     | varchar(100)     | Not Null, Foreign Key, Indexed  | References `oauth_access_tokens.id`  |
| user_id             | bigint           | Nullable, Foreign Key, Indexed  | References `users.id`                |
| client_id           | bigint           | Not Null, Foreign Key           | References `oauth_clients.id`        |
| revoked             | boolean          | Not Null                        | Indicates if token is revoked        |
| expires_at          | datetime         | Nullable                        | Token expiration timestamp           |
| created_at          | timestamp        | Nullable                        | Record creation timestamp            |
| updated_at          | timestamp        | Nullable                        | Record update timestamp              |

**Indexes**:
- Composite index on `client_id`, `user_id`.

**Relationships**:
- `access_token_id` references `oauth_access_tokens.id`.
- `user_id` references `users.id`.
- `client_id` references `oauth_clients.id`.

This flow ensures secure user authentication, client management, and API access using OAuth 2.0, with the database schema supporting all operations efficiently.