<?php
namespace Fifteen;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PlayCommand extends Command
{
    private $game;

    public function __construct($game)
    {
        $this->game = $game;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('play')
             ->setDescription('Starts game of fifteen.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . $this->game->greet() . '</info>');

        $helper = $this->getHelper('question');
        $question = new Question('Please enter the size of the board (must be number between 3 and 9 inclusive): ');

        do {
            $gridSize = $helper->ask($input, $output, $question);
        } while (!$this->game->init($gridSize));

        $table = new Table($output);
        $board = $this->game->draw();
        $table->setRows($board)->render();

        $questionMove = new Question('Your move: ');

        do {
            $move = $helper->ask($input, $output, $questionMove);

            try {
                $this->game->move($move);
            } catch (\Exception $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');
            }

            // render table again
            $board = $this->game->draw();
            $table->setRows($board)->render();
        } while (!$this->game->gameWon());

        $output->writeln('<info>' . $this->game->end() . '</info>');
    }
}
