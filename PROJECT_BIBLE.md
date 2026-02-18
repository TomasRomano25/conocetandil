# Conoce Tandil — Project Bible

> **Last updated:** 2026-02-18
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
17. [Seeders & Sample Data](#17-seeders--sample-data)
18. [Design System](#18-design-system)
19. [Development Setup](#19-development-setup)
20. [Known Limitations & Pending Work](#20-known-limitations--pending-work)

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
│   │   ├── AuthController.php          # Login/logout
│   │   ├── PageController.php          # Public pages (with search/filter)
│   │   ├── MessageController.php       # Public form submission (POST /formulario/{slug})
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── LugarController.php     # Places CRUD
│   │       ├── UserController.php      # User management
│   │       ├── InicioSectionController.php # Homepage editor + hero image
│   │       ├── NavItemController.php   # Nav menu control
│   │       ├── ConfigurationController.php # Site settings + backups + SMTP
│   │       ├── MessageController.php   # Admin inbox (list, show, mark read, delete)
│   │       └── FormController.php      # Form + field management
│   └── Middleware/
│       └── AdminMiddleware.php
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
│   └── Message.php                     # Submitted form messages

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
│   └── 2026_02_18_000003_create_messages_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── AdminUserSeeder.php
│   ├── LugarSeeder.php
│   ├── InicioSectionSeeder.php
│   └── FormSeeder.php                  # Default "Contacto" form with 4 fields

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
    │   └── login.blade.php
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
    └── admin/
        ├── dashboard.blade.php
        ├── lugares/ (index, create, edit)
        ├── usuarios/ (index, create, edit)
        ├── inicio/
        │   └── index.blade.php         # Sections editor + hero image upload
        ├── nav/
        │   └── index.blade.php         # Nav menu editor
        ├── configuraciones/
        │   └── index.blade.php         # SMTP + backup settings
        ├── mensajes/
        │   ├── index.blade.php         # Message grid with form/read filters
        │   └── show.blade.php          # Individual message view
        └── formularios/
            ├── index.blade.php         # Form settings editor
            └── campos.blade.php        # Field editor with drag-and-drop reorder

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

`/lugares` accepts query params: `?q=search+term&category=Naturaleza`

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

---

## 6. Models

### `User`
- **Fillable:** name, email, password, is_admin
- **Casts:** password (hashed), is_admin (boolean)
- **Methods:** `isAdmin(): bool`

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

---

## 7. Controllers

### `AuthController`
- `showLogin()`, `login(Request)`, `logout(Request)`

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

---

## 8. Middleware

### `AdminMiddleware` (alias: `admin`)
Registered in `bootstrap/app.php`. Checks `auth()->check() && auth()->user()->is_admin`. Returns 403 if not admin.

---

## 9. Views & Layouts

### Public Layout (`layouts/app.blade.php`)
- `@php $navItems = \App\Models\NavItem::ordered()->visible()->get(); @endphp` at top
- Navbar and footer **both loop over `$navItems`** — hiding an item removes it from both
- Login link is always shown (not a NavItem)
- Sticky green navbar, mobile hamburger menu, 3-column footer

### Admin Layout (`layouts/admin.blade.php`)
Sidebar links: Dashboard, Lugares, Usuarios, Editar Inicio, **Menú de Navegación**, **Mensajes** (with unread badge count), **Formularios**, **Configuraciones**, Ver sitio, Cerrar Sesión.
The unread badge is computed inline: `\App\Models\Message::where('is_read', false)->count()`.

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

Custom (no Breeze/Jetstream). No registration, no password reset, no email verification. Admin creates users manually. Login: `Auth::attempt()` → redirect to `/admin`.

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

### Configuraciones (`/admin/configuraciones`)
Three cards:

**Configuración de Email (SMTP):**
- Host, port, encryption (TLS/SSL/STARTTLS/none)
- Username, password (write-only — shows checkmark if saved)
- From email and from name
- Saved to `configurations` table; applied dynamically at send time via `config([...])` calls

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

## 17. Seeders & Sample Data

### `DatabaseSeeder` calls:
1. **AdminUserSeeder** — creates admin@conocetandil.com / password (is_admin: true)
2. **LugarSeeder** — creates 9 lugares (6 featured)
3. **InicioSectionSeeder** — creates 5 homepage sections
4. **FormSeeder** — creates default "Formulario de Contacto" (slug: contacto) with fields: nombre (text, required), email (email, required), telefono (tel, optional), mensaje (textarea, required)

Nav items and configurations are seeded via their own migrations (not seeders).

### Running seeders
```bash
php artisan db:seed
php artisan migrate:fresh --seed
# Or to seed only the form:
php artisan db:seed --class=FormSeeder
```

---

## 18. Design System

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

## 19. Development Setup

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

## 20. Known Limitations & Pending Work

| Feature | Status | Notes |
|---------|--------|-------|
| Contact form | ✅ Functional | Dynamic fields, DB storage, email notification |
| Email sending | Configurable | SMTP set via Admin Configuraciones; silently fails if not set |
| Guides pricing/checkout | Static | No payment or cart |
| Social media links | Placeholder | Footer links go to `#` |
| Password reset | Not implemented | No forgot password flow |
| User registration | Not implemented | Admin creates users manually |
| Form field creation | Not implemented | Fields added via seeder/tinker; admin can edit but not add new fields yet |
| API | None | No `routes/api.php` |
| Tests | None | No test files |
| Analytics | None | No tracking integration |
| Backup cron | Manual setup | Admin must add cron entry to server — see Section 15 |
| Google Maps | Placeholder | Lat/lng stored but map not rendered |
 
---

> **Maintenance Note:** Update this file after every significant change to structure, schema, routes, or features.
