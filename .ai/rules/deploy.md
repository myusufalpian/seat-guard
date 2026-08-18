---
paths:
  - 'deploy/**,docker-compose.yml,config/octane.php'
---

# Deploy

## API prefix /api/v1 and FrankenPHP worker layout
Health/readiness endpoints live at /api/v1/{healthz,readyz} (apiPrefix 'api/v1' set in bootstrap/app.php). FrankenPHP Octane worker stub is at public/frankenphp-worker.php (gitignored); DB lock_timeout/statement_timeout defaults are set server-side in docker-compose postgres command, not in Laravel config.
