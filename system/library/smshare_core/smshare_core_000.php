<?php
namespace smshare_core;

class smshare_core_000 {

    protected $registry;
    protected $config;
    protected $load;
    protected $log;
    protected $db;


    public function __construct($registry) {
        $this->registry = $registry;
        $this->config   = $registry->get('config');
        $this->log      = $registry->get('log');
        $this->load     = $registry->get('load');
        $this->db       = $registry->get('db');
    }
}