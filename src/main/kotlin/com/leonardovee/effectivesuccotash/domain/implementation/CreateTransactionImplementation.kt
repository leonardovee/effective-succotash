package com.leonardovee.effectivesuccotash.domain.implementation

import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.domain.usecase.CreateTransactionUseCase
import org.springframework.stereotype.Component

@Component
class CreateTransactionImplementation: CreateTransactionUseCase {
    override fun execute(deposit: Deposit, withdraw: Withdraw): Transaction {
        return Transaction("1", "2", "3")
    }
}