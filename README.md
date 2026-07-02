1. Database Changes
   Run Migration
   Execute:
   php artisan migrate
   Migration adds the following columns to the existing users table.

No new tables are created.
Only the existing users table is modified.

2. iN root foler run : php artisan serve
   then cd face-ai : venv\Scripts\activate

    FastAPI server start karo

uvicorn app:app --reload

1.  .env file mein

Ye line honi chahiye:

FACE_AI_URL=http://127.0.0.1:8001

Ya jo bhi port tumne FastAPI ke liye rakha hai.

Aur config/services.php mein:

'face_ai' => [
'url' => env('FACE_AI_URL'),
],

Existing attendences table current requirement support nahi karti thi. Face-based attendance implementation ke liye naya schema create kiya gaya hai. Existing HRMS reports ke according mapping later ki ja sakti hai.

app/
Models/
Attendance.php

database/
migrations/
2026_xx_xx_create_attendances_table.php

      | Column           | Purpose                 |

| ---------------- | ----------------------- |
| id | Primary Key |
| employee_id | Employee ID |
| date | One attendance per day |
| attendance_type | FULL_DAY, HALF_DAY |
| attendance_state | CHECKED_IN, CHECKED_OUT |
| status | PRESENT, ABSENT |
| check_in_time | Check In |
| check_out_time | Check Out |
| worked_minutes | Total Work |
| overtime_minutes | OT |
| created_at | Laravel |
| updated_at | Laravel |

php artisan migrate
