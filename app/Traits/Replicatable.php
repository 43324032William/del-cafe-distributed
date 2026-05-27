<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait Replicatable
{
    protected static function bootReplicatable()
    {
        // 1. Fungsi Otomatis saat Data Baru Dibuat (INSERT)
        static::created(function ($model) {
            $connectionName = 'mysql_notif'; // Nama koneksi replica di config/database.php
            $tableName = $model->getTable();

            // Ambil hanya kolom yang benar-benar ada di tabel database agar tidak error
            $columns = Schema::connection($connectionName)->getColumnListing($tableName);
            $data = array_intersect_key($model->getAttributes(), array_flip($columns));

            if (!empty($data)) {
                DB::connection($connectionName)->table($tableName)->insert($data);
            }
        });

        // 2. Fungsi Otomatis saat Data Diubah (UPDATE)
        static::updated(function ($model) {
            $connectionName = 'mysql_notif';
            $tableName = $model->getTable();

            $columns = Schema::connection($connectionName)->getColumnListing($tableName);
            $data = array_intersect_key($model->getAttributes(), array_flip($columns));

            if (!empty($data)) {
                DB::connection($connectionName)->table($tableName)
                    ->where($model->getKeyName(), $model->getKey())
                    ->update($data);
            }
        });
    }
}