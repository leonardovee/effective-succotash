package com.leonardovee.effectivesuccotash.domain.implementation

import com.leonardovee.effectivesuccotash.data.usecase.AddDepositRepository
import com.leonardovee.effectivesuccotash.data.usecase.AddWithdrawRepository
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.presentation.resource.TransactionRequestResource
import org.junit.jupiter.api.Test
import org.mockito.Mockito.*

internal class CreateTransactionImplementationTest {
    private var addWithdrawRepository = mock(AddWithdrawRepository::class.java)
    private var addDepositRepository = mock(AddDepositRepository::class.java)
    private var createTransactionImplementation =
        CreateTransactionImplementation(addDepositRepository, addWithdrawRepository)
    private var transactionRequestResource = TransactionRequestResource("any@email.com", "other@email.com", "10.00")
    private val deposit = Deposit(transactionRequestResource.payee, transactionRequestResource.value, null)
    private val withdraw = Withdraw(transactionRequestResource.payer, transactionRequestResource.value, null)

    @Test
    fun `should call add deposit repository with correct values`() {
        createTransactionImplementation.execute(deposit, withdraw)
        verify(addDepositRepository, times(1)).addDeposit(deposit)
    }

    @Test
    fun `should call add withdraw repository with correct values`() {
        createTransactionImplementation.execute(deposit, withdraw)
        verify(addWithdrawRepository, times(1)).addWithdraw(withdraw)
    }
}