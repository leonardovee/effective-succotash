package com.leonardovee.effectivesuccotash.domain.implementation

import com.leonardovee.effectivesuccotash.data.usecase.AddDepositRepository
import com.leonardovee.effectivesuccotash.data.usecase.AddTransactionRepository
import com.leonardovee.effectivesuccotash.data.usecase.AddWithdrawRepository
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.domain.usecase.CreateTransactionUseCase
import org.springframework.stereotype.Component

@Component
class CreateTransactionImplementation(
    private val addDepositRepository: AddDepositRepository, private val addWithdrawRepository: AddWithdrawRepository, private val addTransactionRepository: AddTransactionRepository
) : CreateTransactionUseCase {
    override fun execute(deposit: Deposit, withdraw: Withdraw): Transaction {
        val addedDeposit = addDepositRepository.addDeposit(deposit)
        val addedWithdraw = addWithdrawRepository.addWithdraw(withdraw)
        addTransactionRepository.addTransaction(addedDeposit, addedWithdraw)
        return Transaction("1", "2", "3")
    }
}