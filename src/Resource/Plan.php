<?php

namespace Zabaala\Moip\Resource;

use Zabaala\Moip\Contracts\ResourceManager;
use Zabaala\Moip\Http\HTTPRequest;
use stdClass;

class Plan extends MoipResource implements ResourceManager
{
    /**
     * @const string
     */
    const PATH = 'assinaturas/v1/plans';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Set the Plan code.
     *
     * @param null $code
     */
    public function setCode($code = null) {

        if ($code === null) {
            $this->data->code = uniqid();
        }

        $this->data->code = $code;
    }

    /**
     * Set the plan name. Optional.
     *
     * @param $name
     */
    public function setName($name) {
        $this->data->name = $name;
    }

    /**
     * Set the plan description.
     *
     * @param null $description
     */
    public function setDescription($description = null) {
        $this->data->description = $description;
    }

    /**
     * Set amount of the plan.
     *
     * @param $amount
     */
    public function setAmount($amount) {
        $this->data->amount = $amount;
    }

    /**
     * Set the plan setup fee.
     *
     * @param $setup_fee
     */
    public function setSetupFee($setup_fee = null) {
        $this->data->setup_fee = $setup_fee;
    }

    /**
     * Sets the recurrence of the plan charging.
     *
     * @param string $unit. Unit can be: DAY, MONTH or YEAR.
     * @param int $length
     */
    public function setInterval($unit = 'MONTH', $length = 1) {
        $this->data->interval = new stdClass();
        $this->data->interval->unit = $unit;
        $this->data->interval->length = $length;
    }

    /**
     * Defines how many times the plan must happen.
     *
     * @param $billingCycles
     */
    public function setBillingCycles($billingCycles = null) {
        $this->data->billing_cycles = $billingCycles;
    }

    /**
     * Set max quantity of the subscribes on the plan.
     * If no value was passed, there is no limit.
     *
     * @param null $maxQuantity
     */
    public function setMaxQuantity($maxQuantity = null) {
        $this->max_qty = $maxQuantity;
    }

    public function setTrial($days = 0, $enabled = false, $hold_setup_fee = true) {
        $this->data->trial = new stdClass();
        $this->data->trial->days = $days;
        $this->data->trial->enabled = $enabled;
        $this->data->trial->hold_setup_fee = $hold_setup_fee;
    }

    /**
     * Payment methods accepted by plan.
     * Default value: CREDIT_CARD.
     *
     * @param string $method
     */
    public function setPaymentMethod($method = Payment::METHOD_CREDIT_CARD) {
        $this->data->payment_method = $method;
    }

    /**
     * Create a new Plan.
     *
     * @return stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s', self::PATH));
    }

    /**
     * Update a Plan.
     *
     * @return stdClass
     */
    public function update() {
        return $this->updateResource(sprintf('/%s/%s', self::PATH, $this->data->code));
    }

    /**
     * Inactivate a Plan.
     *
     * @return stdClass
     */
    public function inactivate() {
        return $this->setStatus('inactivate');
    }

    /**
     * Activate a Plan.
     *
     * @return stdClass
     */
    public function activate() {
        return $this->setStatus('activate');
    }

    /**
     * Activate / Deactivate a Plan.
     * @see http://dev.moip.com.br/assinaturas-api/?php#ativar-plano-put
     *
     * @param $action string Possible values: activate | inactivate.
     * @return stdClass
     */
    protected function setStatus($action) {
        return $this->updateResource(sprintf('/%s/%s/%s', self::PATH, $this->data->code, $action));
    }

    /**
     * Find a Plan.
     *
     * @param string $id
     *
     * @return stdClass
     */
    public function find($id)
    {
        return $this->getByPath(sprintf('/%s/%s', self::PATH, $id));
    }

    /**
     * Get All Plans.
     *
     * @return stdClass
     */
    public function all()
    {
        return parent::getAllByPath(sprintf('/%s/', self::PATH));
    }

    /**
    * Mount the plan structure.
    *
    * @param \stdClass $response
    *
    * @return Plan Plan information.
    */
    public function populate(stdClass $response) {

        $plan = clone $this;
        $plan->data = new stdClass();
        $plan->data->code = $this->getIfSet('code', $response);
        $plan->data->name = $this->getIfSet('name', $response);
        $plan->data->description = $this->getIfSet('description', $response);
        $plan->data->amount = $this->getIfSet('amount', $response);
        $plan->data->setup_fee = $this->getIfSet('setup_fee', $response);
        $plan->data->max_qty = $this->getIfSet('max_qty', $response);

        // Interval
        $interval = $this->getIfSet('interval', $response);

        $plan->data->interval = new stdClass();
        $plan->data->interval->length = $this->getIfSet('length', $interval);
        $plan->data->interval->unit = $this->getIfSet('unit', $interval);

        $plan->data->billing_cycles = $this->getIfSet('billing_cycles', $response);

        // Trial
        $trial = $this->getIfSet('interval', $response);

        $plan->data->trial = new stdClass();
        $plan->data->trial->days = $this->getIfSet('days', $trial);
        $plan->data->trial->enabled = $this->getIfSet('enabled', $trial);
        $plan->data->trial->hold_setup_fee = $this->getIfSet('hold_setup_fee', $trial);

        $plan->data->payment_method = $this->getIfSet('payment_method', $response);
        $plan->data->status = $this->getIfSet('status', $response);

        return $plan;
    }
}