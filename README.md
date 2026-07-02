# Face Recognition Attendance Module

## Overview

This module provides AI-based face recognition attendance for the Employee Management System.

### Features

- Face Registration
- Face Verification
- AI Descriptor Generation
- Automatic Check-In
- Automatic Check-Out
- Working Hours Calculation
- Overtime Calculation
- Half Day Detection
- Today's Attendance API
- One Attendance Record Per Day

---

# Known Limitations

- Authentication is not yet integrated.
- Employee identification currently uses `empid` from the request.
- GPS verification is not implemented.
- Face liveness detection is not implemented.
- Shift-wise attendance is not implemented.

# Technology Stack

### Backend

- Laravel 12

### Face Recognition

- FastAPI
- InsightFace
- ONNX Runtime

### Database

- MySQL

---

# Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── AttendanceController.php
│   ├── Requests/
│   │   ├── RegisterFaceRequest.php
│   │   └── MarkAttendanceRequest.php
│
├── Models/
│   ├── User.php
│   └── Attendance.php
│
├── Services/
│   ├── AttendanceService.php
│   └── FaceRecognitionService.php

face-ai/
├── app.py
├── services/
│   └── face_service.py
```

---

# Installation

## 1. Clone Repository

```bash
git clone <repository-url>
cd employee-crud
```

---

## 2. Install Laravel Dependencies

```bash
composer install
```

---

## 3. Environment Configuration

Copy the environment file.

```bash
cp .env.example .env
```

Generate application key.

```bash
php artisan key:generate
```

Update database configuration inside `.env`

```env
DB_DATABASE=attendance_company
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Configure Face AI server URL.

```env
FACE_AI_URL=http://127.0.0.1:8001
```

---

## 4. Install Python Dependencies

Move to Face AI project.

```bash
cd face-ai
```

Activate virtual environment (Windows)

```bash
.venv\Scripts\activate
```

Install required packages.

```bash
pip install -r requirements.txt
```

---

## 5. Run Face AI Server

```bash
uvicorn app:app --reload --port 8001
```

Swagger Documentation

```
http://127.0.0.1:8001/docs
```

---

## 6. Run Laravel Server

```bash
php artisan serve
```

Laravel will start at

```
http://127.0.0.1:8000
```

---

# Database Changes

## Users Table

Additional columns required.

```
face_descriptor
is_face_registered
face_registered_at
```

---

## Attendances Table

```
id
employee_id
date

attendance_type
attendance_state
status

check_in_time
check_out_time

worked_minutes
overtime_minutes

created_at
updated_at
```

---

# API Documentation

## 1. Register Face

### Endpoint

```
POST /api/me/register-face
```

### Request

Form Data

```
empid
image
```

### Success Response

```json
{
    "success": true,
    "message": "Face registered successfully."
}
```

---

## 2. Mark Attendance

### Endpoint

```
POST /api/attendance/mark-from-image
```

### Request

Form Data

```
empid
image
```

### Response (Check In)

```json
{
    "success": true,
    "action": "CHECK_IN",
    "message": "Check In successful."
}
```

### Response (Check Out)

```json
{
    "success": true,
    "action": "CHECK_OUT",
    "message": "Check Out successful.",
    "worked_minutes": 480,
    "overtime_minutes": 0,
    "attendance_type": "FULL_DAY"
}
```

### Response (Attendance Completed)

```json
{
    "success": true,
    "action": "COMPLETED",
    "message": "Attendance already completed for today."
}
```

### Response (Face Verification Failed)

```json
{
    "success": false,
    "message": "Face verification failed."
}
```

---

## 3. Get Today's Attendance

### Endpoint

```
GET /api/attendance/today
```

### Current Request

Query Parameter

```
empid
```

Example

```
GET /api/attendance/today?empid=EMP002
```

### Current Response

```json
{
    "success": true,
    "data": {
        "attendanceState": "CHECKED_OUT",
        "checkInTime": "2026-07-02 17:15:05",
        "checkOutTime": "2026-07-02 17:30:00",
        "workedMinutes": 15,
        "workedHours": 0.25,
        "status": "PRESENT"
    }
}
```

---

# Attendance Workflow

```
Register Face
        │
        ▼
Generate Face Descriptor
        │
        ▼
Store Descriptor in User Table
        │
        ▼
Daily Attendance
        │
        ▼
Generate Current Descriptor
        │
        ▼
Compare Face
        │
        ▼
Matched?
   │             │
  No            Yes
  │              │
  ▼              ▼
Return      Find Today's Attendance
                    │
         ┌──────────┴──────────┐
         │                     │
   No Record             Record Exists
         │                     │
         ▼                     ▼
     CHECK_IN          attendance_state
                              │
                  ┌───────────┴───────────┐
                  │                       │
            CHECKED_IN             CHECKED_OUT
                  │                       │
                  ▼                       ▼
             CHECK_OUT          Attendance Completed
                  │
                  ▼
      Calculate Working Minutes
                  │
                  ▼
          Calculate Overtime
                  │
                  ▼
          Update Attendance
```

---

# Business Rules

## Full Day

```
Worked Minutes >= 240
```

---

## Half Day

```
Worked Minutes < 240
```

---

## Overtime

```
Worked Minutes > 480

Overtime = Worked Minutes - 480
```

---

# Temporary Implementation

Since authentication is not integrated yet, the employee is currently identified using **empid**.

Current implementation:

```php
$user = User::where('empid', $request->empid)->first();
```

Current Request

```
form-data

empid
image
```

---

# Authentication Integration (Future)

After authentication is integrated, replace

```php
$user = User::where('empid', $request->empid)->first();
```

with

```php
$user = $request->user();
```

or

```php
$user = auth()->user();
```

Request format will become

```
Authorization

Bearer <JWT Token>

image
```

`empid` will no longer be required.

---

# Future Enhancements

- GPS Verification
- Face Liveness Detection
- Multiple Face Rejection
- Shift Management
- Leave Management
- Holiday Calendar
- Late Arrival Detection
- Early Checkout Detection
- Monthly Attendance Report
- Attendance Analytics Dashboard

---

# Notes

- Face descriptors are stored in the **users** table.
- Attendance records are stored in the **attendances** table.
- Uploaded images are temporary and are automatically deleted after processing.
- Face verification is performed using InsightFace embeddings through FastAPI.

---

# Testing Checklist

## Register Face

- Register new face
- Prevent duplicate registration

## Attendance

- Check In
- Check Out
- Attendance Completed
- Face Verification Failed
- Face Not Registered
- Get Today's Attendance
