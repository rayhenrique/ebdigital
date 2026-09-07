# Database Schema - Caderneta EBD

## Estrutura Relacional e Entidades

### 1. Tabela: `users`
* `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
* `name`: `VARCHAR(255) NOT NULL`
* `email`: `VARCHAR(255) UNIQUE NOT NULL`
* `password`: `VARCHAR(255) NOT NULL`
* `role`: `ENUM('admin', 'secretario', 'professor') NOT NULL DEFAULT 'professor'`
* `is_active`: `BOOLEAN NOT NULL DEFAULT TRUE`
* `remember_token`: `VARCHAR(100) NULL`
* `timestamps`

### 2. Tabela: `classes` (Model: EbdClass)
* `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
* `name`: `VARCHAR(150) NOT NULL`
* `description`: `VARCHAR(255) NULL`
* `is_active`: `BOOLEAN NOT NULL DEFAULT TRUE`
* `timestamps`

### 3. Tabela: `class_teacher` (Pivot)
* `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
* `class_id`: `FOREIGN KEY -> classes(id) ON DELETE CASCADE`
* `user_id`: `FOREIGN KEY -> users(id) ON DELETE CASCADE`
* `created_at`: `TIMESTAMP NULL`
* `UNIQUE KEY`: `unique_class_teacher (class_id, user_id)`

### 4. Tabela: `students`
* `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
* `class_id`: `FOREIGN KEY -> classes(id) ON DELETE RESTRICT`
* `name`: `VARCHAR(255) NOT NULL`
* `phone`: `VARCHAR(20) NULL`
* `birth_date`: `DATE NULL`
* `is_active`: `BOOLEAN NOT NULL DEFAULT TRUE`
* `timestamps`
* `INDEX`: `idx_class_active (class_id, is_active)`

### 5. Tabela: `lesson_records` (Cabeçalho da Aula & Totais)
* `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
* `class_id`: `FOREIGN KEY -> classes(id) ON DELETE RESTRICT`
* `registered_by`: `FOREIGN KEY -> users(id) ON DELETE RESTRICT`
* `lesson_date`: `DATE NOT NULL`
* `lesson_number`: `VARCHAR(50) NULL`
* `lesson_title`: `VARCHAR(255) NULL`
* `visitors_count`: `INT UNSIGNED NOT NULL DEFAULT 0`
* `bibles_count`: `INT UNSIGNED NOT NULL DEFAULT 0`
* `magazines_count`: `INT UNSIGNED NOT NULL DEFAULT 0`
* `offerings_amount`: `DECIMAL(10,2) NOT NULL DEFAULT 0.00`
* `observations`: `TEXT NULL`
* `timestamps`
* `UNIQUE KEY`: `unique_class_lesson_date (class_id, lesson_date)`
* `INDEX`: `idx_lesson_date (lesson_date)`

### 6. Tabela: `lesson_attendances` (Presença Individual)
* `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
* `lesson_record_id`: `FOREIGN KEY -> lesson_records(id) ON DELETE CASCADE`
* `student_id`: `FOREIGN KEY -> students(id) ON DELETE RESTRICT`
* `is_present`: `BOOLEAN NOT NULL DEFAULT FALSE`
* `timestamps`
* `UNIQUE KEY`: `unique_record_student (lesson_record_id, student_id)`

### 7. Tabela: `audit_logs`
* `id`: `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
* `user_id`: `FOREIGN KEY -> users(id) ON DELETE SET NULL NULLABLE`
* `action`: `VARCHAR(100) NOT NULL`
* `auditable_type`: `VARCHAR(255) NOT NULL`
* `auditable_id`: `BIGINT UNSIGNED NOT NULL`
* `payload_before`: `JSON NULL`
* `payload_after`: `JSON NULL`
* `ip_address`: `VARCHAR(45) NULL`
* `user_agent`: `TEXT NULL`
* `created_at`: `TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP`
* `INDEX`: `idx_audit_created_at (created_at)`