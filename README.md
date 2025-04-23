# Symfony 7 Booking website

## 📌 Why This Repository?

This repository serves as a **technical playground** to experiment with features in **Symfony 7**. It is not intended to be a production-ready project, but rather a hands-on space for me to refine and improve my skills with Symfony and API development.

The `API` folder under `src` is designed as a backend data provider for a separate **React** frontend project.

---

## 🔍 Areas of Technical Exploration

### 1. API Development, Serialization, and Object Validation

This project explores API development in Symfony, with a focus on structured data delivery, custom serialization, and clean controller logic.

#### 🛠 Example: `ServiceController`

Fetches all services for a specific business and serializes the output using serialization groups.

```php
#[Route('/api/business/{businessId}/service', name: 'api_business_service', methods: ['GET'])]
public function getServiceAllByBusinessId(...) {
    ...
    return $this->json($services, 200, [], ['groups' => 'service.client']);
}
````

### 🔗 Related Entity Retrieval

- Retrieves related entities (e.g., `Service` linked to a `Business`)
- Uses Symfony's serializer with a custom group (`service.client`) to limit data exposure
- Returns a clean, structured JSON response

---

### 🕒 Example: `AvailabilityController`

Generates availability data for the next 7 days starting from a given date, with daily intervals and human-readable formatting.

```php
#[Route('api/availability/{businessId}/{weekStartDate}', name: 'api_business_availability', methods: ['GET'])]
public function getAvailabilityByBusiness(...) {
    ...
    return $this->json($availabilitiesArray);
}
````

- Dynamically maps availability to weekdays
- Formats output with readable labels and ISO-compliant dates
- Handles interval timing, start/end time windows
- Shapes data specifically for frontend consumption

## 🕰️ Timezone-Sensitive Data Handling
This project implements timezone-aware logic using DataTransformers and Symfony Forms:

UTC times are converted to the user’s local timezone

Prevents double-booking by checking overlapping appointment logic

Maintains consistent time data across global users

## 🔐 Security Voters
Implements custom security voters for fine-grained permission control:

Grants or denies access based on user roles and ownership

Enables contextual logic (e.g., user can edit their own resources but not others’)

Centralized, reusable security rules

## 📁 Project Structure Highlights
````
src/
└── Controller/
    └── API/
        ├── ServiceController.php
        └── AvailabilityController.php
    └── ...
````
