<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. VIEW: Stok Menipis
        // Nampilin produk yang stoknya kurang dari 10
        DB::unprepared("
            CREATE VIEW view_stok_menipis AS
            SELECT name, stock, code
            FROM products
            WHERE stock < 10
            ORDER BY stock ASC;
        ");

        // 2. STORED PROCEDURE: Produk Terlaris
        // Ngitung top 5 produk bulan ini
        DB::unprepared("
            CREATE PROCEDURE sp_produk_terlaris()
            BEGIN
                SELECT p.name, SUM(td.quantity) as total_sold
                FROM transaction_details td
                JOIN transactions t ON td.transaction_id = t.id
                JOIN products p ON td.product_id = p.id
                WHERE MONTH(t.transaction_date) = MONTH(CURRENT_DATE())
                AND YEAR(t.transaction_date) = YEAR(CURRENT_DATE())
                GROUP BY p.name
                ORDER BY total_sold DESC
                LIMIT 5;
            END
        ");

        // 3. STORED FUNCTION: Cek Kenaikan Omset
        // Bandingin omzet hari ini vs kemarin (output: '+10%' atau '-5%')
        DB::unprepared("
            CREATE FUNCTION sf_cek_kenaikan_omzet() RETURNS VARCHAR(10)
            DETERMINISTIC
            BEGIN
                DECLARE omzet_today DECIMAL(15,2);
                DECLARE omzet_yesterday DECIMAL(15,2);
                DECLARE growth DECIMAL(5,1);
                DECLARE result VARCHAR(10);

                SELECT IFNULL(SUM(total_amount), 0) INTO omzet_today
                FROM transactions WHERE DATE(transaction_date) = CURDATE();

                SELECT IFNULL(SUM(total_amount), 0) INTO omzet_yesterday
                FROM transactions WHERE DATE(transaction_date) = SUBDATE(CURDATE(), 1);

                IF omzet_yesterday = 0 THEN
                    SET result = 'N/A';
                ELSE
                    SET growth = ((omzet_today - omzet_yesterday) / omzet_yesterday) * 100;
                    IF growth > 0 THEN
                        SET result = CONCAT('+', growth, '%');
                    ELSE
                        SET result = CONCAT(growth, '%');
                    END IF;
                END IF;

                RETURN result;
            END
        ");
    }

    public function down()
    {
        DB::unprepared("DROP VIEW IF EXISTS view_stok_menipis");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_produk_terlaris");
        DB::unprepared("DROP FUNCTION IF EXISTS sf_cek_kenaikan_omzet");
    }
};
