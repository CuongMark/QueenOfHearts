<?php


namespace Angel\QoH\Cron;

class DrawCard
{

    protected $logger;
    protected $drawCard;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Angel\QoH\Model\DrawCard $drawCard
    ){
        $this->logger = $logger;
        $this->drawCard = $drawCard;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->drawCard->massDrawCard();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
