# Student Management System

A simple Laravel + Vue.js application for managing students and their custom fields.

---

## Features

- Add, edit, and delete students
- Add, edit, and delete custom fields (with data type and required option)
- Assign custom field values to each student
- Dynamic student table columns based on current custom fields
- Required custom fields are enforced in the student form

---

## Tech Stack

- **Backend:** Laravel
- **Frontend:** Vue.js 3
- **Database:** MySQL

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/BedarAsad/StudentManagementCrud.git
cd student-management-system
```

### 2. Install Backend Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Configure Environment

- Copy `.env.example` to `.env` and set your database credentials.
- Generate app key:

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Serve the Application

- Start Laravel backend:

```bash
php artisan serve
```

- Start Vite dev server for Vue frontend:

```bash
npm run dev
```

---

## Usage

- Visit [http://localhost:8000](http://localhost:8000) (or the port shown in your terminal).
- Use the UI to add students and configure custom fields.
- Custom fields will appear as columns in the student list and as inputs in the student form.

---

## Project Structure

- `app/Models/` — Eloquent models (`Student`, `CustomField`, `StudentCustomFieldValue`)
- `app/Http/Controllers/` — API controllers
- `resources/js/pages/` — Vue.js pages (`Homepage.vue`, `AddStudent.vue`, `ConfigureCustomList.vue`)
- `database/migrations/` — Migration files for tables

---

## API Endpoints

- `GET /students` — List students with custom field values
- `POST /students` — Add student (with custom fields)
- `PUT /students/{id}` — Update student
- `DELETE /students/{id}` — Delete student
- `GET /custom-fields` — List custom fields
- `POST /custom-fields` — Add custom field
- `PUT /custom-fields/{id}` — Update custom field
- `DELETE /custom-fields/{id}` — Delete custom field

---

## Notes

- All custom field values are stored as strings in the database.
- Required custom fields are enforced both in the UI and backend validation.
- The student table dynamically updates columns when custom fields are added or removed.

---

##
