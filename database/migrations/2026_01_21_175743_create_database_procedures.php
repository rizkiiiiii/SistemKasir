<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. STORED FUNCTION: Generate Invoice Code Otomatis (String & Date Function)
        // Format: INV-YYYYMMDD-XXX (e.g., INV-20251020-001)
        DB::unprepared('
            CREATE FUNCTION generate_invoice_code() RETURNS VARCHAR(20)
            DETERMINISTIC
            BEGIN
                DECLARE new_code VARCHAR(20);
                DECLARE last_no INT;

                -- Ambil urutan terakhir hari ini
                SELECT IFNULL(MAX(CAST(RIGHT(invoice_code, 3) AS UNSIGNED)), 0) INTO last_no
                FROM transactions
                WHERE DATE(transaction_date) = CURDATE();

                SET new_code = CONCAT("INV-", DATE_FORMAT(NOW(), "%Y%m%d"), "-", LPAD(last_no + 1, 3, "0"));
                RETURN new_code;
            END
        ');

        // 2. TRIGGER: Kurangi Stok Otomatis saat Detail Transaksi Masuk
        DB::unprepared('
            CREATE TRIGGER reduce_stock_after_insert
            AFTER INSERT ON transaction_details
            FOR EACH ROW
            BEGIN
                UPDATE products
                SET stock = stock - NEW.quantity
                WHERE id = NEW.product_id;
            END
        ');

        // 3. STORED PROCEDURE: Laporan Penualan Harian (Aggregate Function)
        DB::unprepared('
            CREATE PROCEDURE get_daily_sales_report(IN target_date DATE)
            BEGIN
                SELECT
                    p.name as product_name,
                    SUM(td.quantity) as total_qty,
                    SUM(td.subtotal) as total_revenue
                FROM transaction_details td
                JOIN products p ON td.product_id = p.id
                JOIN transactions t ON td.transaction_id = t.id
                WHERE DATE(t.transaction_date) = target_date
                GROUP BY p.name;
            END
        ');

        // 4. VIEW: Dashboard Summary (Gabungan user, transaksi, total)
        DB::unprepared('
            CREATE VIEW view_dashboard_summary AS
            SELECT
                DATE(transaction_date) as tgl,
                COUNT(id) as total_transaksi,
                SUM(total_amount) as omzet,
                (SELECT name FROM users WHERE id = t.user_id) as kasir
            FROM transactions t
            WHERE status = "completed"
            GROUP BY tgl, user_id
        ');
    }

    public function down()
    {
        DB::unprepared('DROP VIEW IF EXISTS view_dashboard_summary');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_daily_sales_report');
        DB::unprepared('DROP TRIGGER IF EXISTS reduce_stock_after_insert');
        DB::unprepared('DROP FUNCTION IF EXISTS generate_invoice_code');
    }
};
