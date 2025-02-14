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

### Authentication

-   POST /api/v1/login # User login
-   POST /api/v1/register # User registration
-   GET /api/v1/user # Get current user profile

### Event Management

-   GET /api/v1/events # Get all events
-   GET /api/v1/topEvents # Get featured/top events
-   GET /api/v1/getEvents # Get paginated events with filters
-   GET /api/v1/user/events # Get user's created events
-   POST /api/v1/events # Create a new event
-   PUT /api/v1/events/{event} # Update an event
-   DELETE /api/v1/events/{event} # Delete an event

### Participation

-   POST /api/v1/events/{event}/join # Join an event
-   POST /api/v1/events/{event}/leave # Leave an event
-   GET /api/v1/user/participations # Get user's event participations

### Notifications

-   GET /api/v1/notifications # Get user notifications
-   POST /api/v1/notifications/{notification}/read # Mark notification as read

## Models

### User

-   Basic authentication fields
-   Profile information
-   Relationship with events and participations

### Event

-   Title and description
-   Start date
-   Online/offline status
-   Location or online URL
-   Price
-   Category
-   Maximum participants
-   Relationship with host and participants

### Participation

-   Event and user relationships
-   Status tracking (pending, confirmed, cancelled, declined)
-   Timestamp information

### Notification

-   User relationship
-   Type of notification
-   Data payload
-   Read/unread status
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

## Installation Requirements

-   PHP >= 8.1
-   Composer
-   MySQL >= 5.7 or PostgreSQL >= 10.0
-   Node.js & NPM (for frontend assets)
-   Redis (for queue and broadcasting)

## Environment Setup

The following environment variables need to be configured in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_cluster

```

## Testing

Run the test suite with:

```bash
php artisan test
```

For specific test suites:

```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## API Documentation

API documentation is available at `/api/documentation` when running the application locally. You can also find the Postman collection in the `docs` folder.

## Error Handling

The API uses standard HTTP response codes:

-   200: Success
-   201: Created
-   400: Bad Request
-   401: Unauthorized
-   403: Forbidden
-   404: Not Found
-   422: Validation Error
-   500: Server Error

## Rate Limiting

API endpoints are rate-limited to:

-   Authentication endpoints: 5 requests per minute
-   Other endpoints: 60 requests per minute

## Support

For support, please email support@eventplatform.com or create an issue in the GitHub repository.

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information on recent changes.
