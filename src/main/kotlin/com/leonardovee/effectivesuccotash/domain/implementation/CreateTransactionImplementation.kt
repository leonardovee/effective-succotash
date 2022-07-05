package com.leonardovee.effectivesuccotash.domain.implementation

import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.domain.usecase.CreateTransactionUseCase
import org.springframework.stereotype.Component

@Component
class CreateTransactionImplementation: CreateTransactionUseCase {
    override fun execute(deposit: Deposit, withdraw: Withdraw): Number {
        return 1
    }
}