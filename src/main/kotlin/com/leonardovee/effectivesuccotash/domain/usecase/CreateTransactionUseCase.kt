package com.leonardovee.effectivesuccotash.domain.usecase

import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import org.springframework.stereotype.Component

@Component
interface CreateTransactionUseCase {
    fun execute(deposit: Deposit, withdraw: Withdraw): Transaction
}