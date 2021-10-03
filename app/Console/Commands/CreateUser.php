<?php

namespace App\Console\Commands;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateUser extends Command
{
    use PasswordValidationRules;

    const PASSWORD_RULES = [
        'required',
        'min:8',              // must be at least 8 characters in length
        'regex:/[a-z]/',      // must contain at least one lowercase letter
        'regex:/[A-Z]/',      // must contain at least one uppercase letter
        'regex:/[0-9]/',      // must contain at least one digit
        'regex:/[@$!%*#?&]/', // must contain a special character
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Einen Login für ein Vorstandsmitglied anlegen';

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
     * @return int
     */
    public function handle()
    {
        $fails = 0;

        // get name
        $name = $this->ask('Bitte Name eingeben');

        // get email
        $email = $this->ask('Bitte Email-Adresse eingeben');
        while (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $fails++;
            $this->error("Das ist keine gültige Email.");
            $email = $this->ask("Bitte Email-Adresse eingeben");
        }
        while (User::where('email', '=', $email)->exists())
        {
            $fails++;
            $this->error("Diese Email gibt es bereits.");
            $email = $this->ask("Bitte Email-Adresse eingeben");
        }

        $password = $this->secret('Bitte Passwort eingeben');
        // TODO validate password
        $password_check = $this->secret('Bitte Passwort wiederholen');

        while ($password !== $password_check)
        {
            $fails++;
            $this->error("Die Passwörter stimmen nicht überein. Versuch's nochmal:");
            $password_check = $this->secret('Bitte Passwort wiederholen');
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        if ($fails == 0)
        {
            $this->info('Herzlichen Glückwunsch, dein Konto als Vorstandsmitglied wurde angelegt.');
        }
        else
        {
            $this->info("Herzlichen Glückwunsch, du hast erfolgreich ein Konto als Vorstandsmitglied angelegt und dabei nur $fails mal verkackt.");
        }
        $this->info('Du kannst dich jetzt auf psypark.de/login damit einloggen.');

        return 0;
    }
}
