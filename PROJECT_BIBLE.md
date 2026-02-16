1. [x] # Conoce Tandil — Project Bible
2. [x] 
3. [x] > **Last updated:** 2026-02-16
4. [x] > **Purpose:** Single source of truth for the entire project. Share this file with developers or AI assistants to provide full context.
5. [x] 
6. [x] ---
7. [x] 
8. [x] ## Table of Contents
9. [x] 
10. [x] 1. [Overview](#1-overview)
11. [x] 2. [Tech Stack](#2-tech-stack)
12. [x] 3. [Project Structure](#3-project-structure)
13. [x] 4. [Database Schema](#4-database-schema)
14. [x] 5. [Routes](#5-routes)
15. [x] 6. [Models](#6-models)
16. [x] 7. [Controllers](#7-controllers)
17. [x] 8. [Middleware](#8-middleware)
18. [x] 9. [Views & Layouts](#9-views--layouts)
19. [x] 10. [Frontend Assets](#10-frontend-assets)
20. [x] 11. [Authentication](#11-authentication)
21. [x] 12. [Admin Panel](#12-admin-panel)
22. [x] 13. [Public Pages](#13-public-pages)
23. [x] 14. [Image System](#14-image-system)
24. [x] 15. [Seeders & Sample Data](#15-seeders--sample-data)
25. [x] 16. [Design System](#16-design-system)
26. [x] 17. [Development Setup](#17-development-setup)
27. [x] 18. [Known Limitations & Pending Work](#18-known-limitations--pending-work)
28. [x] 
29. [x] ---
30. [x] 
31. [x] ## 1. Overview
32. [x] 
33. [x] **Conoce Tandil** is a tourism website for the city of Tandil, Argentina. It lets visitors browse places to visit (lugares), read guides, and make contact. An admin panel allows content managers to create/edit/delete places, manage users, and customize the homepage.
34. [x] 
35. [x] **Key Capabilities:**
36. [x] - Public place browsing with detail pages (gallery, map, ratings, promotions)
37. [x] - Admin CRUD for places with multi-image gallery management
38. [x] - Drag-and-drop homepage section editor
39. [x] - User management with admin roles
40. [x] - Mobile-optimized with touch carousel, sticky CTA, collapsible content
41. [x] 
42. [x] ---
43. [x] 
44. [x] ## 2. Tech Stack
45. [x] 
46. [x] | Layer | Technology | Version |
47. [x] |-------|-----------|---------|
48. [x] | Framework | Laravel | 12.0 |
49. [x] | PHP | PHP | 8.2+ |
50. [x] | Database | SQLite | — |
51. [x] | CSS | Tailwind CSS | 4.0 |
52. [x] | Build Tool | Vite | 7.0 |
53. [x] | JS Libraries | Axios, SortableJS | 1.11, 1.15 |
54. [x] | Auth | Custom (no Breeze/Jetstream) | — |
55. [x] 
56. [x] **composer.json key packages:** `laravel/framework`, `laravel/tinker`
57. [x] **package.json key packages:** `tailwindcss`, `@tailwindcss/vite`, `axios`, `sortablejs`, `concurrently`
58. [x] 
59. [x] ---
60. [x] 
61. [x] ## 3. Project Structure
62. [x] 
63. [x] ```
64. [x] app/
65. [x] ├── Http/
66. [x] │   ├── Controllers/
67. [x] │   │   ├── AuthController.php          # Login/logout
68. [x] │   │   ├── PageController.php          # Public pages
69. [x] │   │   └── Admin/
70. [x] │   │       ├── DashboardController.php # Admin home
71. [x] │   │       ├── LugarController.php     # Places CRUD
72. [x] │   │       ├── UserController.php      # User management
73. [x] │   │       └── InicioSectionController.php # Homepage editor
74. [x] │   └── Middleware/
75. [x] │       └── AdminMiddleware.php         # Admin role check
76. [x] ├── Models/
77. [x] │   ├── User.php
78. [x] │   ├── Lugar.php
79. [x] │   ├── LugarImage.php
80. [x] │   └── InicioSection.php
81. [x] 
82. [x] database/
83. [x] ├── migrations/
84. [x] │   ├── ...create_users_table.php
85. [x] │   ├── ...add_is_admin_to_users_table.php
86. [x] │   ├── ...create_lugares_table.php
87. [x] │   ├── ...create_inicio_sections_table.php
88. [x] │   ├── ...create_lugar_images_table.php
89. [x] │   └── ...add_detail_fields_to_lugares_table.php
90. [x] ├── seeders/
91. [x] │   ├── DatabaseSeeder.php
92. [x] │   ├── AdminUserSeeder.php
93. [x] │   ├── LugarSeeder.php
94. [x] │   └── InicioSectionSeeder.php
95. [x] 
96. [x] resources/
97. [x] ├── css/app.css                         # Tailwind imports + theme
98. [x] ├── js/
99. [x] │   ├── app.js                          # SortableJS setup
100. [x] │   └── bootstrap.js                    # Axios + CSRF
101. [x] └── views/
102. [x]     ├── layouts/
103. [x]     │   ├── app.blade.php               # Public layout
104. [x]     │   └── admin.blade.php             # Admin layout
105. [x]     ├── auth/
106. [x]     │   └── login.blade.php
107. [x]     ├── pages/
108. [x]     │   ├── inicio.blade.php            # Homepage (dynamic sections)
109. [x]     │   ├── lugares.blade.php           # Places listing
110. [x]     │   ├── lugar.blade.php             # Place detail (premium page)
111. [x]     │   ├── guias.blade.php             # Guides (static)
112. [x]     │   ├── contacto.blade.php          # Contact (static form)
113. [x]     │   └── sections/                   # Homepage section partials
114. [x]     │       ├── hero.blade.php
115. [x]     │       ├── featured.blade.php
116. [x]     │       ├── banner.blade.php
117. [x]     │       ├── cta_guias.blade.php
118. [x]     │       └── cta_contacto.blade.php
119. [x]     └── admin/
120. [x]         ├── dashboard.blade.php
121. [x]         ├── lugares/
122. [x]         │   ├── index.blade.php
123. [x]         │   ├── create.blade.php
124. [x]         │   └── edit.blade.php
125. [x]         ├── usuarios/
126. [x]         │   ├── index.blade.php
127. [x]         │   ├── create.blade.php
128. [x]         │   └── edit.blade.php
129. [x]         └── inicio/
130. [x]             └── index.blade.php         # Drag-and-drop editor
131. [x] 
132. [x] routes/
133. [x] └── web.php                             # All routes
134. [x] 
135. [x] storage/app/public/
136. [x] └── lugares/                            # Uploaded images
137. [x] ```
138. [x] 
139. [x] ---
140. [x] 
141. [x] ## 4. Database Schema
142. [x] 
143. [x] ### `users`
144. [x] | Column | Type | Notes |
145. [x] |--------|------|-------|
146. [x] | id | bigint PK | |
147. [x] | name | string | |
148. [x] | email | string | unique |
149. [x] | email_verified_at | timestamp | nullable |
150. [x] | password | string | hashed |
151. [x] | is_admin | boolean | default: false |
152. [x] | remember_token | string | nullable |
153. [x] | timestamps | | |
154. [x] 
155. [x] ### `lugares`
156. [x] | Column | Type | Notes |
157. [x] |--------|------|-------|
158. [x] | id | bigint PK | |
159. [x] | title | string | required |
160. [x] | direction | string | required (address) |
161. [x] | description | text | required |
162. [x] | image | string | nullable, legacy main image path |
163. [x] | featured | boolean | default: false |
164. [x] | order | integer | default: 0 |
165. [x] | category | string(100) | nullable — "Restaurante", "Senderismo", etc. |
166. [x] | rating | decimal(2,1) | nullable — 0.0 to 5.0 |
167. [x] | phone | string(20) | nullable |
168. [x] | website | string(255) | nullable |
169. [x] | opening_hours | string(255) | nullable — free text like "Lun-Vie 9:00-18:00" |
170. [x] | promotion_title | string(150) | nullable |
171. [x] | promotion_description | text | nullable |
172. [x] | promotion_url | string(255) | nullable |
173. [x] | latitude | decimal(10,7) | nullable |
174. [x] | longitude | decimal(10,7) | nullable |
175. [x] | timestamps | | |
176. [x] 
177. [x] **Relationships:** `hasMany` → `lugar_images`
178. [x] 
179. [x] ### `lugar_images`
180. [x] | Column | Type | Notes |
181. [x] |--------|------|-------|
182. [x] | id | bigint PK | |
183. [x] | lugar_id | foreignId | cascadeOnDelete |
184. [x] | path | string | storage path |
185. [x] | order | integer | default: 0 |
186. [x] | timestamps | | |
187. [x] 
188. [x] **Relationships:** `belongsTo` → `Lugar`
189. [x] 
190. [x] ### `inicio_sections`
191. [x] | Column | Type | Notes |
192. [x] |--------|------|-------|
193. [x] | id | bigint PK | |
194. [x] | key | string | unique — hero, banner, featured, cta_guias, cta_contacto |
195. [x] | title | string | |
196. [x] | subtitle | string | nullable |
197. [x] | content | text | nullable |
198. [x] | order | integer | default: 0 |
199. [x] | is_visible | boolean | default: true |
200. [x] | timestamps | | |
201. [x] 
202. [x] ### System tables
203. [x] `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `password_reset_tokens` — standard Laravel tables.
204. [x] 
205. [x] ---
206. [x] 
207. [x] ## 5. Routes
208. [x] 
209. [x] ### Public
210. [x] | Method | URI | Controller@action | Name |
211. [x] |--------|-----|-------------------|------|
212. [x] | GET | `/` | PageController@inicio | inicio |
213. [x] | GET | `/lugares` | PageController@lugares | lugares |
214. [x] | GET | `/lugares/{lugar}` | PageController@lugar | lugar.show |
215. [x] | GET | `/guias` | PageController@guias | guias |
216. [x] | GET | `/contacto` | PageController@contacto | contacto |
217. [x] 
218. [x] ### Auth (middleware: guest)
219. [x] | Method | URI | Controller@action | Name |
220. [x] |--------|-----|-------------------|------|
221. [x] | GET | `/login` | AuthController@showLogin | login |
222. [x] | POST | `/login` | AuthController@login | — |
223. [x] | POST | `/logout` | AuthController@logout (auth) | logout |
224. [x] 
225. [x] ### Admin (prefix: `/admin`, middleware: auth + admin)
226. [x] | Method | URI | Controller@action | Name |
227. [x] |--------|-----|-------------------|------|
228. [x] | GET | `/admin` | DashboardController@index | admin.dashboard |
229. [x] | RESOURCE | `/admin/lugares` | LugarController | admin.lugares.* |
230. [x] | RESOURCE | `/admin/usuarios` (except show) | UserController | admin.usuarios.* |
231. [x] | GET | `/admin/inicio` | InicioSectionController@index | admin.inicio.index |
232. [x] | PUT | `/admin/inicio/{inicioSection}` | InicioSectionController@update | admin.inicio.update |
233. [x] | POST | `/admin/inicio/reorder` | InicioSectionController@reorder | admin.inicio.reorder |
234. [x] 
235. [x] ---
236. [x] 
237. [x] ## 6. Models
238. [x] 
239. [x] ### `User`
240. [x] - **Fillable:** name, email, password, is_admin
241. [x] - **Casts:** email_verified_at (datetime), password (hashed), is_admin (boolean)
242. [x] - **Methods:** `isAdmin(): bool`
243. [x] 
244. [x] ### `Lugar`
245. [x] - **Fillable:** title, direction, description, image, featured, order, category, rating, phone, website, opening_hours, promotion_title, promotion_description, promotion_url, latitude, longitude
246. [x] - **Casts:** featured (boolean), order (integer), rating (decimal:1), latitude (decimal:7), longitude (decimal:7)
247. [x] - **Appends:** cover_image
248. [x] - **Relationships:** `images()` → hasMany LugarImage (ordered by `order`)
249. [x] - **Accessors:**
250. [x] - `getCoverImageAttribute()` — first gallery image, falls back to `image` field
251. [x] - `getGoogleMapsUrlAttribute()` — builds Maps search URL (coords or address)
252. [x] - `getGoogleMapsDirectionsUrlAttribute()` — builds Maps directions URL
253. [x] - **Methods:**
254. [x] - `hasCoordinates(): bool` — lat/lng both not null
255. [x] - `hasPromotion(): bool` — promotion_title not null
256. [x] - **Scopes:** `featured()`, `ordered()`
257. [x] 
258. [x] ### `LugarImage`
259. [x] - **Fillable:** lugar_id, path, order
260. [x] - **Relationships:** `lugar()` → belongsTo Lugar
261. [x] 
262. [x] ### `InicioSection`
263. [x] - **Fillable:** key, title, subtitle, content, order, is_visible
264. [x] - **Casts:** order (integer), is_visible (boolean)
265. [x] - **Scopes:** `ordered()`, `visible()`
266. [x] 
267. [x] ---
268. [x] 
269. [x] ## 7. Controllers
270. [x] 
271. [x] ### `AuthController`
272. [x] - `showLogin()` — renders login form
273. [x] - `login(Request)` — validates credentials, authenticates, redirects to `/admin`
274. [x] - `logout(Request)` — invalidates session, redirects to `/`
275. [x] 
276. [x] ### `PageController`
277. [x] - `inicio()` — loads featured lugares (6, eager images) + visible sections keyed by `key`
278. [x] - `lugares()` — loads all lugares ordered, with images
279. [x] - `lugar(Lugar)` — loads single lugar with images + 6 random related places (images eager-loaded)
280. [x] - `guias()` — static view
281. [x] - `contacto()` — static view
282. [x] 
283. [x] ### `Admin\DashboardController`
284. [x] - `index()` — passes total lugar count + user count to dashboard view
285. [x] 
286. [x] ### `Admin\LugarController`
287. [x] - Full CRUD with image upload handling
288. [x] - `store()` — validates 20+ fields, stores main image + gallery images to `lugares/` disk
289. [x] - `update()` — same validation, handles image replacement, gallery deletion (by checkbox), new gallery additions
290. [x] - `destroy()` — deletes lugar + all images from storage
291. [x] - **Validation rules include:** category, rating (0-5), phone, website, opening_hours, promotion fields, latitude (-90 to 90), longitude (-180 to 180)
292. [x] 
293. [x] ### `Admin\UserController`
294. [x] - CRUD (except show)
295. [x] - Prevents self-deletion
296. [x] - Password optional on update
297. [x] 
298. [x] ### `Admin\InicioSectionController`
299. [x] - `index()` — all sections ordered for drag-and-drop editor
300. [x] - `update(Request, InicioSection)` — updates title, subtitle, content, is_visible
301. [x] - `reorder(Request)` — receives ordered array of IDs via AJAX, updates `order` column
302. [x] 
303. [x] ---
304. [x] 
305. [x] ## 8. Middleware
306. [x] 
307. [x] ### `AdminMiddleware` (alias: `admin`)
308. [x] Registered in `bootstrap/app.php`. Checks `auth()->check() && auth()->user()->is_admin`. Returns 403 if unauthorized.
309. [x] 
310. [x] ---
311. [x] 
312. [x] ## 9. Views & Layouts
313. [x] 
314. [x] ### Public Layout (`layouts/app.blade.php`)
315. [x] - Sticky navbar with green theme (#2D6A4F background)
316. [x] - Desktop: horizontal links — Inicio, Lugares, Guías, Contacto, Iniciar Sesión
317. [x] - Mobile: hamburger menu with JS toggle
318. [x] - Footer: 3-column (branding, links, social icons)
319. [x] - Uses `@vite(['resources/css/app.css', 'resources/js/app.js'])`
320. [x] 
321. [x] ### Admin Layout (`layouts/admin.blade.php`)
322. [x] - Fixed left sidebar (dark theme) with nav links to Dashboard, Lugares, Usuarios, Secciones Inicio
323. [x] - Mobile: collapsible sidebar with overlay
324. [x] - Top bar: header title + user name/email + logout button
325. [x] - Flash message rendering (success alerts)
326. [x] 
327. [x] ### Place Detail Page (`pages/lugar.blade.php`) — Premium Feature
328. [x] This is the most complex view. Sections rendered conditionally:
329. [x] 
330. [x] | Section | Condition | Desktop | Mobile |
331. [x] |---------|-----------|---------|--------|
332. [x] | Gallery | images > 0 | 3-col grid (1 large + 3 thumbs, +N overlay) | Touch carousel with dots + arrows |
333. [x] | Title Block | always | Category pill + star rating + H1 + address link | Same, stacked |
334. [x] | Info Block | hours/phone/website exist | 2-col cards + action buttons (w-64) | Stacked vertically |
335. [x] | Minimal CTA | no info fields | Single "Cómo Llegar" button | Same |
336. [x] | Description | always | Full prose text | Collapsible (150px, fade, "Leer más"/"Leer menos") |
337. [x] | Promotion | promotion_title set | Gradient banner with icon + CTA | Same, stacked |
338. [x] | Map | lat/lng set | Google Maps iframe (h-450) + "Abrir en Maps" link | Same |
339. [x] | Related Places | other lugares exist | 4-col grid (max 4) | Horizontal scroll (w-72 cards, snap) |
340. [x] | Back Link | always | "Volver a Lugares" | Same |
341. [x] | Sticky CTA | mobile only | N/A | Fixed bottom bar, shows after 500px scroll down, hides on scroll up |
342. [x] | Lightbox | images > 0 | Full-screen overlay, keyboard nav (Esc/arrows) | Same |
343. [x] 
344. [x] **JavaScript (vanilla IIFE):** Lightbox controller, mobile carousel (translateX + touch 50px threshold), collapsible description, sticky CTA scroll listener.
345. [x] 
346. [x] ---
347. [x] 
348. [x] ## 10. Frontend Assets
349. [x] 
350. [x] ### CSS (`resources/css/app.css`)
351. [x] ```css
352. [x] @import 'tailwindcss';
353. [x] @source "../views/**/*.blade.php";
354. [x] 
355. [x] @theme {
356. [x] --font-sans: 'Inter', 'Instrument Sans', sans-serif;
357. [x] --color-green-dark: #2D6A4F;
358. [x] --color-green-light: #52B788;
359. [x] --color-black: #1A1A1A;
360. [x] }
361. [x] ```
362. [x] 
363. [x] ### JS (`resources/js/app.js`)
364. [x] Imports `bootstrap.js` (Axios with CSRF) and registers SortableJS globally on `window.Sortable`.
365. [x] 
366. [x] ### Vite Config (`vite.config.js`)
367. [x] Laravel plugin with `resources/css/app.css` + `resources/js/app.js` as entry points. Tailwind CSS Vite plugin included.
368. [x] 
369. [x] ---
370. [x] 
371. [x] ## 11. Authentication
372. [x] 
373. [x] Custom implementation (no Breeze/Jetstream):
374. [x] - `GET /login` — form with email + password
375. [x] - `POST /login` — `Auth::attempt()`, redirects to `/admin`
376. [x] - `POST /logout` — `Auth::logout()` + session invalidate/regenerate
377. [x] - Guest middleware on login routes
378. [x] - Auth + Admin middleware on all `/admin/*` routes
379. [x] - No registration, no password reset, no email verification
380. [x] 
381. [x] ---
382. [x] 
383. [x] ## 12. Admin Panel
384. [x] 
385. [x] ### Dashboard (`/admin`)
386. [x] Displays stat cards: total Lugares count, total Users count.
387. [x] 
388. [x] ### Lugares Manager (`/admin/lugares`)
389. [x] - **Index:** Paginated table (10/page) with thumbnail, title, direction, featured badge, edit/delete buttons
390. [x] - **Create/Edit:** Full form with:
391. [x] - Title, Direction, Description (required)
392. [x] - Main image upload with preview
393. [x] - Multi-file gallery upload with previews
394. [x] - Existing gallery images with delete checkboxes (edit only)
395. [x] - Order + Featured toggle
396. [x] - Category + Rating (0-5, step 0.1)
397. [x] - Phone + Website
398. [x] - Opening Hours
399. [x] - Latitude + Longitude (step 0.0000001)
400. [x] - Promotion section (title, description, URL) in bordered box
401. [x] 
402. [x] ### Users Manager (`/admin/usuarios`)
403. [x] - **Index:** Table with name, email, role badge (Admin/Usuario), edit/delete
404. [x] - **Create/Edit:** Name, email, password (optional on edit), admin checkbox
405. [x] - Cannot delete yourself
406. [x] 
407. [x] ### Homepage Editor (`/admin/inicio`)
408. [x] - Cards for each section showing title, subtitle, visibility badge
409. [x] - **Drag-and-drop reordering** via SortableJS → AJAX POST to `/admin/inicio/reorder`
410. [x] - **Edit modal** (inline) for title, subtitle, content (textarea), visibility toggle
411. [x] - Sections: hero, banner, featured, cta_guias, cta_contacto
412. [x] 
413. [x] ---
414. [x] 
415. [x] ## 13. Public Pages
416. [x] 
417. [x] ### Homepage (`/`)
418. [x] Dynamically renders sections from `inicio_sections` table using `@includeIf('pages.sections.' . $section->key)`:
419. [x] - **Hero:** Background gradient, title, subtitle, search input (UI only)
420. [x] - **Featured:** 3-column grid of featured lugares (links to detail)
421. [x] - **Banner:** Promotional text section
422. [x] - **CTA Guías:** Call-to-action linking to /guias
423. [x] - **CTA Contacto:** Call-to-action linking to /contacto
424. [x] 
425. [x] ### Places Listing (`/lugares`)
426. [x] - Header banner
427. [x] - Search/filter bar (UI only — not functional)
428. [x] - 3-column responsive grid of lugar cards (image, title, address, description excerpt)
429. [x] 
430. [x] ### Place Detail (`/lugares/{lugar}`)
431. [x] See [Section 9](#9-views--layouts) for full breakdown.
432. [x] 
433. [x] ### Guides (`/guias`)
434. [x] Static page with 3 pricing cards (Básica, Premium, Exclusiva). No backend integration.
435. [x] 
436. [x] ### Contact (`/contacto`)
437. [x] Static form (name, email, subject, message) with `action="#"`. Info cards for address/phone/hours. Map placeholder.
438. [x] 
439. [x] ---
440. [x] 
441. [x] ## 14. Image System
442. [x] 
443. [x] ### Storage
444. [x] - Disk: `public` (symlinked via `php artisan storage:link`)
445. [x] - Directory: `storage/app/public/lugares/`
446. [x] - URL: `/storage/lugares/{filename}`
447. [x] 
448. [x] ### Two Image Types
449. [x] 1. **Main image** (`lugares.image`) — legacy single image field
450. [x] 2. **Gallery images** (`lugar_images` table) — multiple ordered images
451. [x] 
452. [x] ### Cover Image Logic
453. [x] `Lugar::getCoverImageAttribute()`: Returns first gallery image path if gallery exists, otherwise falls back to main `image` field. This is appended to all Lugar JSON/array output.
454. [x] 
455. [x] ### Upload Flow
456. [x] - Main image: single file, stored via `$file->store('lugares', 'public')`
457. [x] - Gallery: multi-file input, each stored individually with incremental `order`
458. [x] - On update: old main image deleted from disk when replaced
459. [x] - Gallery deletion: checkboxes mark images for deletion, removed from disk + DB
460. [x] 
461. [x] ---
462. [x] 
463. [x] ## 15. Seeders & Sample Data
464. [x] 
465. [x] ### `DatabaseSeeder` calls:
466. [x] 1. **AdminUserSeeder** — creates admin@conocetandil.com / password (is_admin: true)
467. [x] 2. **LugarSeeder** — creates 9 lugares (6 featured): Cerro Centinela, Piedra Movediza, Lago del Fuerte, Monte Calvario, Reserva Sierra del Tigre, Centro Histórico, Balcón de Nogueira, Parque Independencia, Época de Quesos
468. [x] 3. **InicioSectionSeeder** — creates 5 homepage sections (hero, featured, banner, cta_guias, cta_contacto)
469. [x] 
470. [x] ### Running seeders
471. [x] ```bash
472. [x] php artisan db:seed                    # Run all
473. [x] php artisan db:seed --class=LugarSeeder # Run one
474. [x] php artisan migrate:fresh --seed       # Reset + seed
475. [x] ```
476. [x] 
477. [x] ---
478. [x] 
479. [x] ## 16. Design System
480. [x] 
481. [x] ### Colors
482. [x] | Name | Hex | Usage |
483. [x] |------|-----|-------|
484. [x] | Dark Green | `#2D6A4F` | Navbar, primary buttons, links, badges |
485. [x] | Light Green | `#52B788` | Hover states, accents, active nav |
486. [x] | Black | `#1A1A1A` | Body text, secondary buttons, footer bg |
487. [x] | Category badge bg | `#2D6A4F` at 10% opacity | Category pills |
488. [x] | Promotion gradient | `#F0FFF4` → `#E6F7ED` | Promotion banner |
489. [x] 
490. [x] ### Typography
491. [x] - Font: Inter (via Tailwind theme)
492. [x] - Headings: `font-bold`, sizes from `text-sm` to `text-5xl`
493. [x] - Body: `text-gray-600`, prose styling for descriptions
494. [x] 
495. [x] ### Component Patterns
496. [x] - **Cards:** `bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-100`
497. [x] - **Buttons primary:** `bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-2 px-6 rounded-lg transition`
498. [x] - **Buttons outlined:** `border-2 border-[#2D6A4F] text-[#2D6A4F] hover:bg-[#2D6A4F] hover:text-white`
499. [x] - **Form inputs:** `border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#52B788]`
500. [x] - **Info cards:** `bg-gray-50 rounded-lg p-4` with icon in `bg-[#2D6A4F]/10 rounded-lg p-2`
501. [x] - **Section spacing:** `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`
502. [x] 
503. [x] ---
504. [x] 
505. [x] ## 17. Development Setup
506. [x] 
507. [x] ### Prerequisites
508. [x] - PHP 8.2+
509. [x] - Composer
510. [x] - Node.js 20+
511. [x] - SQLite
512. [x] 
513. [x] ### Quick Setup
514. [x] ```bash
515. [x] composer install
516. [x] cp .env.example .env
517. [x] php artisan key:generate
518. [x] php artisan migrate --seed
519. [x] php artisan storage:link
520. [x] npm install
521. [x] npm run build
522. [x] ```
523. [x] 
524. [x] ### Development Server
525. [x] ```bash
526. [x] composer dev    # Runs: php artisan serve + queue:work + pail + vite dev (concurrent)
527. [x] ```
528. [x] 
529. [x] Or manually:
530. [x] ```bash
531. [x] php artisan serve      # Backend at http://localhost:8000
532. [x] npm run dev            # Vite HMR
533. [x] ```
534. [x] 
535. [x] ### Default Admin Login
536. [x] - **Email:** admin@conocetandil.com
537. [x] - **Password:** password
538. [x] 
539. [x] ### Build for Production
540. [x] ```bash
541. [x] npm run build
542. [x] ```
543. [x] 
544. [x] ---
545. [x] 
546. [x] ## 18. Known Limitations & Pending Work
547. [x] 
548. [x] These features have UI but no backend implementation yet:
549. [x] 
550. [x] | Feature | Status | Notes |
551. [x] |---------|--------|-------|
552. [x] | Search bar on homepage | UI only | No search query handling |
553. [x] | Lugares filter/search | UI only | Category select + text input render but don't filter |
554. [x] | Contact form | UI only | `action="#"`, no mail sending |
555. [x] | Guides pricing/checkout | Static | No payment or cart system |
556. [x] | Social media links | Placeholder | Footer links go to `#` |
557. [x] | Email sending | Not configured | Mail driver is `log` |
558. [x] | Password reset | Not implemented | No forgot password flow |
559. [x] | User registration | Not implemented | Admin creates users manually |
560. [x] | API | None | No `routes/api.php` endpoints |
561. [x] | Tests | None | No test files exist |
562. [x] | Analytics | None | No tracking integration |
563. [x] 
564. [x] ---
565. [x] 
566. [x] > **Maintenance Note:** Update this file whenever significant changes are made to the project structure, database schema, routes, or features. Keep it in sync with the codebase so it can serve as onboarding documentation for developers and context for AI assistants.
567. [x] 
