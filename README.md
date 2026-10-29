# MOTOVEX — Premium Motorcycle Group

## Project Overview

MOTOVEX is a multi-brand motorcycle group digital platform developed as a technical assessment for Almana Group. It features a high-performance public website and a scoped administration portal (CMS) for managing brands, motorcycles, promotions, and customer enquiries.

## Technology Stack

- **Backend**: PHP 8.2 / Laravel 12 (Core application logic and RBAC)
- **Frontend**: Tailwind CSS v4 (Modern, utility-first styling)
- **Templating**: Blade (Server-side rendering for SEO and performance)
- **Database**: MySQL (Relational data for brands, models, and users)
- **Authentication**: Manual Laravel Auth (Customized login/register flow)
- **Asset Bundling**: Vite (Fast development and optimized production builds)

## Technical Approach

1.  **Service-Oriented Design**: While small in scope, the project follows clean code practices with dedicated Policies for authorization and Request classes for validation.
2.  **Role-Based Access Control (RBAC)**: A robust multi-role system (Admin, Data Entry, Sales Agent, Customer) ensures that staff see only what they are authorized to manage, particularly brand-scoped content for Data Entry users.
3.  **Modern UI/UX**: The design follows a modern, clean, and elegant aesthetic inspired by the **RideAxis** template, featuring a light theme with salmon pink accents (`#ff9aa9`), optimized for both readability and visual impact.
4.  **Database Integrity**: Relational constraints and atomic transactions (`DB::transaction`) are used to maintain data consistency during complex operations like user registration.

## Database Schema (ERD)

```mermaid
erDiagram
    USERS ||--o{ USER_ROLES : has
    ROLES ||--o{ USER_ROLES : defines
    BRANDS ||--o{ MOTORCYCLES : contains
    BRANDS ||--o{ PROMOTIONS : offers
    BRANDS ||--o{ SHOWROOMS : manages
    USERS ||--o{ BRAND_USER : assigned_to (Data Entry)
    BRANDS ||--o{ BRAND_USER : assigned_by
    USERS ||--o{ ENQUIRIES : sends
```

- **Users**: Extended with profile fields, account status, and brand preferences.
- **Roles**: Admin (Global), Data Entry (Brand-scoped), Sales Agent (Enquiries), Customer (Public).
- **Motorcycles**: Technical specs, market segments (Urban, Adventure, Performance, Premium), and featured status.
- **Promotions & Showrooms**: Scoped content to demonstrate CMS capabilities.

## Setup Instructions

1.  **Clone the repository**
2.  **Install dependencies**:
    ```bash
    composer install
    npm install
    ```
3.  **Environment configuration**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  **Database setup**:
    ```bash
    php artisan migrate:fresh --seed
    ```
5.  **Build assets**:
    ```bash
    npm run build
    ```
6.  **Run the server**:
    ```bash
    php artisan serve
    ```

## Demo Credentials

| Role | Email | Password | Scope |
|------|-------|----------|-------|
| Admin | `admin@motovex.com` | `password123` | All brands & users |
| Dealer (Data Entry) | `vexora@motovex.com` | `password123` | Vexora only |
| Sales Agent | `sales@motovex.com` | `password123` | Enquiries only |
| Customer | `customer@motovex.com` | `password123` | Own profile & enquiries |

## Assumptions & Decisions

- **Public Registration**: While usually private, the registration form allows selecting roles for demonstration purposes. In production, Admin/Staff accounts would be invite-only.
- **Financing & Service**: These are represented through the `Promotions` and `Services` sections to demonstrate content management without over-engineering complex financial calculators.
- **Market Segments**: Categories were aligned with the PDF (Urban Mobility, Adventure & Touring, Performance, Premium) to show attention to business requirements.
- **Brand Assignment**: Data Entry users can only manage motorcycles, promotions, and showrooms for brands explicitly assigned to them in the Admin portal.

## Roles and Permissions

Roles are stored in a `roles` table and linked to users through `user_roles`. Server-side authorization is enforced with Policies (`UserPolicy`, `BrandPolicy`, `MotorcyclePolicy`, `EnquiryPolicy`) plus middleware (`role`, `brand.access`, `active`).

### Admin

- Full dashboard access
- Create / edit / delete brands (delete blocked while motorcycles are linked)
- Manage all motorcycles
- Manage users and roles (create staff, change roles, suspend / reactivate accounts)
- Assign brands to Data Entry users
- View, update, and delete enquiries
- View global statistics

