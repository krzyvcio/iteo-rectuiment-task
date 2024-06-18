# Instalacja i uruchomienie serwera
Aby uruchomić serwer zgodnie z instrukcjami zawartymi w pliku `README.md`, wykonaj następujące kroki:

1. Uruchom Docker, wpisując w terminalu następujące polecenie:
```bash
docker compose up -d
```
2. Zainstaluj zależności za pomocą narzędzia Composer, wpisując w terminalu:
```bash
composer install
```
3. Uruchom serwer Symfony, wpisując w terminalu:
```bash
symfony server:start
```
4. Uruchom skrypt do zasiewania bazy danych, wpisując w terminalu:
```bash
php bin/console app:seed-database
```

Upewnij się, że wszystkie te polecenia są wykonywane w katalogu głównym projektu.