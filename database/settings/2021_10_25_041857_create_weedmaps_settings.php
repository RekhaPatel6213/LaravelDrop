<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateWeedmapsSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('weedmaps.wm_client_id','kGLdud_9zNJpaVgv7HLRZAwEdpOCjHfdHU_l4ZhODnc');
        $this->migrator->add('weedmaps.wm_client_secret','WR-fPzJU5AxsBPykPZaECWft0wnsFGGt4qCNkLeffzE');
    }
}
