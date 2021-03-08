<?php

namespace App\Console\Commands;

use App\Eloquent\UserSocialProfile;
use Illuminate\Console\Command;

class FixUserSocialProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix-user-social-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set empty rows to null on user_sorial_profiles table';

    /**
     * @const array Fields to process
     */
    const PROCESSABLE = [
            'image',
            'work_phone',
            'mobile_phone',
            'skype',
            'web_site',
            'work_email',
            'personal_email',
            'about_me',
            'face_book',
            'linked_in',
            'v_kontakte',
            'odnoklasniki',
            'company_post',
            'address'
        ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $models = UserSocialProfile::all();
        printf("Found %d rows\n", $models->count());
        print("Processing...\n");

        foreach($models as $model) {
            foreach(self::PROCESSABLE as $field) {
                if($model->$field === "" || $model->$field === 'https://') {
                    $model->$field = null;
                }
            }
            $model->save();
        }

        print("Done!\n");
        return;
    }
}
