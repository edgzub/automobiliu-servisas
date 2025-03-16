# Automobilių Serviso Sistema - Architektūrinis Aprašymas

## Technologijos
- Backend: Laravel 10.x (PHP 8.2)
- Duomenų bazė: MySQL 8.0
- Frontend: Blade templates, Tailwind CSS
- Testavimas: PHPUnit
- API: RESTful JSON API mobiliai aplikacijai

## Architektūriniai Sprendimai

### Projektavimo Šablonai
1. Kūrimo (Creational):
   - Factory Pattern (OrderFactory) - užsakymų kūrimo logikos centralizavimas
   - Singleton Pattern (ServiceConfig) - bendros konfigūracijos valdymas

2. Struktūriniai (Structural):
   - Adapter Pattern (VehicleDataAdapter) - išorinių duomenų adaptavimas
   - Decorator Pattern (ServiceDecorator) - dinaminis paslaugų funkcionalumo išplėtimas

3. Elgesio (Behavioral):
   - Observer Pattern (OrderObserver) - reakcija į užsakymų būsenos pasikeitimus
   - Strategy Pattern (PricingStrategy) - skirtingų kainų skaičiavimo strategijų pritaikymas
   - Command Pattern (OrderProcessCommand) - užsakymo apdorojimo komandos

### Duomenų Bazės Schema
- Clients (1:N) Cars - klientas gali turėti daug automobilių
- Cars (1:N) Orders - automobilis gali turėti daug užsakymų
- Orders (N:1) Services - užsakymas turi vieną paslaugą
- Orders (N:1) Mechanics - užsakymas priskirtas vienam mechanikui
- Orders (N:1) Parts - užsakymas gali turėti dalį

### Algoritmai
1. Paieška: Implementuota per SearchService klasę
   - Vykdo tekstinę paiešką per kelis laukus 
   - Naudoja LIKE operatorius ir OR sąlygas
   - Grąžina surikiuotus pagal tinkamumą rezultatus

2. Filtravimas: Realizuotas per filter metodus
   - Dinaminis užklausų formavimas pagal įvairius kriterijus
   - Naudoja query builder ir where sąlygas
   - Palaiko datų, skaičių ir tekstinių laukų filtravimą

3. Rūšiavimas: Daugiapakopis rūšiavimas
   - Rūšiavimas pagal bet kurį lentelės stulpelį
   - Galimybė nurodyti rūšiavimo kryptį
   - Sudėtinis rūšiavimas pagal kelis kriterijus

## API Dokumentacija
Mobiliai aplikacijai pateikiamas REST API:

- GET /api/orders - grąžina visus užsakymus
- GET /api/orders/{id} - grąžina konkretų užsakymą
- POST /api/orders - sukuria naują užsakymą
- PUT /api/orders/{id} - atnaujina užsakymą
- DELETE /api/orders/{id} - ištrina užsakymą

Analogiški endpointai pateikiami ir kitiems resursams (services, mechanics, cars, parts). 