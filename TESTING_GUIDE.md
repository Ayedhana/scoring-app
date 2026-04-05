# Frontend-Backend Integration Testing Guide

## Overview
You now have a complete Vue.js + Laravel API setup with JWT authentication.

## Architecture

```
Vue.js Frontend (http://localhost:5173)
        ↓
  Axios Service (api.js)
        ↓
   JWT Bearer Token
        ↓
  Laravel API (http://localhost:8000/api/v1)
        ├── POST /login → Get JWT token
        ├── GET /clients → List all clients
        ├── GET /clients/{id} → Get client details
        ├── GET /clients/{id}/scores → Get client scores
        └── POST /scores/calculate → Calculate score
```

## Prerequisites

**Backend:**
- Laravel running on `http://localhost:8000`
- Database seeded with test users
- JWT configured

**Frontend:**
- Vue.js running on `http://localhost:5173` (Vite dev server)
- Axios installed
- Services configured

## Step 1: Start the Servers

### Terminal 1 - Laravel Backend
```bash
cd c:\Users\USER\scoring-backend
php artisan serve
# Output: Laravel development server started: http://127.0.0.1:8000
```

### Terminal 2 - Vue.js Frontend
```bash
cd c:\Users\USER\scoring-backend\scoring_frontend
npm install  # If not done yet
npm run dev
# Output: Local: http://localhost:5173/
```

## Step 2: Seed Test User (If needed)

```bash
cd c:\Users\USER\scoring-backend
php artisan db:seed
# Or create user manually:
php artisan tinker
>>> User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => bcrypt('password')])
```

## Step 3: Test with Postman

### 3.1 Test Login Endpoint

**Request:**
```
POST http://localhost:8000/api/v1/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password"
}
```

**Expected Response (200):**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com"
  }
}
```

**Troubleshooting:**
- 401 Unauthorized → Check email/password
- 500 Server Error → Check `php artisan log` for JWT errors
- CORS error → Verify .env has correct VITE_API_URL

### 3.2 Test Clients Endpoint (With Token)

**Request:**
```
GET http://localhost:8000/api/v1/clients
Authorization: Bearer {access_token}
```

**Expected Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "numero_client": "CLI-001",
      "nom": "Ali",
      "prenom": "Ben Salah",
      "email": "ali@example.com",
      "balance": 5000.00,
      "status": "active"
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

**Troubleshooting:**
- 401 Unauthorized → Token missing or invalid
- 403 Forbidden → User lacks permission (check roles)
- 500 Error → Check database connection in .env

### 3.3 Test Score Endpoint

**Request:**
```
GET http://localhost:8000/api/v1/clients/1/scores
Authorization: Bearer {access_token}
```

**Expected Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "client_id": 1,
      "score": 750,
      "rating": "A",
      "calculated_at": "2026-03-30T10:00:00Z"
    }
  ]
}
```

## Step 4: Test Frontend

### 4.1 Test Login Page
1. Open `http://localhost:5173` in browser
2. You'll be redirected to `/login` (auth guard)
3. Enter credentials:
   - Email: `test@example.com`
   - Password: `password`
4. Click **Login**

**Browser DevTools (F12) → Network Tab:**
Check that:
- ✓ POST request to `/api/v1/login` is sent
- ✓ Response has `access_token` in JSON
- ✓ Token is stored in localStorage (F12 → Application → localStorage)

### 4.2 Test Dashboard
After login, you should see:
- ✓ List of clients from API
- ✓ Client count, active count stats
- ✓ Search/filter functionality
- ✓ "View Details" button for each client

**Browser DevTools (F12) → Network Tab:**
Check that:
- ✓ GET request to `/api/v1/clients` is sent
- ✓ `Authorization: Bearer {token}` header is present
- ✓ Response status is 200 with client data

### 4.3 Test Logout
- Click **Logout** button
- ✓ Token removed from localStorage
- ✓ Redirected to `/login`
- ✓ Trying to access `/` redirects to login again

