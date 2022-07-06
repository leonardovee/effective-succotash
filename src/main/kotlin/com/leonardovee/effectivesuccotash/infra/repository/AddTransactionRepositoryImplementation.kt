package com.leonardovee.effectivesuccotash.infra.repository

import com.leonardovee.effectivesuccotash.data.usecase.AddTransactionRepository
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import org.springframework.stereotype.Component

@Component
class AddTransactionRepositoryImplementation : AddTransactionRepository {
    override fun addTransaction(deposit: Deposit, withdraw: Withdraw): Transaction {
        return Transaction("", "", "")
    }
}