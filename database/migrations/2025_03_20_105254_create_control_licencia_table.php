<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('control_licencia', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('cliente', 200);
            $table->string('estatus', 50);
            $table->string('tipo', 100);
            $table->double('montovef')->default(0);
            $table->double('montousd')->default(0);
            $table->boolean('pagada')->default(0);
            $table->boolean('eliminada')->default(0);
        });

        DB::unprepared("
            INSERT INTO `control_licencia` (`id`, `fecha`, `cliente`, `estatus`, `tipo`, `montovef`, `montousd`, `pagada`, `eliminada`) VALUES
            (1, '2022-05-16 02:48:37', 'J-41084997-5 | REPUESTOS MULTICARS MJ, C.A.', 'Activa', 'LICENCIA SPOOLER FISCAL 1 AÑO', 0, 80, 1, 0),
            (2, '2022-05-16 02:52:50', 'J-00237260-5 | REPUESTOS FULL MOTORS, C.A.', 'Activa', 'LICENCIA SPOOLER FISCAL 1 AÑO', 0, 80, 0, 1),
            (3, '2022-05-16 03:01:21', 'J-30510084-5 | TECNO COLOR INDUSTRIAL 2010, C.A', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (4, '2022-05-16 03:08:55', 'J-29367230-9 | 2021 ENERGY GROUP, C.A', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 0, 1),
            (5, '2022-05-16 03:09:27', 'J-41084997-5 | REPUESTOS MULTICARS MJ, C.A.', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (6, '2022-05-16 03:09:54', 'J-00080560-1 | DISTRIBUIDORA BRIBER, C.A.', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (7, '2022-05-16 03:10:43', 'J-40377626-1 | FERREAUTO LOS TOTUMOS 67, C.A.', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (8, '2022-05-16 03:11:05', 'J-40754380-6 | SOLUCIONES ACJ 1938, C.A.', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (9, '2022-05-16 03:11:49', 'J-30733447-9 | INVERSIONES ESCASAN, C.A', 'Activa', 'LICENCIA SPOOLER FISCAL 1 AÑO', 0, 80, 1, 0),
            (10, '2022-05-16 03:12:30', 'J-00099734-9 | FERRETERIA PINTO Y MARTINS, C.A.', 'Activa', 'LICENCIA  PLUS 1 AÑO', 0, 250, 1, 0),
            (11, '2022-05-16 03:12:59', 'J-41029564-3 | AUTOREPUESTOS MECANICOS LC EXPRESS, C.A.', 'Activa', 'LICENCIA SPOOLER FISCAL 1 AÑO', 0, 80, 1, 0),
            (12, '2022-05-16 03:13:45', 'J-30596382-7 | Nak Depot C.A', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (13, '2022-05-16 03:14:34', 'J-50091041-0 | GRUPO EMPRESARIAL FRELAT, C.A', 'Activa', 'LICENCIA  PLUS 1 AÑO', 0, 250, 1, 0),
            (14, '2022-05-16 03:15:59', 'J-41029564-3 | AUTOREPUESTOS MECANICOS LC EXPRESS, C.A.', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (15, '2022-05-16 03:17:56', 'J-29630004-6 | REPRESENTACIONES FRAN CAR 21 C.A.', 'Activa', 'LICENCIA  PROF 1 AÑO', 0, 60, 1, 0),
            (16, '2022-05-16 03:19:31', 'J-40843317-6 | D TAPAS & ALGO MAS , C.A.', 'Activa', 'LICENCIA  ADM + REST 1 AÑO', 0, 200, 1, 0),
            (17, '2022-05-16 03:20:49', 'J-29693148-8 | EQUIPOS DE REFRIGERACION FRIMAX, C.A.', 'Activa', 'LICENCIA  NOM 1 AÑO', 0, 200, 1, 0),
            (18, '2022-05-16 03:23:25', 'V-09410803-5 | FELICIA J. INFANTE RODRIGUEZ', 'Activa', 'LICENCIA  CONT 1 AÑO', 0, 170, 1, 0),
            (19, '2022-05-16 03:26:50', 'J-40381230-6 | INVERSIONES PER MANGIARE, C.A', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 150, 1, 0),
            (20, '2022-05-16 03:27:14', 'J-31435637-2 | INVERSIONES KEIROS 1982, C.A.', 'Activa', 'LICENCIA  PLUS 1 AÑO', 0, 200, 1, 0),
            (21, '2022-05-16 03:27:43', 'J-31435637-2 | INVERSIONES KEIROS 1982, C.A.', 'Activa', 'LICENCIA TOOLPROV ', 0, 70, 1, 0),
            (22, '2022-05-16 03:30:24', 'J-30647151-0 | OFICENTER AVILA, C.A.', 'Activa', 'LICENCIA  PROF 1 AÑO', 0, 60, 0, 1),
            (23, '2022-05-16 05:32:36', 'J-41271264-0 | INVERSIONES DALE MARKET, C.A', 'Activa', 'DESBLOQUEO DE LICENCIA SAINT', 0, 30, 1, 0),
            (24, '2022-05-16 05:40:49', 'J-30693371-9 | CALZADOS TOP, C.A', 'Activa', 'LICENCIA PLUS 1 AÑO', 0, 230, 1, 0),
            (25, '2022-05-16 13:14:34', 'J-31647037-7 | A.L. SYSTEMS, C.A.', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (26, '2022-05-16 17:27:41', 'J-50209427-0 | CITTAPET VET, C.A', 'Activa', 'LICENCIA  ADM 1 AÑO', 0, 200, 1, 0),
            (27, '2022-05-17 15:47:15', 'J-00042918-9 | TALLER METALURGICO LUIS SANCHEZ HERMANOS, C.A.', 'Activa', 'LICENCIA  PLUS 1 AÑO', 0, 250, 1, 0),
            (28, '2022-05-17 17:38:23', 'J-41271264-0 | INVERSIONES DALE MARKET, C.A', 'Activa', 'DESBLOQUEO DE LICENCIA SAINT', 0, 30, 1, 0),
            (29, '2022-05-18 17:56:05', 'J-30798691-3 | FERRE-LLANES, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (30, '2022-05-18 22:50:31', 'J-50082158-1 | INVERSIONES PARTY KIDS STORE 2205, C.A.', 'Activa', 'LICENCIA ADM SAINT', 0, 200, 0, 1),
            (31, '2022-05-23 13:32:48', 'J-50103898-8 | INVERSIONES NAF BEAUTY, C.A', 'Activa', '	LICENCIA ADM SAINT - SABANA GRANDE', 0, 200, 1, 0),
            (32, '2022-05-25 15:24:27', 'J-31070642-5 | INVERSIONES MUCCA 2003, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (33, '2022-05-25 15:25:36', 'J-50103898-8 | INVERSIONES NAF BEAUTY, C.A', 'Activa', 'LICENCIA PLUS 1 AÑO - LA CANDELARIA', 0, 250, 1, 0),
            (34, '2022-05-25 17:18:59', 'J-40633205-4 | REPUESTOS MAUROCAR 2015, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO FACT 00006622', 0, 200, 1, 0),
            (35, '2022-05-27 19:16:55', 'J-31335024-9 | ATLANTAS CENTRO FERRETERO C.A', 'Activa', 'LICENCIA PLUS 1 AÑO', 0, 250, 1, 0),
            (36, '2022-05-30 15:53:39', 'J-31070642-5 | INVERSIONES MUCCA 2003, C.A.', 'Activa', 'DESBLOQUEO DE LICENCIA SAINT', 0, 30, 1, 0),
            (37, '2022-05-30 18:46:48', 'J-29836503-0 | SUPERIMPRESION 9080, C.A.', 'Activa', 'TOOLPROV', 0, 80, 1, 0),
            (38, '2022-06-01 12:20:48', 'J-50108659-1 | PORCELANA DE LA PASTORA LOS CORALES, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (39, '2022-06-01 12:21:29', 'J-40982039-4 | SPACIO BAÑO & CUCINA, C.A', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (40, '2022-06-01 12:22:24', 'J-40674509-0 | INVERSIONES NEMICA, C.A', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (41, '2022-06-01 12:28:55', 'J-00081530-5 | LENCERIAS RIVERA S.R.L.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 180, 1, 0),
            (42, '2022-06-01 15:30:11', 'J-29889444-0 | SERVICIOS NETVISION 360, C.A.', 'Activa', 'LICENCIA PLUS 1 AÑO', 0, 250, 1, 0),
            (43, '2022-06-02 17:51:58', 'J-50054156-2 | INVERSIONES L.A.V.I.P, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (44, '2022-06-06 15:03:34', 'J-30997381-9 | NATO SPORT, C.A', 'Activa', 'LICENCIA NOM 1 AÑO', 0, 200, 1, 0),
            (45, '2022-06-06 15:14:08', 'J-00111559-5 | FERRETERIA LA BOYERA , C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (46, '2022-06-06 15:19:00', 'J-50006219-2 | CERAMICAS DE LA PASTORA 09052018, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (47, '2022-06-06 15:19:28', 'J-30586038-6 | INVERSIONES FERRESTOP 2000, C.A.', 'Activa', 'INVERSIONES FERRESTOP 2000, C.A.', 0, 200, 0, 1),
            (48, '2022-06-06 15:20:04', 'J-31270793-3 | INVERSIONES FERRESTOP 2005, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (49, '2022-06-06 15:20:28', 'J-30586038-6 | INVERSIONES FERRESTOP 2000, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (50, '2022-06-06 15:21:10', 'J-31270793-3 | INVERSIONES FERRESTOP 2005, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (51, '2022-06-06 16:08:54', 'J-29889837-2 | VIDEO CONSOLAS MONTEVIDEO, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (52, '2022-06-06 18:33:26', 'J-30997381-9 | NATO SPORT, C.A', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (53, '2022-06-07 11:36:28', 'J-40595185-0 | INVERSIONES REPMEC C.A', 'Activa', 'LICENCIA ADM SAINT PRESUP 00015621', 0, 200, 1, 0),
            (54, '2022-06-17 22:44:47', 'J-40611952-0 | SUMINISTROS ESPAVEN 2006, C.A.', 'Activa', '2 LICENCIA ADM 1 AÑO', 0, 400, 1, 0),
            (55, '2022-06-22 14:48:47', 'J-30250200-4 | COMERCIAL TORNYFER, C.A', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (56, '2022-06-22 20:06:48', 'J-40793849-5 | EVATEL,C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (57, '2022-06-28 21:10:05', 'J-00304977-8 | FERRETERIA HOYO DE LA PUERTA, C. A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (58, '2022-06-30 21:41:16', 'J-29677012-3 | INVERSIONES JOSNAY 1515, C.A', 'Activa', 'HERRAMIENTA FISCAL 6 MESE - KRISTIAN', 0, 80, 0, 1),
            (59, '2022-07-11 15:20:25', 'J-30985641-3 | BAZAR LATINO, C.A.', 'Pendiente', 'LICENCIA PROF 1 AÑO', 0, 60, 1, 0),
            (60, '2022-07-15 19:21:44', 'J-29555978-0 | SECURITY SOLUTION 2008, C.A.', 'Activa', '	LICENCIA PLUS 1 AÑO', 0, 250, 1, 0),
            (61, '2022-07-19 19:09:20', 'J-00088481-1 | INVERSIONES DOBLE E SRL', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (62, '2022-07-21 16:23:55', 'J-41271264-0 | INVERSIONES DALE MARKET, C.A', 'Activa', 'DESBLOQUEO DE LICENCIA SAINT', 0, 30, 0, 1),
            (63, '2022-07-25 14:11:13', 'J-29732700-2 | Inversiones Abasoluciones 1220, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (64, '2022-07-28 18:38:25', 'J-29409555-0 | AGENTES LUISIHAM, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (65, '2022-07-28 18:39:17', 'J-40267877-0 | REPUESTOS FULLMOBIL, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (66, '2022-07-28 18:40:14', 'J-31452581-6 | LA CASA DEL FITTING, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 0, 1),
            (67, '2022-07-28 18:44:52', 'J-30795766-2 | VIVERES PALERMO, C.A.', 'Activa', 'LICENCIA ADM  1 AÑO', 0, 200, 1, 0),
            (68, '2022-07-28 18:45:17', 'J-31235162-4 | COOPERATIVA LOS PALMITOS 151, R.L.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 200, 1, 0),
            (69, '2022-08-08 16:34:22', 'J-29713812-9 | DISTRIBUIDORA J H N 1 666 S.S.S, C.A', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 210, 1, 0),
            (70, '2022-08-11 17:24:22', 'J-29637651-4 | INGENIERIA NY 2K, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (71, '2022-08-15 21:23:16', 'J-40578481-4 | MULTISERVICIOS SHADDAI, C.A.', 'Activa', 'LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (72, '2022-08-22 15:45:34', 'J-00108495-9 | CARLIS MODAS, C.A.', 'Activa', 'LICENCIA PLUS 1 AÑO', 0, 250, 1, 0),
            (73, '2022-08-22 19:48:05', 'J-40419086-4 | LIENZOS TEXTIL C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (74, '2022-08-22 19:48:42', 'J-50027631-1 | COMERCIALIZADORA DJMON, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (75, '2022-09-02 22:07:40', 'J-29973426-8 | MULTISERVICIOS FJ 2005 DEL ESTE', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (76, '2022-09-05 13:36:28', 'J-30596382-7 | Nak Depot C.A', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (77, '2022-09-05 13:36:59', 'J-29572959-6 | MANUFACTURAS GLOBALTEX GT, C.A.', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (78, '2022-09-09 15:35:25', 'J-30153244-9 | AUTO REPUESTOS GON PER C.A', 'Activa', '	LICENCIA ADM 1 AÑO', 0, 250, 1, 0),
            (79, '2022-09-26 12:29:04', 'J-29761679-9 | REPUESTOS Y ACCESORIOS REVEZU 3010, C.A.', 'Activa', 'LIC SAINT ADM', 0, 250, 1, 0),
            (80, '2023-04-14 14:36:41', 'J-31647037-7 | A.L. SYSTEMS, C.A.', 'Pendiente', 'LICENCIA  ADM 1 AÑO', 0, 250, 0, 1);
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_licencia');
    }
};