### Data Entry

- Dashboard access limited to assigned brands
- Create / edit / delete motorcycles only for assigned brands
- Read-only brand visibility for assigned brands
- Cannot manage users, roles, brand assignments, or enquiries
- Unauthorized brand IDs submitted in forms are rejected server-side

### Sales Agent

- Dashboard access focused on enquiries
- View and update enquiries, but cannot delete them
- Cannot manage brands, motorcycles, or users

### Customer

- Reaches the `/account` area instead of the admin dashboard
- Views and updates their own profile
- Views their own enquiries (matched by email) and changes their password
- Cannot access any admin route

### Account states

- Accounts with `is_active = false` are blocked at login **and** signed out by the `active` middleware on subsequent requests
- The last remaining active admin cannot be suspended, demoted, or deleted
- Users cannot suspend or delete their own account

Brand assignment uses the `brand_user` pivot. Removing an assignment revokes access on the next request.

---

## Database

### Main tables

- `users` — all accounts (staff and customers) with profile fields (`phone`, `country`, `city`, `preferred_brand_id`, `is_active`, `newsletter_opted_in`)
- `roles` / `user_roles` — role membership (admin, data_entry, sales_agent, customer)
- `brands` — motorcycle brands (`is_published`)
- `motorcycles` — catalogue models with specs, price, featured flag
- `brand_user` — Data Entry brand assignments
- `enquiries` — public contact submissions

---

## Public Website

| Route | Purpose |
|-------|---------|
| `/` | Premium homepage (RideAxis Style) |
| `/brands` | Published brands + search |
| `/brands/{slug}` | Brand details |
| `/motorcycles` | Catalogue filters + sort |
| `/motorcycles/{slug}` | Motorcycle details |
| `/services` | Services overview |
| `/contact` | Enquiry form |
| `/register` | Customer registration |
| `/login` | Sign in (staff and customers) |
| `/cart` | Shopping cart overview |
| `/cart/checkout` | Checkout and order placement |

Unpublished brands/motorcycles return 404 on public routes.

---

## Admin Routes

Prefix: `/admin` (auth + active + role:admin,data_entry,sales_agent)

- Overview / Dashboard (stat cards adapt to the viewer's role)
- Brands and Motorcycles (admin + data_entry)
- Promotions and Showrooms (admin + data_entry)
- Users & Roles (admin only) — role filter, suspend / reactivate
- Enquiries (admin + sales_agent; delete is admin-only)
- Brand assignment UI

---

## Testing

```bash
php artisan test
```

Covered areas:
- Guest protection and login/logout
- Customer registration, login routing, and password reset
- Suspended accounts blocked at login and by middleware
- Admin CRUD and enquiry access
- Sales Agent enquiry access without delete rights
- Data Entry brand scoping
- Unauthorized brand writes rejected
- Assignment revocation
- User management: role filter, suspend / reactivate, last-admin protection
- Customer account area: profile and password updates
- Public published-only visibility
- Contact validation and storage
- **NEW**: Promotions, Showrooms, and Cart functionality testing

---

## Images and Attribution

- **UI Design Inspiration**: The visual identity, layout, and components are inspired by the [RideAxis - Motorcycle Shop Template](https://uiparadox.co.uk/templates/rideaxis/) by UIPARADOX.
- **Photography**: Hero and catalogue imagery uses freely available Unsplash photography.
- **Icons**: Technical and social icons are provided by FontAwesome 6.
- **Fonts**: Inter and standard sans-serif stacks for maximum readability.

---

## Design Decisions

- **Color Palette**: Shifted from a dark stealth theme to the **RideAxis Light Theme**:
  - Primary Background: `#ffffff`
  - Accent Color: `#ff9aa9` (Salmon Pink)
  - Dark Sections: `#0c0c0d`
  - Text: `#1e1f20`
- **Animations**: Subtle `reveal-on-scroll` effects using `IntersectionObserver` to enhance the premium feel.
- **Consistency**: The same visual language is maintained across the homepage, product details, and footer.

---

## Deployment Notes

1. Set production `.env` (`APP_ENV=production`, `APP_DEBUG=false`, strong `APP_KEY`)
2. Point MySQL credentials to the production database
3. Run `php artisan migrate --force`
4. Seed only if a disposable demo database is intended
5. Run `npm ci && npm run build`
6. Configure the web server document root to `/public`
7. Ensure `storage` and `bootstrap/cache` are writable
8. Never commit real secrets or demo passwords into production configs
