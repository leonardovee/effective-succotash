package com.leonardovee.effectivesuccotash.data.usecase

import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw

interface AddTransactionRepository {
    fun addTransaction(deposit: Deposit, withdraw: Withdraw): Transaction
}