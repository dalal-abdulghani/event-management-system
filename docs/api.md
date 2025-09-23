# API Documentation (v1)

Base URL: `/api/v1`

Authentication:
- Public endpoints: no auth
- Protected endpoints: `Authorization: Bearer <token>` (Laravel Sanctum)

## Auth
- POST `/login` — email, password → issue token
- POST `/register` — name, email, password
- GET `/user` (auth) — current user
- POST `/logout` (auth) — revoke token

## Events
- GET `/events` — list public events
- GET `/events/{id}` — event details
- GET `/my-events` (auth)
- GET `/all-events` (auth)
- POST `/events/{id}/publish` (auth)
- REST (auth):
  - POST `/events`
  - GET `/events/{id}` (covered above)
  - PUT `/events/{id}`
  - PATCH `/events/{id}`
  - DELETE `/events/{id}`

## Ticket Types (nested)
- REST (auth): `/events/{event}/ticket-types`

## Venues
- GET `/venues` — list public venues
- GET `/venues/{id}` — venue details
- REST (auth): `/venues`

## Orders
- GET `/my-orders` (auth)
- POST `/orders/{id}/cancel` (auth)
- REST (auth): `/orders`

## Tickets
- GET `/my-tickets` (auth)
- POST `/tickets/{id}/checkin` (auth)
- REST (auth, limited): GET `/tickets`, GET `/tickets/{id}`

## Coupons
- POST `/coupons/validate` (auth)
- REST (auth): `/coupons`

Notes:
- All responses are JSON.
- Errors follow standard Laravel JSON error structure.
