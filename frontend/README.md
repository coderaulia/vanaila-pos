# Frontend

Static SvelteKit frontend for the Vanaila POS role experiences.

## Routes

- `/` overview
- `/auth/login` login shell
- `/cashier` mobile-first POS terminal
- `/admin` desktop dashboard
- `/superadmin` desktop control tower

## Local Run

```bash
cp .env.example .env
npm install
npm run dev
```

## Validation

```bash
npm run check
npm run lint
npm run build
```

## Deployment

Upload the contents of `build/` to Hostinger `public_html/`.

Keep `.htaccess` in place so direct route visits continue to resolve through `200.html`.
