# Server issue fixes (EF)

This doc captures the production issues we hit while deploying EF and the quickest ways to diagnose/fix them.

## Filament `/admin` returns 403 after login

### Symptom
- `/admin/login` loads (200)
- Login submission appears successful
- Visiting `/admin` returns **403 Forbidden**

### Root cause (Filament v4)
Filament v4’s middleware requires your authenticated user model to implement `Filament\Models\Contracts\FilamentUser` in non-`local` environments. If it doesn’t, Filament aborts with 403.

### Fix
Implement `FilamentUser` on `app/Models/User.php` and define `canAccessPanel()`:

- **File**: `app/Models/User.php`
- **Requirement**: `implements FilamentUser`
- **Method**: `public function canAccessPanel(Panel $panel): bool`

Notes:
- Returning `true` allows all users into the panel. Tighten this for production (email allowlist, `is_admin` flag, roles/permissions).

### Quick verification
From the server:

```bash
curl -I http://YOUR_HOST/admin/login
curl -I http://YOUR_HOST/admin
```

Expected:
- `/admin` redirects to `/admin/login` when logged out (302)
- `/admin` returns 200 when logged in and authorized

## Vite/Filament theme error: “Unable to locate file in Vite manifest… theme.css”

### Symptom
Filament (or the site) returns 500 and logs show:
`Unable to locate file in Vite manifest: resources/css/filament/admin/theme.css`

### Root cause
`public/build/manifest.json` is missing or doesn’t include the Filament theme entry. This happens when:
- `npm run build` wasn’t run, failed, or ran in the wrong directory
- `public/build` was deleted during troubleshooting and not rebuilt

### Fix
Rebuild the Vite assets and clear Laravel caches:

```bash
rm -rf public/build
npm ci || npm install
npm run build
php artisan optimize:clear
```

### Quick verification

```bash
ls -la public/build/manifest.json
grep -F "resources/css/filament/admin/theme.css" public/build/manifest.json
```

## NPM build fails on server: Rollup optional dependency missing

### Symptom
`npm run build` fails with:
`Cannot find module @rollup/rollup-linux-x64-gnu`

### Root cause
An npm optional-dependencies install issue on some Linux environments.

### Fix (npm)

```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install --include=optional
npm rebuild
npm run build
```

If it still fails:

```bash
npm i -D @rollup/rollup-linux-x64-gnu
npm run build
```

## `php artisan config:cache` fails (permission denied)

### Symptom
Artisan fails with errors like:
- cannot open `storage/logs/laravel.log` (append mode)
- cannot write `bootstrap/cache/config.php`

### Root cause
The user running artisan (or the PHP-FPM user) can’t write to:
- `storage/`
- `bootstrap/cache/`

This often happens after running `composer`, `npm`, or `artisan` as `root`, creating root-owned files.

### Fix (recommended ownership model)
Keep deploy user (e.g. `tony`) and the web/PHP user group (often `www-data`) sharing writable dirs:

```bash
cd /var/www/ef
sudo mkdir -p storage/logs bootstrap/cache storage/framework/{sessions,views,cache}
sudo chown -R tony:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
sudo touch storage/logs/laravel.log
sudo chmod 664 storage/logs/laravel.log
```

Then:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

### Tip
Avoid running `composer`/`npm`/`artisan` as `root` inside the project directory.