## Step 5: Debug CORS Issues

### Symptoms
```
Access to XMLHttpRequest at 'http://localhost:8000/api/v1/login'
from origin 'http://localhost:5173' has been blocked by CORS policy
```

### Solutions

1. **Check .env on Frontend**
   ```
   // .env.local
   VITE_API_URL=http://localhost:8000/api/v1
   ```

2. **Check CORS Middleware in Laravel**
   ```php
   // config/cors.php
   'allowed_origins' => [
       'http://localhost:5173',
   ],
   ```

3. **Verify Options Request**
   In Network tab, look for OPTIONS request (preflight):
   - Should return 200 OK
   - Should have `Access-Control-Allow-Origin: http://localhost:5173`
   - Should have `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`

4. **Clear Browser Cache**
   - F12 → Application → Clear all
   - Hard refresh: `Ctrl+Shift+R`

## Step 6: Debug Auth Issues

### 401 Unauthorized on /api/v1/clients

**Check Token Storage:**
```javascript
// In browser console (F12)
localStorage.getItem('access_token')
// Should return JWT token, not null
```

**Verify Interceptor:**
```javascript
// In api.js, token is added in request interceptor:
config.headers.Authorization = `Bearer ${token}`
```

**Check JWT Config in Laravel:**
```bash
# In Laravel CLI
php artisan config:cache
php artisan jwt:secret
```

**Verify auth.php:**
```php
'api' => [
    'driver' => 'jwt',
    'provider' => 'users',
],
```

## Step 7: Full Test Flow

**Scenario: Login → Fetch Clients → View Details → Calculate Score**

1. **Frontend:**
   - Login page loads
   - User enters credentials
   - Vue calls `api.post('/login')` 

2. **API Response:**
   - Axios interceptor captures token
   - Token stored in localStorage
   - Redirected to Dashboard

3. **Dashboard:**
   - Vue calls `api.get('/clients')`
   - Interceptor adds: `Authorization: Bearer {token}`
   - Displays clients list

4. **Client Details:**
   - Click "View Details" → Router navigates to `/clients/:id`
   - Component calls `api.get('/clients/1/scores')`
   - Displays client scores

5. **Error Handling:**
   - If 401 → Auto logout, redirect to login
   - If 500 → Display error message
   - If network error → Show connection error

## Step 8: Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| CORS blocked | Frontend URL not in allowed_origins | Update config/cors.php |
| 401 on API | Missing/invalid token | Check localStorage, re-login |
| No clients shown | Database empty | Seed database with clients |
| Login fails | Wrong credentials | Check user table, create test user |
| 500 error | Database connection | Check .env DB_* variables |
| Token doesn't refresh | JWT expired | Token auto-refresh in interceptor |

## Step 9: Production Checklist

Before deploying to production:

- [ ] Change `JWT_SECRET` in .env to strong random value
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Update `allowed_origins` in cors.php with real frontend URL
- [ ] Use HTTPS for all API calls
- [ ] Add rate limiting to login endpoint
- [ ] Implement refresh token rotation
- [ ] Add CSRF protection for web routes
- [ ] Test on actual server (not localhost)

## Code Reference

### API Service (services/api.js)
```javascript
import api from '@/services/api'

// Automatic token attachment & error handling
api.get('/clients')
api.post('/login', credentials)
```

### Auth Composable (composables/useAuth.js)
```javascript
const { login, logout, isAuthenticated, user } = useAuth()

await login(email, password)
await logout()
```

### Client Service (services/clientService.js)
```javascript
const { data } = await clientService.getClients()
const { data } = await clientService.getClient(id)
const { data } = await scoreService.calculateScore(data)
```

## Support

For detailed logs:
- **Frontend:** F12 → Console & Network tabs
- **Backend:** `tail -f storage/logs/laravel.log`
- **Database:** Check Oracle connection

