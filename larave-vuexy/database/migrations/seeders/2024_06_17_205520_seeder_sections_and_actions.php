<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE role_sections
            DROP CONSTRAINT role_sections_section_id_foreign;
        ");

        DB::statement("
            ALTER TABLE section_actions 
            DROP CONSTRAINT section_actions_section_id_foreign;
        ");

        DB::statement("
            ALTER TABLE section_actions
            DROP CONSTRAINT section_actions_action_id_foreign;
        ");

        DB::table('section_actions')->truncate();
        DB::table('sections')->truncate();
        DB::table('actions')->truncate();

        $sections = [
            /*1*/ ['name' => 'root', 'title' => 'Panel', 'description' => null, 'parent_id' => null, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],

            /**COMMERCIAL*/
            /*2*/ ['name' => 'commercial', 'title' => 'Comercial', 'description' => null, 'parent_id' => null, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            /*2*//*3*/ ['name' => 'commercial-prices', 'title' => 'Precios', 'description' => 'Listas de precios', 'parent_id' => 2, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            /*2*//*4*/ ['name' => 'commercial-stocks', 'title' => 'Stocks', 'description' => 'Lista de stocks', 'parent_id' => 2, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            /*2*//*3*//*5*/ ['name' => 'commercial-prices-fia', 'title' => 'Fia', 'description' => null, 'parent_id' => 3, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            /*2*//*3*//*6*/ ['name' => 'commercial-prices-infrima', 'title' => 'Infrima', 'description' => null, 'parent_id' => 3, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],

            /**ACCESS */
            /*7*/ ['name' => 'access', 'title' => 'Accesos', 'description' => null, 'parent_id' => null, 'order' => 100, 'created_at' => now(), 'updated_at' => now()],
            /*7*//*8*/ ['name' => 'access-users', 'title' => 'Usuarios', 'description' => null, 'parent_id' => 7, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            /*7*//*9*/ ['name' => 'access-roles', 'title' => 'Roles', 'description' => null, 'parent_id' => 7, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],

            /**COMMERCIAL*/
            /*2*//*3*//*10*/ ['name' => 'commercial-prices-atq', 'title' => 'Atq', 'description' => null, 'parent_id' => 3, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            /*2*//*3*//*11*/ ['name' => 'commercial-prices-quo', 'title' => 'Quo', 'description' => null, 'parent_id' => 3, 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
            /*2*//*3*//*12*/ ['name' => 'commercial-prices-ecuador', 'title' => 'Ecuador', 'description' => null, 'parent_id' => 3, 'order' => 5, 'created_at' => now(), 'updated_at' => now()],

            /**CONFIGURACION*/
            /*13*/ ['name' => 'setting', 'title' => 'Configuración', 'description' => null, 'parent_id' => null, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($sections as $section) {
            DB::table('sections')->insert($section);
        }

        $actions = [
            /*1*/ ['name' => 'Ver', 'description' => null, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            /*2*/ ['name' => 'Agregar', 'description' => null, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            /*3*/ ['name' => 'Editar', 'description' => null, 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            /*4*/ ['name' => 'Eliminar', 'description' => null, 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
            /*5*/ ['name' => 'Activa/Desactivar', 'description' => null, 'order' => 5, 'created_at' => now(), 'updated_at' => now()],
            /*6*/ ['name' => 'Permisos', 'description' => null, 'order' => 6, 'created_at' => now(), 'updated_at' => now()],
            /*7*/ ['name' => 'Exportar', 'description' => "Exportar a excel", 'order' => 7, 'created_at' => now(), 'updated_at' => now()],
            /*8*/ ['name' => 'Sincronizar', 'description' => "Sincronizar información de tabla con informacion de otras BD", 'order' => 8, 'created_at' => now(), 'updated_at' => now()],

            /*9*/ ['name' => 'Menú / LP. ventas', 'description' => "Opcion dentro de filtros", 'order' => 9, 'created_at' => now(), 'updated_at' => now()],
            /*9*//*10*/ ['name' => 'Seleccionar', 'description' => null, 'parent_id' => 9, 'level' => 2, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            /*9*//*11*/ ['name' => 'Editar', 'description' => null, 'parent_id' => 9, 'level' => 2, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],

            /*12*/ ['name' => 'Menú / LP. asesores', 'description' => "Opcion dentro de filtros", 'order' => 9, 'created_at' => now(), 'updated_at' => now()],
            /*12*//*13*/ ['name' => 'Seleccionar', 'description' => null, 'parent_id' => 12, 'level' => 2, 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            /*12*//*14*/ ['name' => 'Editar', 'description' => null, 'parent_id' => 12, 'level' => 2, 'order' => 2, 'created_at' => now(), 'updated_at' => now()],

            /*15*/ ['name' => 'Menú / LP. todos', 'description' => "Opcion dentro de filtros", 'order' => 9, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($actions as $action) {
            DB::table('actions')->insert($action);
        }

        /** AGREGAR ACCIONES A LAS SECCIONES */
        $panel_id = DB::table('sections')->where('name', 'root')->first()->id;
        $commercial_prices_fia_id = DB::table('sections')->where('name', 'commercial-prices-fia')->first()->id;
        $commercial_prices_infrima_id = DB::table('sections')->where('name', 'commercial-prices-infrima')->first()->id;
        $access_users_id = DB::table('sections')->where('name', 'access-users')->first()->id;
        $access_roles_id = DB::table('sections')->where('name', 'access-roles')->first()->id;
        $commercial_prices_atq_id = DB::table('sections')->where('name', 'commercial-prices-atq')->first()->id;
        $commercial_prices_quo_id = DB::table('sections')->where('name', 'commercial-prices-quo')->first()->id;
        $commercial_prices_ecuador_id = DB::table('sections')->where('name', 'commercial-prices-ecuador')->first()->id;
        $setting = DB::table('sections')->where('name', 'setting')->first()->id;
        /** array */
        $section_actions = [
            ['section_id' => $panel_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $commercial_prices_fia_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 13, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_fia_id, 'action_id' => 15, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 13, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_infrima_id, 'action_id' => 15, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $commercial_prices_atq_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 13, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_atq_id, 'action_id' => 15, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $commercial_prices_quo_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 13, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_quo_id, 'action_id' => 15, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $commercial_prices_ecuador_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_ecuador_id, 'action_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $commercial_prices_ecuador_id, 'action_id' => 8, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $access_users_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $access_users_id, 'action_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $access_users_id, 'action_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $access_users_id, 'action_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $access_roles_id, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $access_roles_id, 'action_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $access_roles_id, 'action_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $access_roles_id, 'action_id' => 6, 'created_at' => now(), 'updated_at' => now()],

            ['section_id' => $setting, 'action_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['section_id' => $setting, 'action_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($section_actions as $section_action) {
            DB::table('section_actions')->insert($section_action);
        }

        $sections = DB::select("SELECT id FROM sections");

        DB::table('role_sections')->where('role_id', 1)->delete();

        foreach ($sections as $section) {
            $actions_simple = [];
            foreach ($section_actions as $action) {
                if ($action['section_id'] == $section->id) {
                    $actions_simple[] = $action['action_id'];
                }
            }

            if (count($actions_simple) == 0) {
                continue;
            }

            DB::table('role_sections')->insert([
                ['role_id' => 1, 'section_id' => $section->id, 'actions' => json_encode($actions_simple), 'created_at' => now(), 'updated_at' => now()]
            ]);
        }

        DB::statement("
            ALTER TABLE role_sections
            ADD CONSTRAINT role_sections_section_id_foreign
            FOREIGN KEY (section_id) REFERENCES sections(id)
        ");

        DB::statement("
            ALTER TABLE section_actions
            ADD CONSTRAINT section_actions_section_id_foreign
            FOREIGN KEY (section_id) REFERENCES sections(id)
        ");

        DB::statement("
            ALTER TABLE section_actions
            ADD CONSTRAINT section_actions_action_id_foreign
            FOREIGN KEY (action_id) REFERENCES actions(id)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
