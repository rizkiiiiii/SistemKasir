<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Hapus Function Lama
        DB::unprepared('DROP FUNCTION IF EXISTS generate_invoice_code');

        // 2. Buat Function Baru (Lebih Pinter)
        // Bedanya: Ada tambahan "AND invoice_code LIKE 'INV-%'"
        DB::unprepared('
            CREATE FUNCTION generate_invoice_code() RETURNS VARCHAR(20)
            DETERMINISTIC
            BEGIN
                DECLARE new_code VARCHAR(20);
                DECLARE last_no INT;

                SELECT IFNULL(MAX(CAST(RIGHT(invoice_code, 3) AS UNSIGNED)), 0) INTO last_no
                FROM transactions
                WHERE DATE(transaction_date) = CURDATE()
                AND invoice_code LIKE "INV-%"; -- <--- INI TAMBAHANNYA

                SET new_code = CONCAT("INV-", DATE_FORMAT(NOW(), "%Y%m%d"), "-", LPAD(last_no + 1, 3, "0"));
                RETURN new_code;
            END
        ');
    }

    public function down()
    {
        // Balikin ke versi lama kalau rollback (optional)
    }
};
