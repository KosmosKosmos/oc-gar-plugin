<?php namespace KosmosKosmos\GAR2\Console;

use Illuminate\Console\Command;
use KosmosKosmos\GAR2\Models\Confirm;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeleteConfirm extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'gar:deleteconfirm';

    /**
     * @var string The console command description.
     */
    protected $description = 'Deletes Command by ID';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $confirm = Confirm::find($this->argument('id'));

        if ($confirm) {
            $this->output->writeln('Confirm #'.$this->argument('id').' found. Deleting...');
            $confirm->delete();
        } else {
            $this->warn('Confirm #'.$this->argument('id').' not found.');
        }
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['id', InputArgument::REQUIRED, 'An ID of Confirm to be deleted.'],
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
