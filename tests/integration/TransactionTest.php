<?php

class TransactionTest extends TestCase
{
    public function test_should_validate_the_request()
    {
        $this->post('/transaction')
            ->seeJsonEquals([
                'amount' => ["The amount field is required."],
                'payer' => ["The payer field is required."],
                'payee' => ["The payee field is required."]
            ]);
    }
}
