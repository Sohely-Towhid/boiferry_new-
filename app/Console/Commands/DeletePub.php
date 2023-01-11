<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Console\Command;

class DeletePub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:pub {id?}';

    /**
     * The console command description.
     *
     * @var string

    protected $description = 'Command description';

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
        $id = $this->argument('id');
        if (!$id) {
            $id = $this->ask('Enter Vendor ID: ');
        }
        $vendor = Vendor::FindorFail($id);
        if ($this->confirm('Do you wish to delete `' . $vendor->name . '` ?')) {
            $this->info('Deleting Books..');
            Book::where('vendor_id', $vendor->id)->delete();
            $this->info('Deleting User...');
            User::where('id', $vendor->user_id)->delete();
            $this->info('Deleting Shop....');
            $vendor->delete();
            $this->info('Vendor delete done!');
        } else {
            $this->info('bye!');
        }
        return 0;
    }
}
