# Event Management Platform API

This Laravel-based API provides a robust backend for an event management platform where users can create, join, and manage events.

## Project Overview

The platform allows users to:

-   Create and manage events
-   Join/leave events
-   Receive real-time notifications
-   Get email notifications for important actions
-   View their events and participations

## Technical Stack

-   **Framework**: Laravel
-   **Authentication**: Laravel Sanctum
-   **Real-time**: Laravel Broadcasting with Pusher
-   **Database**: MySQL/PostgreSQL (supports both)

## Project Structure

```
laravel-event-platform/
├── app/
│ ├── Models/ # Database models
│ ├── Http/
│ │ ├── Controllers/ # API controllers
│ │ ├── Requests/ # Form requests & validation
│ │ └── Resources/ # API resources
│ ├── Events/ # Event classes for broadcasting
│ ├── Notifications/ # Notification classes
│ └── Services/ # Business logic services
```

## Core Features

### Authentication

-   User registration
-   Login with token generation
-   Secure logout
-   Profile management

### Event Management

-   CRUD operations for events
-   Event categorization
-   Location tracking
-   Participant limits
-   Event status tracking (draft, published, cancelled, completed)
-   Public/private event visibility

### Participation System

-   Join/leave events
-   Participation status management (pending, confirmed, cancelled, declined)
-   Automatic spot availability checking
-   Host controls for managing participants

### Real-time Features

-   Instant notifications when users join events
-   Real-time updates for event changes
-   Broadcasting system integration

## API Endpoints

POST /api/v1/login # User login
POST /api/v1/register # User registration
POST /api/v1/logout # User logout
GET /api/v1/me # Get current user

### Event Management

GET /api/v1/events # Get all events
GET /api/v1/events/{event} # Get a single event
POST /api/v1/events # Create a new event
PUT /api/v1/events/{event} # Update an event
DELETE /api/v1/events/{event} # Delete an event

### Participation

POST /api/v1/events/{event}/join # Join an event
POST /api/v1/events/{event}/leave # Leave an event

### Notifications

GET /api/v1/notifications # Get all notifications

## Models

### User

-   Basic authentication fields
-   Profile information
-   Notification preferences

### Event

-   Title and description
-   Date and time
-   Location (with coordinates)
-   Maximum participants
-   Category and visibility settings
-   Status tracking

### Participation

-   Event and user relationships
-   Status tracking
-   Timestamp information

## Real-time Notifications

The platform uses Laravel's broadcasting system to provide real-time updates for:

-   New event participants
-   Event updates
-   Status changes
-   Custom notifications

## Security Features

-   Token-based authentication using Sanctum
-   Form request validation
-   Authorization policies
-   CORS protection
-   Rate limiting

## Getting Started

1. Clone the repository
2. Install dependencies:

```bash
composer install
```

3. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`

5. Run migrations:

```bash
php artisan migrate
```

6. Start the server:

```bash
php artisan serve
```

## Next Steps

Future enhancements could include:

-   Event comments/discussion system
-   File attachments for events
-   Event recurring patterns
-   Advanced search and filtering
-   Social sharing features
-   Calendar integration
-   Payment integration for paid events

## Contributing

Please read our contributing guidelines before submitting pull requests.

## License

This project is licensed under the MIT License.
