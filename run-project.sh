#!/usr/bin/env bash
set -euo pipefail

project="${1:?Project name is required}"
project_root="$(cd "$(dirname "$0")" && pwd)"
root="$(cd "$project_root/.." && pwd)"
need() { command -v "$1" >/dev/null || { echo "'$1' is required. ${2:-}" >&2; exit 1; }; }
node_bootstrap() { cd "$1"; need npm 'Install Node.js LTS.'; [[ -d node_modules ]] || npm install; }
pnpm_bootstrap() { cd "$1"; need pnpm 'Enable Corepack (corepack enable) or install pnpm.'; [[ -d node_modules ]] || pnpm install; }
mysql_bootstrap() {
  if command -v mysqladmin >/dev/null && mysqladmin ping --silent >/dev/null 2>&1; then return; fi
  command -v brew >/dev/null || { echo 'MySQL/MariaDB is required but Homebrew is unavailable.' >&2; exit 1; }
  brew services start mysql
  for _ in {1..20}; do command -v mysqladmin >/dev/null && mysqladmin ping --silent >/dev/null 2>&1 && return; sleep 1; done
  echo 'MySQL/MariaDB did not become available on port 3306.' >&2; exit 1
}
laravel_bootstrap() { cd "$1"; need php 'Install PHP.'; need composer 'Install Composer.'; if [[ ! -f .env && -f .env.example ]]; then cp .env.example .env; php artisan key:generate --force; fi; [[ -f vendor/autoload.php ]] || composer install; [[ ! -f package.json || -d node_modules ]] || { need npm 'Install Node.js LTS.'; npm install; }; [[ "${2:-}" == mysql ]] && mysql_bootstrap; }
open_task() {
  local title="$1" dir="$2" boot="$3" cmd="$4"
  local payload="cd $(printf '%q' "$dir"); $boot; exec $cmd"
  if [[ "$(uname)" == Darwin ]]; then
    local apple_payload="${payload//\\/\\\\}"
    apple_payload="${apple_payload//\"/\\\"}"
    osascript -e "tell application \"Terminal\" to do script \"$apple_payload\""
  else
    bash -lc "$payload" &
  fi
  echo "Started $title"
}
export -f need node_bootstrap pnpm_bootstrap mysql_bootstrap laravel_bootstrap

case "$project" in
  Moven) open_task 'Moven API' "$root/Moven/laravel" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8010'; open_task 'Moven web' "$root/Moven/react" "node_bootstrap ." 'npm run dev -- --host 127.0.0.1 --port 3010'; open_task 'Moven Reverb' "$root/Moven/laravel" "laravel_bootstrap ." 'php artisan reverb:start --host=127.0.0.1 --port=8080' ;;
  Peykad) open_task 'Peykad API' "$root/Peykad/peykad-laravel" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8020'; open_task 'Peykad web' "$root/Peykad/peykad-react" "node_bootstrap ." 'npm run dev -- --host 127.0.0.1 --port 3020'; open_task 'Peykad Reverb' "$root/Peykad/peykad-laravel" "laravel_bootstrap ." 'php artisan reverb:start --host=127.0.0.1 --port=8081' ;;
  Revaal) open_task 'Revaal API' "$root/Revaal/backend" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8030'; open_task 'Revaal web' "$root/Revaal/frontend" "node_bootstrap ." 'npm run dev -- --hostname 127.0.0.1 --port 3030' ;;
  CMS-shopping) open_task 'CMS API' "$root/CMS-shopping/cms_laravel" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8040'; open_task 'CMS web' "$root/CMS-shopping/cms_next" "node_bootstrap ." 'npm run dev -- --hostname 127.0.0.1 --port 3040' ;;
  Classino) open_task 'Classino API' "$root/Classino/backend" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8050'; open_task 'Classino web' "$root/Classino/frontend" "node_bootstrap ." 'npm run dev -- --host 127.0.0.1 --port 3060' ;;
  NFT) open_task 'NFT API' "$root/NFT/backend" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8060'; open_task 'NFT web' "$root/NFT/frontend" "node_bootstrap ." 'npm run dev -- --hostname 127.0.0.1 --port 3070' ;;
  AirSend) open_task 'AirSend Laravel' "$root/AirSend/Laravel" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8005'; open_task 'AirSend Django' "$root/AirSend/Django" "need python 'Install Python 3.'" 'python manage.py migrate && python manage.py runserver 127.0.0.1:3005' ;;
  Fe26) open_task 'Fe26' "$root/Fe26" "laravel_bootstrap . mysql" 'php artisan serve --host=127.0.0.1 --port=8090'; open_task 'Fe26 Vite' "$root/Fe26" "laravel_bootstrap . mysql" 'npm run dev -- --host 127.0.0.1 --port 3090' ;;
  FakeAPI) open_task 'FakeAPI' "$root/FakeAPI" "laravel_bootstrap ." 'php artisan serve --host=127.0.0.1 --port=8100'; open_task 'FakeAPI Vite' "$root/FakeAPI" "laravel_bootstrap ." 'npm run dev -- --host 127.0.0.1 --port 3100' ;;
  Fe26-next) open_task 'Fe26 Next' "$root/Fe26-next" "node_bootstrap ." 'npm run dev -- --hostname 127.0.0.1 --port 3000' ;;
  NorthLore) open_task NorthLore "$root/NorthLore" "node_bootstrap ." 'npm run dev -- --host 127.0.0.1 --port 3001' ;;
  Zaryan-goldshop) open_task 'Zaryan Goldshop' "$root/Zaryan-goldshop" "node_bootstrap ." 'npm run dev -- --hostname 127.0.0.1 --port 3002' ;;
  Bedrock) open_task Bedrock "$root/Bedrock/bedrock" "node_bootstrap ." 'npm run dev' ;;
  ZipLink) open_task ZipLink "$root/ZipLink" "need python 'Install Python 3.'" 'python manage.py migrate && python manage.py runserver 127.0.0.1:8110' ;;
  n9n) open_task 'n9n infrastructure' "$root/n9n" "need docker 'Install Docker Desktop.'" 'docker compose up -d'; open_task 'n9n development' "$root/n9n" "pnpm_bootstrap ." 'pnpm dev' ;;
  *) echo "Unknown project: $project" >&2; exit 1 ;;
esac
