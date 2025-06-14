# DrinkAdvisor

Aplikacja webowa do oceniania i komentowania trunków, napisana w PHP z wykorzystaniem PostgreSQL i Docker.

## Funkcje

- Rejestracja i logowanie użytkowników
- Podział na użytkowników i administratorów
- Przeglądanie trunków, sortowanie (trendujące, najlepsze, najgorsze, najdroższe, najtańsze)
- Dodawanie ocen i komentarzy do trunków (jedna opinia na użytkownika, możliwość edycji)
- Dodawanie/usuwanie trunków do ulubionych
- Panel administratora (dodawanie, usuwanie trunków i użytkowników)
- Wyszukiwanie użytkowników i przeglądanie ich profili
- Bezpieczne hasła (hashowanie)
- Utrzymywanie sesji

## Wymagania

- Docker + Docker Compose

## Uruchomienie

1. **Sklonuj repozytorium:**
   ```
   git clone <https://github.com/Gqrecki/WdPAI.git>
   cd WdPAI
   ```

2. **Uruchom aplikację:**
   ```
   docker-compose up --build
   ```

3. **Aplikacja będzie dostępna pod adresem:**  
   [http://localhost:8080](http://localhost:8080)

4. **Panel administracyjny:**  
   Domyślny admin:  
   - login: `admin`  
   - hasło: `admin123`

5. **Panel bazy danych (pgAdmin):**  
   [http://localhost:5050](http://localhost:5050)  
   - email: `admin@example.com`  
   - hasło: `admin`

## Struktura katalogów

- `public/` – pliki widoków i stylów
- `src/` – kontrolery, modele, repozytoria
- `app/sql/` – pliki SQL do inicjalizacji bazy
- `docker/` – pliki Dockerfile

## Najważniejsze pliki

- `docker-compose.yaml` – konfiguracja usług
- `Database.php` – połączenie z bazą danych
- `src/controllers/` – logika aplikacji
- `public/views/` – widoki (HTML + PHP)
- `public/styles/style.css` – stylizacja

---

Projekt na przedmiot WdPAI, Politechnika Krakowska.
