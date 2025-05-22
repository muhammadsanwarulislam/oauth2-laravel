# Project Documentation: Nuxt.js and Laravel Application

This document provides a comprehensive guide to setting up and understanding a web application with a Nuxt.js frontend and a Laravel backend, integrated with OAuth 2.0 for secure authentication and authorization. It covers installation steps, database schema, table designs, and the operational flow of the system, serving as a detailed resource for developers.

## Table of Contents
1. [Installation Instructions](#installation-instructions)
   - [Admin Dashboard and Frontend (Nuxt.js)](#admin-dashboard-and-frontend-nuxtjs)
   - [Backend API (Laravel)](#backend-api-laravel)
2. [Database Schema Explanation](#database-schema-explanation)
   - [Users](#users)
   - [OAuth Clients](#oauth-clients)
   - [OAuth Access Tokens](#oauth-access-tokens)
   - [OAuth Client Credentials](#oauth-client-credentials)
   - [OAuth Authorization Codes](#oauth-authorization-codes)
   - [OAuth Refresh Tokens](#oauth-refresh-tokens)
3. [Database Table Design](#database-table-design)
   - [Table: users](#table-users)
   - [Table: oauth_clients](#table-oauth_clients)
   - [Table: oauth_access_tokens](#table-oauth_access_tokens)
   - [Table: oauth_client_credentials](#table-oauth_client_credentials)
   - [Table: oauth_auth_codes](#table-oauth_auth_codes)
   - [Table: oauth_refresh_tokens](#table-oauth_refresh_tokens)
4. [Working Flow](#working-flow)
   - [User Registration and Login](#user-registration-and-login)
   - [OAuth Client Management](#oauth-client-management)
   - [OAuth Authorization Flow](#oauth-authorization-flow)
   - [Token Management](#token-management)
   - [Database Interactions](#database-interactions)

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

Stores user information for authentication and profile management.

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

## Working Flow

This section explains the operational flow of the application, detailing how the system handles user authentication, OAuth client management, and token-based API access using an OAuth 2.0 framework.

### User Registration and Login

The `AuthController` manages user registration, login, logout, and information retrieval, interacting primarily with the `users` table and generating tokens in `oauth_access_tokens`.

1. **Registration**:
   - Users submit details (name, email, phone, password) via a registration request.
   - The system hashes the password and stores the user in `users` (e.g., `INSERT INTO users (name, email, phone, password, created_at, updated_at) VALUES ('John Doe', 'john@example.com', '1234567890', '$2y$10$...', NOW(), NOW())`).
   - Returns the user’s details.

2. **Login**:
   - Users log in with email or phone and password.
   - The system verifies credentials in `users` (e.g., `SELECT * FROM users WHERE email = 'john@example.com' OR phone = '1234567890'`).
   - If valid, generates an access token in `oauth_access_tokens` (e.g., `INSERT INTO oauth_access_tokens (id, user_id, client_id, expires_at, created_at, updated_at) VALUES ('token', user_id, client_id, NOW() + interval, NOW(), NOW())`).
   - Returns the token and user details for API access.

3. **Logout and User Info**:
   - Logout invalidates the session (may revoke tokens in `oauth_access_tokens`).
   - User info requests query `users` for authenticated user data or join `oauth_access_tokens` with `users` to fetch details using a valid token.

### OAuth Client Management

The `ClientController` handles the creation, retrieval, updating, and deletion of OAuth clients, stored in `oauth_clients`.

1. **Create Client**:
   - Admins create a client with details (name, redirect URI), stored in `oauth_clients` (e.g., `INSERT INTO oauth_clients (name, secret, redirect, created_at, updated_at) VALUES ('Client App', 'secret', 'https://redirect', NOW(), NOW())`).
   - May link to `oauth_client_credentials` for client credentials grant.

2. **Manage Clients**:
   - List clients with pagination (`SELECT * FROM oauth_clients LIMIT limit OFFSET offset`).
   - Retrieve, update, or delete clients by ID (e.g., `SELECT * FROM oauth_clients WHERE id = 'id'`, `UPDATE oauth_clients SET name = 'New Name' WHERE id = 'id'`, `DELETE FROM oauth_clients WHERE id = 'id'`).
   - Deletion may cascade to related tables (`oauth_access_tokens`, `oauth_auth_codes`, `oauth_refresh_tokens`).

### OAuth Authorization Flow

The `AuthorizationController` manages the OAuth 2.0 authorization code grant, issuing authorization codes and tokens.

1. **Authorize Client**:
   - A client initiates authorization with a `client_id`.
   - Retrieves client details from `oauth_clients` (e.g., `SELECT * FROM oauth_clients WHERE id = 'client_id'`).
   - Displays client info for user approval.

2. **Approve Authorization**:
   - Upon approval, generates an authorization code in `oauth_auth_codes` (e.g., `INSERT INTO oauth_auth_codes (id, user_id, client_id, revoked, expires_at, created_at, updated_at) VALUES ('code', user_id, client_id, false, NOW() + interval, NOW(), NOW())`).
   - Returns the code and redirect URI.

3. **Generate Access Token**:
   - The client exchanges the code for an access token.
   - Verifies the code in `oauth_auth_codes` (e.g., `SELECT * FROM oauth_auth_codes WHERE id = 'code' AND revoked = false`).
   - Creates tokens in `oauth_access_tokens` and `oauth_refresh_tokens` (e.g., `INSERT INTO oauth_access_tokens (id, user_id, client_id, refresh_token, expires_at) VALUES ('token', user_id, client_id, 'refresh_token', NOW() + interval)`).
   - Marks the code as revoked (`UPDATE oauth_auth_codes SET revoked = true WHERE id = 'code'`).

### Token Management

The `AuthorizationController` handles token refresh and revocation, while the `VerifyAccessToken` middleware validates tokens.

1. **Token Verification**:
   - The middleware checks tokens in `oauth_access_tokens` (e.g., `SELECT * FROM oauth_access_tokens WHERE id = 'token' AND revoked = false AND expires_at > NOW()`).
   - Allows access if valid; otherwise, returns a 401 error.

2. **Refresh Token**:
   - Clients submit a refresh token.
   - Validates it in `oauth_access_tokens` (e.g., `SELECT * FROM oauth_access_tokens WHERE refresh_token = 'refresh_token' AND revoked = false`).
   - Updates `oauth_access_tokens` with a new token (e.g., `UPDATE oauth_access_tokens SET id = 'new_token', expires_at = NOW() + interval WHERE id = 'old_token'`).

3. **Revoke Tokens**:
   - Revokes tokens for a user and client, updating `oauth_access_tokens`, `oauth_auth_codes`, and `oauth_refresh_tokens` (e.g., `UPDATE oauth_access_tokens SET revoked = true WHERE user_id = user_id AND client_id = 'client_id'`).

### Database Interactions

- **`users`**: Stores user credentials and links to tokens/codes via `user_id`.
- **`oauth_clients`**: Holds client details, referenced by token and code tables.
- **`oauth_access_tokens`**: Manages API access tokens, validated by middleware.
- **`oauth_client_credentials`**: Supports client credentials grant (if used).
- **`oauth_auth_codes`**: Stores temporary authorization codes for token exchange.
- **`oauth_refresh_tokens`**: Enables token renewal, linked to access tokens.

This flow ensures secure user authentication, client management, and API access using OAuth 2.0, with the database schema supporting all operations efficiently.