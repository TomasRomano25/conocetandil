# Conoce Tandil — Project Bible

> **Last updated:** 2026-02-18 (Analytics module added — GA4, GTM, dashboard)
> **Purpose:** Single source of truth for the entire project. Share this file with developers or AI assistants to provide full context.

---

## Table of Contents

1. [Overview](#1-overview)
2. [Tech Stack](#2-tech-stack)
3. [Project Structure](#3-project-structure)
4. [Database Schema](#4-database-schema)
5. [Routes](#5-routes)
6. [Models](#6-models)
7. [Controllers](#7-controllers)
8. [Middleware](#8-middleware)
9. [Views & Layouts](#9-views--layouts)
10. [Frontend Assets](#10-frontend-assets)
11. [Authentication](#11-authentication)
12. [Admin Panel](#12-admin-panel)
13. [Public Pages](#13-public-pages)
14. [Image System](#14-image-system)
15. [Backup System](#15-backup-system)
16. [Messaging & Forms System](#16-messaging--forms-system)
17. [Premium Experience Module](#17-premium-experience-module)
18. [Ecommerce & Membership Checkout](#18-ecommerce--membership-checkout)
19. [Seeders & Sample Data](#19-seeders--sample-data)
20. [Design System](#20-design-system)
21. [Development Setup](#21-development-setup)
22. [Analytics Module](#22-analytics-module)
22. [Known Limitations & Pending Work](#22-known-limitations--pending-work)

---

## 1. Overview

**Conoce Tandil** is a tourism website for the city of Tandil, Argentina. Visitors can browse places to visit (lugares), read guides, and make contact. An admin panel allows content managers to create/edit/delete places, manage users, customize the homepage, control the navigation menu, and manage the system.

**Key Capabilities:**
- Public place browsing with premium detail pages (Airbnb-style gallery, sticky info card, map, ratings, promotions)
- Full-text search + category filter on the places listing
- Admin CRUD for places with multi-image gallery management
- Drag-and-drop homepage section editor with hero image upload
- Admin-controlled navigation menu (show/hide/reorder/rename items)
- User management with admin roles
- Automated SQLite database backups with configurable interval and change detection
- Dynamic form system — contact form rendered from DB; admin can toggle fields visible/required/reorder
- Admin inbox (Mensajes) for all submitted form messages with per-form filtering and read/unread tracking
- SMTP email configuration stored in DB; sends notification emails on form submission
- Mobile-optimized with touch carousel, sticky CTA, collapsible content
- **Premium Experience Module** — membership-gated itinerary planner with day-by-day timelines, contextual editorial notes, and admin CRUD for itineraries
- **Ecommerce & Membership Checkout** — plan selection, bank transfer checkout, order management with admin approval granting Premium access
- **User Registration & Password Reset** — public registration, forgot password flow using dynamic SMTP config

---

## 2. Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 12.0 |
| PHP | PHP | 8.2+ |
| Database | SQLite | — |
| CSS | Tailwind CSS | 4.0 |
| Build Tool | Vite | 7.0 |
| JS Libraries | Axios, SortableJS | 1.11, 1.15 |
| Auth | Custom (no Breeze/Jetstream) | — |

**composer.json key packages:** `laravel/framework`, `laravel/tinker`
**package.json key packages:** `tailwindcss`, `@tailwindcss/vite`, `axios`, `sortablejs`, `concurrently`

---

## 3. Project Structure

```
app/
├── Console/
│   └── Commands/
│       └── BackupDatabase.php          # Artisan command: db:backup
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login/logout/register/password reset
│   │   ├── PageController.php          # Public pages (with search/filter)
│   │   ├── MessageController.php       # Public form submission (POST /formulario/{slug})
│   │   ├── MembershipController.php    # Plan listing, checkout, order confirmation
│   │   ├── PremiumController.php       # /premium gateway + hub + planner + itinerario
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── LugarController.php     # Places CRUD
│   │       ├── UserController.php      # User management + manual premium grant/revoke
│   │       ├── InicioSectionController.php # Homepage editor + hero image
│   │       ├── NavItemController.php   # Nav menu control
│   │       ├── ConfigurationController.php # Site settings + backups + SMTP + payment
│   │       ├── MessageController.php   # Admin inbox (list, show, mark read, delete)
│   │       ├── FormController.php      # Form + field management
│   │       ├── ItineraryController.php # Premium itinerary CRUD + item editor
│   │       ├── OrderController.php     # Order list, detail, complete, cancel
│   │       └── MembershipPlanController.php # Plan CRUD
│   └── Middleware/
│       ├── AdminMiddleware.php
│       └── PremiumMiddleware.php
├── Mail/
│   └── NewMessageNotification.php      # Email sent on form submission
├── Models/
│   ├── User.php
│   ├── Lugar.php
│   ├── LugarImage.php
│   ├── InicioSection.php
│   ├── NavItem.php                     # Navigation menu items
│   ├── Configuration.php              # Key-value site settings
│   ├── Form.php                        # Form definitions
│   ├── FormField.php                   # Per-form field definitions
│   ├── Message.php                     # Submitted form messages
│   ├── Itinerary.php                   # Premium itinerary with filter scope
│   ├── ItineraryItem.php               # Individual timeline activity
│   ├── MembershipPlan.php              # Membership plan (price, duration, features)
│   └── Order.php                       # Purchase order (pending/completed/cancelled)

database/
├── migrations/
│   ├── ...create_users_table.php
│   ├── ...add_is_admin_to_users_table.php
│   ├── ...create_lugares_table.php
│   ├── ...create_inicio_sections_table.php
│   ├── ...create_lugar_images_table.php
│   ├── ...add_detail_fields_to_lugares_table.php
│   ├── 2026_02_17_000001_create_nav_items_table.php
│   ├── 2026_02_17_000002_add_image_to_inicio_sections_table.php
│   ├── 2026_02_17_000003_create_configurations_table.php
│   ├── 2026_02_18_000001_create_forms_table.php
│   ├── 2026_02_18_000002_create_form_fields_table.php
│   ├── 2026_02_18_000003_create_messages_table.php
│   ├── 2026_02_18_100001_add_premium_to_users_table.php
│   ├── 2026_02_18_100002_create_itineraries_table.php
│   ├── 2026_02_18_100003_create_itinerary_items_table.php
│   ├── 2026_02_18_200001_create_membership_plans_table.php
│   └── 2026_02_18_200002_create_orders_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── AdminUserSeeder.php
│   ├── LugarSeeder.php
│   ├── InicioSectionSeeder.php
│   ├── FormSeeder.php                  # Default "Contacto" form with 4 fields
│   └── MembershipPlanSeeder.php        # 4 default plans (1/3/6/12 months)

resources/
├── css/app.css
├── js/
│   ├── app.js
│   └── bootstrap.js
└── views/
    ├── layouts/
    │   ├── app.blade.php               # Public layout — dynamic nav from DB
    │   └── admin.blade.php             # Admin layout with sidebar
    ├── auth/
    │   ├── login.blade.php
    │   ├── register.blade.php
    │   ├── forgot-password.blade.php
    │   └── reset-password.blade.php
    ├── membership/
    │   ├── planes.blade.php            # Plan selection grid
    │   ├── checkout.blade.php          # Bank transfer checkout + order summary
    │   └── confirmacion.blade.php      # Order confirmation + next steps
    ├── pages/
    │   ├── inicio.blade.php
    │   ├── lugares.blade.php           # Search + category filter
    │   ├── lugar.blade.php             # Premium place detail page
    │   ├── guias.blade.php
    │   ├── contacto.blade.php
    │   └── sections/
    │       ├── hero.blade.php          # Supports background image
    │       ├── featured.blade.php
    │       ├── banner.blade.php
    │       ├── cta_guias.blade.php
    │       └── cta_contacto.blade.php
    ├── emails/
    │   └── new-message.blade.php       # HTML email template for form notifications
    ├── premium/
    │   ├── upsell.blade.php            # Elegant upsell for non-premium users
    │   ├── planner.blade.php           # Planning questionnaire
    │   ├── resultados.blade.php        # Matched itinerary cards
    │   └── itinerario.blade.php        # Full day-by-day timeline view
    └── admin/
        ├── dashboard.blade.php
        ├── lugares/ (index, create, edit)
        ├── usuarios/ (index, create, edit)    # Includes premium management
        ├── itinerarios/ (index, create, edit, _form, items)
        ├── inicio/
        │   └── index.blade.php         # Sections editor + hero image upload
        ├── nav/
        │   └── index.blade.php         # Nav menu editor
        ├── configuraciones/
        │   └── index.blade.php         # SMTP + backup + payment method settings
        ├── mensajes/
        │   ├── index.blade.php         # Message grid with form/read filters
        │   └── show.blade.php          # Individual message view
        ├── formularios/
        │   ├── index.blade.php         # Form settings editor
        │   └── campos.blade.php        # Field editor with drag-and-drop reorder
        ├── pedidos/
        │   ├── index.blade.php         # Order list with status filter tabs
        │   └── show.blade.php          # Order detail + complete/cancel actions
        └── planes/
            └── index.blade.php         # Membership plan list + inline edit + create

routes/
├── web.php                             # All HTTP routes
└── console.php                         # Artisan commands + scheduler

storage/app/
├── public/
│   ├── lugares/                        # Place images (symlinked)
│   └── hero/                           # Hero banner images (symlinked)
└── backups/                            # SQLite backup files (NOT public)
```

---

## 4. Database Schema

### `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | |
| email | string | unique |
| password | string | hashed |
| is_admin | boolean | default: false |
| premium_expires_at | timestamp | nullable — null means no premium |
| timestamps | | |

### `lugares`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| title | string | required |
| direction | string | required (address) |
| description | text | required |
| image | string | nullable, legacy main image |
| featured | boolean | default: false |
| order | integer | default: 0 |
| category | string(100) | nullable |
| rating | decimal(2,1) | nullable, 0.0–5.0 |
| phone | string(20) | nullable |
| website | string(255) | nullable |
| opening_hours | string(255) | nullable |
| promotion_title | string(150) | nullable |
| promotion_description | text | nullable |
| promotion_url | string(255) | nullable |
| latitude | decimal(10,7) | nullable |
| longitude | decimal(10,7) | nullable |
| timestamps | | |

### `lugar_images`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| lugar_id | foreignId | cascadeOnDelete |
| path | string | storage path |
| order | integer | default: 0 |
| timestamps | | |

### `inicio_sections`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| key | string | unique — hero, banner, featured, cta_guias, cta_contacto |
| title | string | |
| subtitle | string | nullable |
| content | text | nullable |
| image | string | nullable — hero background image path |
| order | integer | default: 0 |
| is_visible | boolean | default: true |
| timestamps | | |

### `nav_items`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| key | string(50) | unique — inicio, lugares, guias, contacto |
| label | string(100) | display text shown in menu |
| route_name | string(100) | Laravel route name |
| order | integer | default: 0 |
| is_visible | boolean | default: true |
| timestamps | | |

**Seeded with:** Inicio (order 1), Lugares (2), Guías (3), Contacto (4) — all visible.

### `configurations`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| key | string(100) | unique |
| value | text | nullable |
| timestamps | | |

**Backup keys:**
| Key | Default | Description |
|-----|---------|-------------|
| backup_enabled | 1 | Toggle automatic backups |
| backup_interval_hours | 1 | Hours between backup runs |
| backup_keep_count | 10 | Number of backup files to retain |
| backup_last_run | null | ISO timestamp of last run |
| backup_latest_file | null | Filename of most recent backup |

**SMTP keys (set via admin Configuraciones page):**
| Key | Description |
|-----|-------------|
| smtp_host | SMTP server hostname |
| smtp_port | SMTP port (default 587) |
| smtp_encryption | tls / ssl / starttls / empty |
| smtp_username | SMTP login username |
| smtp_password | SMTP login password |
| smtp_from_email | Sender email address |
| smtp_from_name | Sender display name |

### `forms`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | Human-readable form name |
| slug | string | unique — used in URL and route |
| description | string | nullable |
| active | boolean | default: true |
| send_notification | boolean | Send email on submission, default: true |
| notification_email | string | nullable — falls back to smtp_from_email |
| timestamps | | |

### `form_fields`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| form_id | foreignId | cascadeOnDelete |
| name | string | Internal key stored in message data JSON |
| label | string | Display label shown to users |
| type | string | text, email, tel, textarea, select |
| placeholder | string | nullable |
| required | boolean | default: false |
| visible | boolean | default: true — hides from public form |
| sort_order | integer | default: 0 |
| options | text | nullable JSON array — for select type |
| timestamps | | |

### `messages`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| form_id | foreignId | cascadeOnDelete |
| data | json | Map of field name → submitted value |
| is_read | boolean | default: false |
| ip_address | string(45) | nullable |
| timestamps | | |

### `itineraries`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| title | string | |
| slug | string | unique — used in URL |
| description | text | nullable |
| intro_tip | text | nullable — editorial tip shown at top of detail page |
| days_min | integer | minimum days this itinerary fits |
| days_max | integer | maximum days this itinerary fits |
| type | string | nature / gastronomy / adventure / relax / mixed |
| season | string | summer / winter / all |
| requires_car | boolean | default: false |
| kid_friendly | boolean | default: true |
| active | boolean | default: true |
| sort_order | integer | default: 0 |
| cover_image | string | nullable — path to cover photo |
| timestamps | | |

### `itinerary_items`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| itinerary_id | foreignId | cascadeOnDelete |
| lugar_id | foreignId | nullable, references lugares.id, nullOnDelete |
| day | integer | day number (1, 2, 3…) |
| time_block | string | morning / lunch / afternoon / evening / flexible |
| sort_order | integer | display order within the day+block |
| custom_title | string | nullable — overrides lugar title |
| duration_minutes | integer | nullable — estimated time |
| estimated_cost | string | nullable — e.g. "Gratis" or "$500–$1000" |
| why_order | text | nullable — "Ideal ir temprano porque…" |
| contextual_notes | text | nullable — weather tips, warnings |
| skip_if | text | nullable — "Saltá esto si no tenés auto" |
| why_worth_it | text | nullable — "Vale la pena porque…" |
| timestamps | | |

### `membership_plans`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | Display name (e.g. "1 Mes") |
| slug | string | unique — used in URL (e.g. "1-mes") |
| description | text | nullable |
| price | decimal(10,2) | Price in ARS |
| duration_months | integer | How many months of premium access |
| features | json | nullable — array of feature strings for plan card |
| active | boolean | default: true — controls visibility on /premium/planes |
| sort_order | integer | default: 0 |
| timestamps | | |

### `orders`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | foreignId | cascadeOnDelete |
| plan_id | foreignId | references membership_plans.id, cascadeOnDelete |
| status | string | pending / completed / cancelled |
| total | decimal(10,2) | Price at time of order |
| transfer_reference | string | nullable — comprobante number entered by user |
| admin_notes | string | nullable — internal admin note |
| completed_at | timestamp | nullable — set when marked complete |
| timestamps | | |

### System tables
`sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `password_reset_tokens` — standard Laravel.

---

## 5. Routes

### Public
| Method | URI | Controller@action | Name |
|--------|-----|-------------------|------|
| GET | `/` | PageController@inicio | inicio |
| GET | `/lugares` | PageController@lugares | lugares |
| GET | `/lugares/{lugar}` | PageController@lugar | lugar.show |
| GET | `/guias` | PageController@guias | guias |
| GET | `/contacto` | PageController@contacto | contacto |
| POST | `/formulario/{slug}` | MessageController@store | formulario.submit |
| GET | `/premium` | PremiumController@index | premium.upsell |

`/lugares` accepts query params: `?q=search+term&category=Naturaleza`

### Auth (guest middleware)
| Method | URI | Controller@action | Name |
|--------|-----|-------------------|------|
| GET | `/register` | AuthController@showRegister | register |
| POST | `/register` | AuthController@register | — |
| GET | `/forgot-password` | AuthController@showForgotPassword | password.request |
| POST | `/forgot-password` | AuthController@sendResetLink | password.email |
| GET | `/reset-password/{token}` | AuthController@showResetPassword | password.reset |
| POST | `/reset-password` | AuthController@resetPassword | password.update |

### Membership (public + auth)
| Method | URI | Controller@action | Name |
|--------|-----|-------------------|------|
| GET | `/premium/planes` | MembershipController@planes | membership.planes |
| GET | `/premium/checkout/{plan:slug}` | MembershipController@checkout | membership.checkout |
| POST | `/premium/checkout/{plan:slug}` | MembershipController@store | membership.store |
| GET | `/premium/pedido/{order}` | MembershipController@confirmacion | membership.confirmacion |

### Premium (middleware: auth + premium)
| Method | URI | Controller@action | Name |
|--------|-----|-------------------|------|
| GET | `/premium/panel` | PremiumController@hub | premium.hub |
| GET | `/premium/planificar` | PremiumController@planner | premium.planner |
| GET | `/premium/resultados` | PremiumController@resultados | premium.resultados |
| GET | `/premium/itinerario/{itinerary:slug}` | PremiumController@show | premium.show |

`/premium/resultados` accepts GET params: `days`, `type`, `season`, `kids`, `car`

### Auth
| Method | URI | Controller@action | Name |
|--------|-----|-------------------|------|
| GET | `/login` | AuthController@showLogin | login |
| POST | `/login` | AuthController@login | — |
| POST | `/logout` | AuthController@logout | logout |

### Admin (prefix: `/admin`, middleware: auth + admin)
| Method | URI | Controller@action | Name |
|--------|-----|-------------------|------|
| GET | `/admin` | DashboardController@index | admin.dashboard |
| RESOURCE | `/admin/lugares` | LugarController | admin.lugares.* |
| RESOURCE | `/admin/usuarios` (no show) | UserController | admin.usuarios.* |
| GET | `/admin/inicio` | InicioSectionController@index | admin.inicio.index |
| POST | `/admin/inicio/reorder` | InicioSectionController@reorder | admin.inicio.reorder |
| POST | `/admin/inicio/hero-image` | InicioSectionController@updateHeroImage | admin.inicio.hero-image |
| DELETE | `/admin/inicio/hero-image` | InicioSectionController@deleteHeroImage | admin.inicio.hero-image.delete |
| PUT | `/admin/inicio/{inicioSection}` | InicioSectionController@update | admin.inicio.update |
| GET | `/admin/nav` | NavItemController@index | admin.nav.index |
| POST | `/admin/nav/reorder` | NavItemController@reorder | admin.nav.reorder |
| PUT | `/admin/nav/{navItem}` | NavItemController@update | admin.nav.update |
| GET | `/admin/configuraciones` | ConfigurationController@index | admin.configuraciones.index |
| POST | `/admin/configuraciones/backup` | ConfigurationController@updateBackup | admin.configuraciones.backup.update |
| POST | `/admin/configuraciones/backup/run` | ConfigurationController@runBackup | admin.configuraciones.backup.run |
| GET | `/admin/configuraciones/backup/download` | ConfigurationController@downloadBackup | admin.configuraciones.backup.download |
| POST | `/admin/configuraciones/smtp` | ConfigurationController@updateSmtp | admin.configuraciones.smtp.update |
| GET | `/admin/mensajes` | Admin\MessageController@index | admin.mensajes.index |
| GET | `/admin/mensajes/{mensaje}` | Admin\MessageController@show | admin.mensajes.show |
| POST | `/admin/mensajes/{mensaje}/read` | Admin\MessageController@markRead | admin.mensajes.read |
| POST | `/admin/mensajes/{mensaje}/unread` | Admin\MessageController@markUnread | admin.mensajes.unread |
| DELETE | `/admin/mensajes/{mensaje}` | Admin\MessageController@destroy | admin.mensajes.destroy |
| GET | `/admin/formularios` | FormController@index | admin.formularios.index |
| PUT | `/admin/formularios/{formulario}` | FormController@updateForm | admin.formularios.update |
| GET | `/admin/formularios/{formulario}/campos` | FormController@campos | admin.formularios.campos |
| PUT | `/admin/formularios/{formulario}/campos/{campo}` | FormController@updateField | admin.formularios.campos.update |
| POST | `/admin/formularios/{formulario}/campos/reorder` | FormController@reorderFields | admin.formularios.campos.reorder |
| GET | `/admin/itinerarios` | ItineraryController@index | admin.itinerarios.index |
| GET | `/admin/itinerarios/create` | ItineraryController@create | admin.itinerarios.create |
| POST | `/admin/itinerarios` | ItineraryController@store | admin.itinerarios.store |
| GET | `/admin/itinerarios/{id}/edit` | ItineraryController@edit | admin.itinerarios.edit |
| PUT | `/admin/itinerarios/{id}` | ItineraryController@update | admin.itinerarios.update |
| DELETE | `/admin/itinerarios/{id}` | ItineraryController@destroy | admin.itinerarios.destroy |
| GET | `/admin/itinerarios/{id}/items` | ItineraryController@items | admin.itinerarios.items |
| POST | `/admin/itinerarios/{id}/items` | ItineraryController@storeItem | admin.itinerarios.items.store |
| PUT | `/admin/itinerarios/{id}/items/{item}` | ItineraryController@updateItem | admin.itinerarios.items.update |
| DELETE | `/admin/itinerarios/{id}/items/{item}` | ItineraryController@destroyItem | admin.itinerarios.items.destroy |
| POST | `/admin/usuarios/{usuario}/premium/grant` | UserController@grantPremium | admin.usuarios.premium.grant |
| POST | `/admin/usuarios/{usuario}/premium/revoke` | UserController@revokePremium | admin.usuarios.premium.revoke |
| GET | `/admin/pedidos` | OrderController@index | admin.pedidos.index |
| GET | `/admin/pedidos/{order}` | OrderController@show | admin.pedidos.show |
| POST | `/admin/pedidos/{order}/completar` | OrderController@complete | admin.pedidos.complete |
| POST | `/admin/pedidos/{order}/cancelar` | OrderController@cancel | admin.pedidos.cancel |
| GET | `/admin/planes` | MembershipPlanController@index | admin.planes.index |
| POST | `/admin/planes` | MembershipPlanController@store | admin.planes.store |
| PUT | `/admin/planes/{plan}` | MembershipPlanController@update | admin.planes.update |
| DELETE | `/admin/planes/{plan}` | MembershipPlanController@destroy | admin.planes.destroy |
| POST | `/admin/configuraciones/payment` | ConfigurationController@updatePayment | admin.configuraciones.payment.update |

---

## 6. Models

### `User`
- **Fillable:** name, email, password, is_admin, premium_expires_at
- **Casts:** password (hashed), is_admin (boolean), premium_expires_at (datetime)
- **Methods:** `isAdmin(): bool`, `isPremium(): bool` — true if admin OR `premium_expires_at` is in the future

### `Lugar`
- **Fillable:** title, direction, description, image, featured, order, category, rating, phone, website, opening_hours, promotion_title, promotion_description, promotion_url, latitude, longitude
- **Casts:** featured (bool), order (int), rating (decimal:1), latitude/longitude (decimal:7)
- **Appends:** `cover_image`
- **Relationships:** `images()` → hasMany LugarImage (ordered by `order`)
- **Accessors:** `getCoverImageAttribute()`, `getGoogleMapsUrlAttribute()`, `getGoogleMapsDirectionsUrlAttribute()`
- **Methods:** `hasCoordinates(): bool`, `hasPromotion(): bool`
- **Scopes:** `featured()`, `ordered()`, `search(string $term)` — LIKE on title/direction/description

### `LugarImage`
- **Fillable:** lugar_id, path, order
- **Relationships:** `lugar()` → belongsTo Lugar

### `InicioSection`
- **Fillable:** key, title, subtitle, content, image, order, is_visible
- **Casts:** order (int), is_visible (bool)
- **Scopes:** `ordered()`, `visible()`

### `NavItem`
- **Fillable:** key, label, route_name, order, is_visible
- **Casts:** order (int), is_visible (bool)
- **Scopes:** `ordered()`, `visible()`

### `Configuration`
- **Fillable:** key, value
- **Static helpers:**
  - `Configuration::get(string $key, $default = null): mixed`
  - `Configuration::set(string $key, mixed $value): void` — upserts by key

### `Form`
- **Fillable:** name, slug, description, active, send_notification, notification_email
- **Casts:** active (bool), send_notification (bool)
- **Relationships:** `fields()` → hasMany FormField ordered by sort_order; `visibleFields()` → hasMany FormField where visible=true; `messages()` → hasMany Message

### `FormField`
- **Fillable:** form_id, name, label, type, placeholder, required, visible, sort_order, options
- **Casts:** required (bool), visible (bool), sort_order (int), options (array)
- **Relationships:** `form()` → belongsTo Form

### `Message`
- **Fillable:** form_id, data, is_read, ip_address
- **Casts:** data (array), is_read (bool)
- **Relationships:** `form()` → belongsTo Form
- **Helpers:** `getValue(string $name): ?string` — reads a value from the `data` JSON by field name

### `Itinerary`
- **Fillable:** title, slug, description, intro_tip, days_min, days_max, type, season, requires_car, kid_friendly, active, sort_order, cover_image
- **Casts:** requires_car, kid_friendly, active (bool); days_min, days_max, sort_order (int)
- **Relationships:** `items()` → hasMany ItineraryItem ordered by day then sort_order
- **Scopes:** `active()`, `ordered()`, `matchFilters($days, $type, $season, $kids, $car)` — filters by day range overlap, type (mixed matches all), season (all matches all), car requirement, kid_friendly
- **Methods:** `itemsByDay()` — returns items grouped by day number; `timeBlockLabel(string $block): string`; `timeBlockIcon(string $block): string`

### `ItineraryItem`
- **Fillable:** itinerary_id, lugar_id, day, time_block, sort_order, custom_title, duration_minutes, estimated_cost, why_order, contextual_notes, skip_if, why_worth_it
- **Casts:** day, sort_order, duration_minutes (int)
- **Relationships:** `itinerary()` → belongsTo Itinerary; `lugar()` → belongsTo Lugar
- **Methods:** `displayTitle(): string` — custom_title ?? lugar->title ?? '—'; `formattedDuration(): ?string` — "2h 30min" format

### `MembershipPlan`
- **Fillable:** name, slug, description, price, duration_months, features, active, sort_order
- **Casts:** price (decimal:2), duration_months, sort_order (int), features (array), active (bool)
- **Relationships:** `orders()` → hasMany Order
- **Methods:** `durationLabel(): string` — "1 mes" / "3 meses" / "1 año"; `formattedPrice(): string` — "$2.999"
- **Scopes:** `active()`, `ordered()`

### `Order`
- **Fillable:** user_id, plan_id, status, total, transfer_reference, admin_notes, completed_at
- **Casts:** total (decimal:2), completed_at (datetime)
- **Relationships:** `user()` → belongsTo User; `plan()` → belongsTo MembershipPlan
- **Methods:** `isPending/isCompleted/isCancelled(): bool`; `statusLabel(): string`; `statusColor(): string`
- **`complete()`** — extends user's `premium_expires_at` by `plan->duration_months` from current expiry (or now), sets status=completed, completed_at=now
- **`cancel()`** — sets status=cancelled

---

## 7. Controllers

### `AuthController`
- `showLogin()`, `login(Request)`, `logout(Request)`
- `showRegister()`, `register(Request)` — validates name/email/password, creates User, auto-login, redirect to `/premium`
- `showForgotPassword()`, `sendResetLink(Request)` — calls `applySmtpConfig()` then `Password::sendResetLink()`
- `showResetPassword(Request, string $token)`, `resetPassword(Request)` — uses Laravel `Password::reset()` facade, fires `PasswordReset` event
- Private `applySmtpConfig()` — reads all `smtp_*` Configuration keys and sets them via `config([...])`

### `MessageController` (public)
- `store(Request, string $slug)` — finds active Form by slug, validates fields dynamically (rules built from visible + required field config), saves Message, sends `NewMessageNotification` email (silent fail)

### `PageController`
- `inicio()` — loads featured lugares (6) + sections keyed by key
- `lugares()` — applies `?q` full-text search + `?category` filter; passes `$lugares`, `$categories`, `$q`, `$category` to view
- `lugar(Lugar)` — loads lugar with images + 6 random related
- `guias()` — static view
- `contacto()` — loads Form (slug=contacto, active=true) with visibleFields; passes `$form` to view

### `Admin\DashboardController`
- `index()` — lugar count + user count

### `Admin\LugarController`
- Full CRUD with image upload. Validates 20+ fields including lat/lng bounds and promotion fields.

### `Admin\UserController`
- CRUD (except show). Prevents self-deletion. Password optional on update.
- `grantPremium(Request, User)` — sets premium_expires_at based on duration (1month/3months/6months/1year/custom date); extends existing expiry if already premium
- `revokePremium(User)` — clears premium_expires_at

### `Admin\InicioSectionController`
- `index()` — all sections ordered
- `update(Request, InicioSection)` — title, subtitle, content, is_visible
- `reorder(Request)` — AJAX array of IDs → updates order
- `updateHeroImage(Request)` — validates image (max 4MB), deletes old, stores to `hero/` disk, saves path to hero section's `image` field
- `deleteHeroImage()` — deletes file + clears `image` field

### `Admin\NavItemController`
- `index()` — all nav items ordered
- `update(Request, NavItem)` — label + is_visible
- `reorder(Request)` — AJAX array of IDs → updates order

### `Admin\ConfigurationController`
- `index()` — reads all backup config + SMTP config, lists backup files, passes stats to view
- `updateBackup(Request)` — saves backup_enabled, backup_interval_hours, backup_keep_count
- `runBackup()` — calls `Artisan::call('db:backup', ['--force' => true])`
- `downloadBackup()` — streams latest backup file as download
- `updateSmtp(Request)` — saves smtp_* keys to configurations table (password only saved if non-empty)

### `Admin\MessageController`
- `index(Request)` — paginated message list, filterable by `form_id` and `is_read`; passes `$unreadCount` to view
- `show(Message)` — marks message read, loads form.fields relationship, renders detail view
- `markRead(Message)` / `markUnread(Message)` — toggle is_read
- `destroy(Message)` — hard delete

### `Admin\FormController`
- `index()` — all forms with messages_count
- `updateForm(Request, Form)` — saves form settings (name, description, active, send_notification, notification_email)
- `campos(Form)` — loads form.fields, renders field editor
- `updateField(Request, Form, FormField)` — saves label, placeholder, required, visible
- `reorderFields(Request, Form)` — accepts `order[]` array of IDs, updates sort_order

### `Admin\ItineraryController`
- Full CRUD for itineraries with cover image upload (stored to `itineraries/` in public disk)
- `items(Itinerary)` — loads all items grouped by day (with lugar options) for the item editor
- `storeItem(Request, Itinerary)` — creates new itinerary item
- `updateItem(Request, Itinerary, ItineraryItem)` — updates item (inline edit modal in view)
- `destroyItem(Itinerary, ItineraryItem)` — deletes item

### `MembershipController`
- `planes()` — loads active + ordered plans, renders plan grid
- `checkout(MembershipPlan $plan)` — abort if plan inactive; passes plan + `bankConfig()` to view
- `store(Request, MembershipPlan)` — validates transfer_reference (nullable), creates Order (status: pending), redirects to confirmation
- `confirmacion(Order)` — 403 if order.user_id != auth()->id(); loads plan; renders confirmation view
- Private `bankConfig()` — reads 6 `bank_*` Configuration keys

### `Admin\OrderController`
- `index(Request)` — paginate 30, optional `?status=` filter, passes `$pendingCount`
- `show(Order)` — loads user + plan eager
- `complete(Request, Order)` — abort if not pending; saves admin_notes if provided; calls `$order->complete()`
- `cancel(Request, Order)` — abort if already completed; saves admin_notes; calls `$order->cancel()`

### `Admin\MembershipPlanController`
- `index()` — all plans with orders_count
- `store(Request)` — creates new plan
- `update(Request, MembershipPlan)` — updates plan fields
- `destroy(MembershipPlan)` — deletes plan (only if no orders)

### `Admin\ConfigurationController`
- `index()` — reads backup config + SMTP config + payment config (`bank_*` keys), lists backup files, passes all stats to view
- `updateBackup(Request)`, `runBackup()`, `downloadBackup()` — backup management
- `updateSmtp(Request)` — saves smtp_* keys
- `updatePayment(Request)` — validates and saves bank_name, bank_account_holder, bank_cbu, bank_alias, bank_account_number, bank_instructions

### `PremiumController`
- `index()` — smart gate: if auth + isPremium → redirect to `premium.hub`; else → renders `premium.upsell`
- `hub()` — loads last 5 orders with plan; renders `premium.hub` (member panel)
- `planner()` — renders questionnaire form (auth + premium required)
- `resultados(Request)` — validates GET params (days required, type/season default mixed/all), calls `Itinerary::matchFilters()`, returns matched itinerary list
- `show(Itinerary)` — 404 if inactive; loads items with lugar.images; calls `itemsByDay()` for grouping; renders timeline view

---

## 8. Middleware

### `AdminMiddleware` (alias: `admin`)
Registered in `bootstrap/app.php`. Checks `auth()->check() && auth()->user()->is_admin`. Returns 403 if not admin.

### `PremiumMiddleware` (alias: `premium`)
Registered in `bootstrap/app.php`. Checks `auth()->check() && auth()->user()->isPremium()`. Redirects to `premium.upsell` if not premium (admins always pass — `isPremium()` returns true for admins).

---

## 9. Views & Layouts

### Public Layout (`layouts/app.blade.php`)
- `@php $navItems = \App\Models\NavItem::ordered()->visible()->get(); @endphp` at top
- Navbar and footer **both loop over `$navItems`** — hiding an item removes it from both
- Amber "✦ Premium" nav link always shown (→ `/premium`)
- Auth area (top right): logged-in users → name pill with dropdown (Admin panel / Mi cuenta Premium / Cerrar sesión); guests → "Iniciar sesión" link
- Mobile menu also includes Premium link + auth section (user info / logout or login/register)
- Sticky green navbar, mobile hamburger menu, 3-column footer

### Admin Layout (`layouts/admin.blade.php`)
Sidebar links: Dashboard, Lugares, Usuarios, Editar Inicio, **Menú de Navegación**, **Itinerarios Premium**, **Pedidos** (with amber pending badge count), **Planes Premium**, **Mensajes** (with unread badge count), **Formularios**, **Configuraciones**, Ver sitio, Cerrar Sesión.
Unread message badge: `\App\Models\Message::where('is_read', false)->count()`.
Pending orders badge: `\App\Models\Order::where('status','pending')->count()`.

### Place Detail Page (`pages/lugar.blade.php`) — Premium
Full Airbnb-inspired redesign:

| Section | Desktop | Mobile |
|---------|---------|--------|
| Breadcrumb nav | "Volver a Lugares" | Same |
| Gallery | 2/3 + 1/3 grid (main + up to 2 side images), hover zoom, "+N" overlay, "Ver todas las fotos" button | Swipeable carousel, dot indicators, fullscreen button |
| Title block | Category pill + stars + H1 (44px) + address | Same + prominent "Cómo Llegar" CTA |
| Two-column layout | Left: description + promotion + map; Right: sticky info card | Stacked vertically |
| Info card | Sticky `top-24`, green header, icon rows (address/hours/phone/website/rating), CTA buttons | Collapsible (chevron toggle), hidden by default |
| Lightbox | Full-screen, thumbnail strip, keyboard nav (Esc/←/→) | Same |
| Related places | 4-col grid, hover lift + zoom | Horizontal scroll snap |
| Sticky CTA | Hidden (info card covers it) | Fixed bottom bar, appears after 350px scroll |

### Hero Section (`pages/sections/hero.blade.php`)
- If `$sections['hero']->image` exists: renders as full-bleed `background-image` with gradient overlay
- Otherwise: solid green gradient background

---

## 10. Frontend Assets

### CSS (`resources/css/app.css`)
```css
@import 'tailwindcss';
@theme {
  --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
  --color-green-dark: #2D6A4F;
  --color-green-light: #52B788;
  --color-black: #1A1A1A;
}
```

### JS (`resources/js/app.js`)
Imports `bootstrap.js` (Axios + CSRF) and registers SortableJS globally as `window.Sortable`.

### Build
```bash
source ~/.nvm/nvm.sh && nvm use 20 && npm run build
```

---

## 11. Authentication

Custom (no Breeze/Jetstream). No email verification.

- **Login** → admins redirect to `/admin`, regular users redirect to `/premium` (which itself redirects to `/premium/panel` if premium, or shows upsell if not)
- **Registration** → `GET/POST /register` — public, creates non-admin user, auto-login, redirect to `/premium`
- **Forgot password** → `GET/POST /forgot-password` — sends reset link email using dynamic SMTP config
- **Reset password** → `GET /reset-password/{token}` + `POST /reset-password` — standard Laravel Password broker
- **`password_reset_tokens`** table — standard Laravel, exists from initial migration

---

## 12. Admin Panel

### Dashboard (`/admin`)
Stat cards: total Lugares + total Users.

### Lugares Manager (`/admin/lugares`)
Full CRUD. Create/Edit form: title, direction, description, main image, gallery (multi-file with drag sort), order, featured, category, rating, phone, website, opening_hours, lat/lng, promotion section.

### Users Manager (`/admin/usuarios`)
CRUD (no show). Cannot delete self.

### Homepage Editor (`/admin/inicio`)
- **Hero Image card** (top of page): preview current image (or placeholder), upload/replace/delete form. Stored at `storage/app/public/hero/`.
- **Sections list**: drag-to-reorder (SortableJS), visibility toggle, edit modal (title, subtitle, content, is_visible).

### Nav Menu Editor (`/admin/nav`)
- Drag-to-reorder all nav items
- One-click Visible/Oculto toggle
- Edit modal for changing the display label
- Changes reflected instantly in public navbar + footer

### Mensajes (`/admin/mensajes`)
- Grid of all submitted messages, paginated (30/page)
- **Filter by form** (Contacto, Hotels, etc.) and **read/unread status**
- Unread messages highlighted, red dot indicator, badge count in sidebar
- Click to view full message; can mark read/unread, reply by email link, delete

### Formularios (`/admin/formularios`)
- List of all form definitions with message count and active status
- Edit form settings: name, description, notification email, active/send_notification toggles
- **Campos editor** (`/admin/formularios/{form}/campos`): per-field controls — label, placeholder, **Visible** toggle, **Obligatorio** toggle, drag-and-drop reorder (pure HTML5 drag events + fetch AJAX)

### Pedidos (`/admin/pedidos`)
- Filter tabs: Todos / Pendientes (amber badge count) / Completados / Cancelados
- Table: zero-padded order ID, user name+email, plan name+duration, total, transfer reference, status badge, date, "Ver →" link
- Pending rows highlighted amber
- Order detail (`/admin/pedidos/{order}`): status banner, user card (with premium status + expiry), plan card, admin notes (saved with order), complete/cancel actions (forms with confirm dialog)
- Completing an order grants Premium; cancelling sets status=cancelled

### Planes Premium (`/admin/planes`)
- Plan list with order count, inline edit toggle (JS `toggleEdit(id)`)
- Edit form: name, price, duration_months, sort_order, description, active checkbox
- Create new plan form at bottom
- Plans are shown on `/premium/planes` ordered by `sort_order`

### Configuraciones (`/admin/configuraciones`)
Four cards:

**Configuración de Email (SMTP):**
- Host, port, encryption (TLS/SSL/STARTTLS/none)
- Username, password (write-only — shows checkmark if saved)
- From email and from name
- Saved to `configurations` table; applied dynamically at send time via `config([...])` calls

**Métodos de Pago (Bank Transfer):**
- Bank name, account holder, CBU (22-digit monospace), alias, account number, instructions
- Shown in checkout and confirmation pages for users to complete transfer
- Saved to `configurations` table as `bank_*` keys

**Copias de Seguridad (settings):**
- Toggle: enable/disable automatic backups
- Interval: 1h / 2h / 4h / 6h / 12h / 24h
- Keep last: 5 / 10 / 20 / 30 backups

**Estado del backup (info + actions):**
- Stats: backup count, last backup date, file size
- "Generar backup ahora" button (force-runs command)
- "Descargar último backup" button (streams file download)

**Cron setup notice** with exact command to add to server crontab.

---

## 13. Public Pages

### Homepage (`/`)
Renders `inicio_sections` via `@includeIf('pages.sections.' . $section->key)`. Hero section supports background image.

### Places Listing (`/lugares`)
- **Search:** `?q=` does LIKE match on title, direction, description
- **Filter:** `?category=` does exact match on category field
- Categories dropdown populated dynamically from DB
- Results count + active filter pills shown when filtering
- Empty state with "Ver todos los lugares" CTA
- Sticky filter bar (`sticky top-0 z-20`)

### Place Detail (`/lugares/{lugar}`)
See [Section 9](#9-views--layouts).

### Guides (`/guias`)
Static page.

### Contact (`/contacto`)
Loads the Form with `slug=contacto` and renders fields dynamically. Submits to `POST /formulario/contacto`. Fields respect visible/required config from DB. Shows validation errors inline. On success, shows flash message. If form is inactive or missing, shows a fallback message.

---

## 14. Image System

### Storage Paths
| Type | Disk | Directory | URL prefix |
|------|------|-----------|------------|
| Place images | public | `lugares/` | `/storage/lugares/` |
| Hero banner | public | `hero/` | `/storage/hero/` |
| DB backups | local | `backups/` | Not public (download via controller) |

### Cover Image Logic
`getCoverImageAttribute()`: first gallery image → falls back to `image` field.

---

## 15. Backup System

### Artisan Command: `db:backup`
**Signature:** `php artisan db:backup [--force]`

**Logic (without `--force`):**
1. Check `backup_enabled` config — abort if disabled
2. Check if `backup_interval_hours` have passed since `backup_last_run` — abort if too soon
3. Compare SQLite file `mtime` against `backup_last_run` timestamp — skip copy if no changes (but reset clock)
4. Copy `database/database.sqlite` → `storage/app/backups/backup-YYYY-MM-DD-HH-MM-SS.sqlite`
5. Prune oldest backups keeping only `backup_keep_count` files
6. Update `backup_last_run` and `backup_latest_file` in configurations table

**`--force` flag** skips steps 1–3 (used by manual "run now" in admin).

### Scheduler (`routes/console.php`)
```php
Schedule::command('db:backup')->everyMinute();
```
Runs every minute; the command self-governs via the configured interval.

### Required cron entry on server
```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 16. Messaging & Forms System

### How it works
1. **Form submission** — `POST /formulario/{slug}` hits `MessageController@store`
2. Validation rules built dynamically from visible + required field config
3. Only visible fields are stored in `messages.data` (JSON)
4. `NewMessageNotification` mailable is sent to `notification_email` (or `smtp_from_email`)
5. SMTP settings are applied at send time via `config([...])` — no server restart needed
6. If SMTP is not configured or sending fails, the message is still saved (silent fail)

### Adding a new form type (e.g. Hotels)
1. Insert a row in `forms` (slug: `hoteles`)
2. Insert rows in `form_fields` for that form
3. Embed `<form action="{{ route('formulario.submit', 'hoteles') }}">` on the public page
4. New form appears as a filter option in `/admin/mensajes` automatically

### `App\Mail\NewMessageNotification`
- View: `emails/new-message.blade.php`
- Subject: `Nuevo mensaje de {form name}`
- Renders all visible fields from the form with their submitted values

---

## 17. Premium Experience Module

### Overview
A gated membership feature accessible at `/premium`. Non-members (not logged in or no active membership) see an elegant upsell page at `/premium`. Members access a planning questionnaire, get filtered itinerary suggestions, and view full day-by-day timeline plans.

### User flow
1. User visits `/premium` — sees upsell (Free vs Premium comparison, how it works)
2. If logged in with active premium → redirected through to planner automatically; else prompted to contact admin
3. `/premium/planificar` — questionnaire: days (1–7), type (nature/gastronomy/adventure/relax/mixed), season (summer/winter/all), kids (checkbox), car (checkbox)
4. Submit → GET `/premium/resultados?days=2&type=nature&season=summer`
5. Matched itineraries shown as cards; click → `/premium/itinerario/{slug}`
6. Full timeline: cover hero, intro_tip tip card, days grouped → time blocks → activity cards

### Activity card layout
Each `ItineraryItem` renders:
- Left: lugar thumbnail (first gallery image)
- Right: title + duration badge + cost badge + address + why_order text
- Bottom panel (gray bg): contextual_notes (⚠️), skip_if (⏭️), why_worth_it (⭐), Google Maps link

### Membership management (Admin → Usuarios → Edit)
- Premium status shown with expiry date + diffForHumans
- Grant form: dropdown (1 month / 3 months / 6 months / 1 year / custom date) + amber "Otorgar Premium" button
- If already premium, button reads "Extender Premium" (extends from current expiry)
- Revoke link appears only when active

### Itinerary matching logic (`Itinerary::scopeMatchFilters`)
- `days` must fall within `[days_min, days_max]` range
- `type` matched exactly unless itinerary type is 'mixed' (matches any) OR requested type is 'mixed' (matches all)
- `season` matched exactly unless itinerary season is 'all' (matches any) OR requested season is 'all' (matches all)
- `car=false` → excludes itineraries with `requires_car=true`
- `kids=true` → limits to `kid_friendly=true` itineraries

### Admin Itinerarios (`/admin/itinerarios`)
- Index: list with title, days range, type, season, active toggle visual, actions
- Create/Edit: _form partial (title, slug, description, intro_tip, days_min/max, type, season, requires_car, kid_friendly, active, sort_order, cover image)
- Items editor (`/admin/itinerarios/{id}/items`): items grouped by day, add-item form at bottom, inline edit modal via JavaScript

---

## 18. Ecommerce & Membership Checkout

### Overview
A full self-service checkout for Premium memberships. Users browse plans, complete a bank transfer checkout, and an admin marks the order complete — which automatically grants Premium access.

### User flow
1. `/premium/planes` — browse 4 plan cards (1 mes, 3 meses, 6 meses, 1 año) with prices, features, and a "Más elegido" highlight on the 6-month plan
2. Click "Suscribirme" → `/premium/checkout/{slug}` (requires auth; non-logged users see "Iniciar sesión para suscribirme")
3. Checkout page shows bank transfer details (CBU, alias, amount) with copy-to-clipboard buttons and an optional transfer reference field
4. Submit → `POST /premium/checkout/{slug}` → creates `Order` (status: pending) → redirects to `/premium/pedido/{order}` (confirmation page)
5. Confirmation page shows order number, bank details reminder, and a 3-step "what happens next" guide

### Admin order flow
1. `/admin/pedidos` — table of all orders, filterable by status (Todos / Pendientes / Completados / Cancelados), with amber highlight on pending rows
2. Click order → `/admin/pedidos/{order}` — full detail: user info (with premium status), plan info, payment reference, admin notes field
3. Admin fills optional notes, clicks "Completar pedido" → `POST /admin/pedidos/{order}/completar`
4. `Order::complete()` — extends `premium_expires_at` by `plan->duration_months` from current expiry (or now if not premium), sets `status=completed`, `completed_at=now()`
5. Or admin clicks "Cancelar" → sets `status=cancelled`

### Bank transfer configuration
Admin configures bank details at `Admin → Configuraciones → Métodos de Pago`:
| Configuration key | Description |
|------------------|-------------|
| bank_name | Name of the bank |
| bank_account_holder | Account holder name |
| bank_cbu | 22-digit CBU (displayed monospace) |
| bank_alias | Transfer alias |
| bank_account_number | Account number |
| bank_instructions | Additional instructions (textarea) |

These are read by `MembershipController::bankConfig()` and passed to checkout/confirmation views.

### Membership Plans admin (`/admin/planes`)
- List all plans with order count per plan
- Inline edit form (toggle with JS) for name, price, duration_months, sort_order, description, active status
- Create new plan form at bottom
- Deactivating a plan hides it from `/premium/planes`

### Premium Hub (`/premium/panel`)
After purchasing, logged-in premium users see a hub at `/premium/panel` (accessible via `/premium` → redirect):
- Welcome card with name, membership expiry date + `diffForHumans()`
- Big CTA to start planning
- 3 quick action cards: Planificador, Explorar lugares, Contacto
- Feature list (what Premium includes)
- Recent orders section (last 5 orders with status badge)

---

## 19. Seeders & Sample Data

### `DatabaseSeeder` calls:
1. **AdminUserSeeder** — creates admin@conocetandil.com / password (is_admin: true)
2. **LugarSeeder** — creates 9 lugares (6 featured)
3. **InicioSectionSeeder** — creates 5 homepage sections
4. **FormSeeder** — creates default "Formulario de Contacto" (slug: contacto) with fields: nombre (text, required), email (email, required), telefono (tel, optional), mensaje (textarea, required)
5. **MembershipPlanSeeder** — creates 4 plans: 1 mes ($2,999), 3 meses ($6,999), 6 meses ($11,999), 1 año ($19,999); uses `firstOrCreate` so safe to re-run

Nav items and configurations are seeded via their own migrations (not seeders).

### Running seeders
```bash
php artisan db:seed
php artisan migrate:fresh --seed
# Or to seed only specific seeders:
php artisan db:seed --class=FormSeeder
php artisan db:seed --class=MembershipPlanSeeder
```

---

## 20. Design System

### Colors
| Name | Hex | Usage |
|------|-----|-------|
| Dark Green | `#2D6A4F` | Navbar, primary buttons, info icons, card headers |
| Light Green | `#52B788` | Hover states, accents, active nav, icon accents |
| Black | `#1A1A1A` | Body text, H1, footer bg, secondary buttons |

### Typography
- Font: Inter (via Tailwind theme)
- Place title: `text-[2.75rem]` bold, tight tracking
- Section labels: `text-[0.65rem] uppercase tracking-[0.12em]` (eyebrow style)
- Body: `text-gray-600`, `leading-[1.85]`, `max-w-prose`

### Component Patterns
- **Cards:** `bg-white rounded-2xl shadow-xl border border-gray-100`
- **Button primary:** `bg-[#2D6A4F] hover:bg-[#1A4A35] text-white font-bold py-3.5 rounded-xl transition-all`
- **Button outlined:** `border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white`
- **Form inputs:** `border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#52B788]`
- **Icon cells:** `w-8 h-8 bg-[#2D6A4F]/10 rounded-lg flex items-center justify-center`
- **Section max-width:** `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`

---

## 21. Development Setup

### Prerequisites
- PHP 8.2+, Composer, Node.js 20+, SQLite

### Quick Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install && npm run build
```

### Dev Server
```bash
composer dev    # concurrent: serve + queue + pail + vite dev
```

### Default Admin
- **Email:** admin@conocetandil.com
- **Password:** password

### Reset admin password
```bash
php artisan tinker --execute="\App\Models\User::where('email','admin@conocetandil.com')->update(['password'=>bcrypt('password')]);"
```

---

## 22. Known Limitations & Pending Work

| Feature | Status | Notes |
|---------|--------|-------|
| Contact form | ✅ Functional | Dynamic fields, DB storage, email notification |
| Email sending | Configurable | SMTP set via Admin Configuraciones; silently fails if not set |
| Premium Module | ✅ Functional | Membership, planner, itinerary timelines, admin CRUD |
| Premium payment | ✅ Functional | Bank transfer checkout; admin marks order complete → grants Premium |
| User registration | ✅ Functional | Public `/register` page; auto-login after registration |
| Password reset | ✅ Functional | `/forgot-password` + `/reset-password/{token}`; uses dynamic SMTP config |
| Form field creation | Not implemented | Fields added via seeder/tinker; admin can edit but not add new fields yet |
| Guides pricing/checkout | Static | No payment or cart |
| Social media links | Placeholder | Footer links go to `#` |
| API | None | No `routes/api.php` |
| Tests | None | No test files |
| Analytics | ✅ Functional | GA4 + GTM injection, event tracking, Data API dashboard — see Section 22 |
| Backup cron | Manual setup | Admin must add cron entry to server — see Section 15 |
| Google Maps | Partial | Lat/lng stored; Google Maps URL generated via accessor; rendered in itinerary items view |
| Payment verification | Manual | No automated bank transfer verification; admin confirms manually |
 
---

## 22. Analytics Module

### Overview
Full Google Analytics 4 and Google Tag Manager integration with admin dashboard.

### Configuration keys (in `configurations` table)
| Key | Description |
|-----|-------------|
| `analytics_enabled` | `'1'` = active, `'0'` = inactive |
| `analytics_gtm_id` | GTM Container ID (e.g. `GTM-XXXXXXX`) |
| `analytics_ga4_id` | GA4 Measurement ID (e.g. `G-XXXXXXXXXX`) — only used if no GTM |
| `analytics_ga4_property_id` | Numeric GA4 Property ID for Data API |

### Service Account credentials
Stored at `storage/app/analytics/service-account.json` (not in git).

### Files
- `app/Http/Controllers/Admin/AnalyticsController.php` — dashboard, settings save, cache refresh, credentials delete
- `app/Services/GoogleAnalyticsService.php` — JWT auth + concurrent GA4 Data API calls
- `resources/views/admin/analytics/dashboard.blade.php` — full dashboard with Chart.js line chart
- `resources/views/layouts/app.blade.php` — conditional GTM/GA4 script injection + funnel events

### Admin Routes
| Method | URI | Name |
|--------|-----|------|
| GET | `/admin/analytics` | `admin.analytics.dashboard` |
| POST | `/admin/analytics/settings` | `admin.analytics.settings.update` |
| POST | `/admin/analytics/refresh` | `admin.analytics.refresh` |
| DELETE | `/admin/analytics/credentials` | `admin.analytics.credentials.delete` |

### Dashboard metrics (last 28 days)
- Overview cards: users, sessions, page views, bounce rate, avg session duration
- Daily chart (Chart.js): sessions + page views over 30 days
- Top 10 pages table
- Traffic sources with bar visualization
- Conversion funnel: all visitors → lugares/guías → premium → checkout → purchase

### Event tracking (auto, frontend)
| Event | Trigger |
|-------|---------|
| `view_item` | Individual lugar page |
| `view_item_list` | Lugares list, Guías list, Premium planes |
| `view_promotion` | Premium upsell page |
| `begin_checkout` | Premium checkout page |
| `purchase` | Order confirmation page |
| `generate_lead` | Contact form submission |

### Setup steps
1. Create Google Cloud project, enable **Google Analytics Data API**
2. Create Service Account → download JSON key
3. In GA4: Admin → Property access management → add service account email as **Reader**
4. In Admin → Analytics: enter GTM ID or GA4 ID, Property ID, upload JSON key
5. Enable tracking toggle

### Caching
- Access token: 50 minutes (`ga4_access_token` cache key)
- Metrics data: 1 hour (`ga4_metrics_{propertyId}` cache key)
- "Actualizar datos" button clears both caches

---

> **Maintenance Note:** Update this file after every significant change to structure, schema, routes, or features.
