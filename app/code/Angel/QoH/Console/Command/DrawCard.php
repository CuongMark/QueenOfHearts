<?php
namespace Angel\QoH\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SomeCommand
 */
class DrawCard extends Command
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Angel\QoH\Model\DrawCard
     */
    protected $drawCard;
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;
    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     *
     * @throws LogicException When the command name is empty
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Angel\QoH\Model\DrawCard $drawCard,
        \Magento\Framework\App\State $state,
        string $name = null
    ){
        parent::__construct($name);
        $this->state = $state;
        $this->logger = $logger;
        $this->drawCard = $drawCard;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('angel:qoh:draw');
        $this->setDescription('Draw Queen of hearts card.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
        try {
            $this->drawCard->massDrawCard();
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $this->logger->error($e->getMessage());
        }
        $output->writeln('<info>Done!</info>');
    }
}
