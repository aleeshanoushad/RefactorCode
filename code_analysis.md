Security Vulnerabilities
    1. SQL injection
    Directly using $_GET['id'], $_GET['site_id'] and $booking['email'] in SQL queries.
    2. No input Validation 
     id and site_id are not validated as integers
    3.Sensitive Error Exposure
        die(mysqli_connect_error()) exposes DB details.
    4.No Connection Error Handling
        mysqli_query() failures are not checked.
    5.Hardcoded DB Credentials
        Credentials are directly stored in PHP files.

Code Quality Issues

    1.there is no Procedural & Monolithic Code
        Everything is in one file.
    2.there is no Separation of Concerns
        DB logic, business logic, and response handling mixed.
    3.Don't reused the Reusable Functions
        DB operations are duplicated.
    4.hardcoded status
        Status 'confirmed' hardcoded.

Performance Problems

SELECT * Usage - Fetches unnecessary columns.
Multiple DB Connections - No reuse or pooling.
Unconditional UPDATE - Booking status updated even if already confirmed.

Best Practice Violations

No prepared statements
No HTTP response codes
No centralized error handling
No environment-based configuration