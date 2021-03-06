<?php

namespace Zabaala\Moip\Resource;

use stdClass;

class Invoice extends MoipResource
{
    /**
     * Path for all invoices.
     *
     * @const string
     */
    const PATH_ALL = 'assinaturas/v1/subscriptions/{{subscription_code}}/invoices';

    /**
     * Path to a specified invoice.
     *
     * @const string
     */
    const PATH = 'assinaturas/v1/invoices';

    /**
     * Subscription code.
     *
     * @var string
     */
    protected $subscription_code;

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Set Subscription code.
     *
     * @param $code
     */
    public function setSubscriptionCode($code) {
        $this->subscription_code = $code;
    }

    /**
     * Find all Invoices.
     *
     * @return stdClass
     */
    public function all()
    {
        return $this->getByPath(sprintf('/%s', str_replace('{{subscription_code}}', $this->subscription_code, self::PATH_ALL)), true);
    }

    /**
     * Find a Invoice.
     *
     * @param string $id
     *
     * @return stdClass
     */
    public function get($id)
    {
        return $this->getByPath(sprintf('/%s/%s', self::PATH, $id));
    }

    /**
     * @param stdClass $response
     * @return stdClass
     */
    public function populate(stdClass $response) {
        $this->data = $response;
        return $this->data;
    }

}
